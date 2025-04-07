<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\MembershipCategory;
use App\Mail\welcomeNewMemberEmail;
use App\Models\Branch;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'gender' => ['required', 'string'],
            'marital_status' => ['required', 'string'],
            'age' => ['required', 'integer'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'next_of_kin' => ['nullable', 'string'],
            'next_of_kin_phone' => ['nullable', 'string'],
            'uma_branch' => 'required|exists:branches,name',
            'employer' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'membership_category_id' => ['required', 'exists:membership_categories,id'],
            'referee' => ['required', 'string'],
            'referee_phone1' => ['required', 'string'],
            'referee_phone2' => ['nullable', 'string'],

            'photo' => ['nullable', 'image', 'max:10240'], // 10MB Max
            'signature' => ['nullable', 'image', 'max:10240'],
            'national_id' => ['required', 'image', 'max:10240'],

            'payment_mode' => ['required', 'string'],
        ];

        // Conditional validation based on category
        if (isset($data['category'])) {
            if ($data['category'] === 'Specialist') {
                $rules['specialization'] = ['required', 'string'];
            }

            if (in_array($data['category'], ['Medical Student', 'Medical Officer', 'Specialist', 'Intern Doctor'])) {
                $rules['umdpc_number'] = ['required', 'string'];
                $rules['license_document'] = ['required', 'image', 'max:10240'];
            }
        }

        // Conditional validation based on payment mode
        if (isset($data['payment_mode']) && $data['payment_mode'] === 'Mobile Money') {
            $rules['payment_phone'] = ['required', 'string'];
        }

        return Validator::make($data, $rules);
    }


    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        try {
            $user = $this->create($request->all());

            if (!$user) {
                throw new \Exception('User creation failed');
            }

            event(new Registered($user));

            Log::info('User registered successfully', ['user_id' => $user->id, 'email' => $user->email]);

            $this->guard()->login($user);

            return $this->registered($request, $user)
                ?: redirect($this->redirectTo);
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['registration_error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }


    protected function create(array $data)
    {
        // Log the incoming data for debugging (remove sensitive info in production)
        Log::info('Registration data received', $data, ['password', 'password_confirmation']);

        try {
            // 1. Create the user first
            $password = Str::random(8);
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($password),
            ]);

            // Send the generated password via email
            Mail::to($user->email)->send(new welcomeNewMemberEmail($user, $password));

            // 2. Handle file uploads with proper error checking
            $photoPath = null;
            $signaturePath = null;
            $nationalIdPath = null;
            $licensePath = null;

            if (isset($data['photo']) && $data['photo']->isValid()) {
                $photoPath = $data['photo']->store('photos', 'public');
            } else {
                throw new \Exception('Photo upload failed');
            }

            if (isset($data['signature']) && $data['signature']->isValid()) {
                $signaturePath = $data['signature']->store('signatures', 'public');
            } else {
                throw new \Exception('Signature upload failed');
            }

            if (isset($data['national_id']) && $data['national_id']->isValid()) {
                $nationalIdPath = $data['national_id']->store('documents', 'public');
            } else {
                throw new \Exception('National ID upload failed');
            }

            // License is conditional based on category
            if (in_array($data['category'], ['Medical Officer', 'Specialist', 'Intern Doctor'])) {
                if (isset($data['license_document']) && $data['license_document']->isValid()) {
                    $licensePath = $data['license']->store('licenses', 'public');
                } else {
                    throw new \Exception('License upload failed');
                }
            }
            Log::info('Uploaded files', request()->allFiles());

            // 3. Get the proper membership category ID based on the selected type
            // $membershipCategoryId = $this->getMembershipCategoryId($data['membership_category']);

            // 4. Create user profile with all the form data
            $profileData = [
                'gender' => $data['gender'],
                'marital_status' => $data['marital_status'],
                'age' => $data['age'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'uma_branch' => $data['uma_branch'],
                'employer' => $data['employer'],
                'category' => $data['category'],
                'specialization' => $data['specialization'] ?? null,
                'umdpc_number' => $data['umdpc_number'] ?? null,
                'membership_category_id' => $data['membership_category_id'],
                'next_of_kin' => $data['next_of_kin'],
                'next_of_kin_phone' => $data['next_of_kin_phone'],
                'referee' => $data['referee'],
                'referee_phone1' => $data['referee_phone1'],
                'referee_phone2' => $data['referee_phone2'],
                'photo' => $photoPath,
                'signature' => $signaturePath,
                'national_id' => $nationalIdPath,
                'license_document' => $licensePath,
                'registration_status' => 'pending',
            ];

            // dd($profileData);

            // 5. Create payment record
            $paymentInfo = [
                'payment_mode' => $data['payment_mode'],
                'payment_phone' => $data['payment_phone'] ?? null,
                // Add other payment fields as needed
            ];

            // Log the profile data before saving
            // Log::info('Creating user profile', ['user_id' => $user->id, 'profile_data' => $profileData]);
            if ($user) {
                $profile = $user->profile()->create($profileData);
                Log::info('Creating user profile', ['user_id' => $user->id, 'profile_data' => $profileData]);
            } else {
                throw new \Exception('User model was not created');
            }

            // 6. Save the profile
            $profile = $user->profile()->create($profileData);

            // 7. Create payment record (assuming you have a payment model)
            // $user->payments()->create($paymentInfo);

            // Log successful profile creation
            Log::info('User profile created successfully', ['profile_id' => $profile->id]);

            return $user;
        } catch (\Exception $e) {
            // If there's an error, log it and delete the user if it was created
            Log::error('Error in create method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // If the user was created but profile failed, delete the user to avoid orphaned records
            if (isset($user) && $user) {
                $user->delete();
            } else {
                return redirect()->route('home');
            }

            // Re-throw the exception to be caught by the register method
            throw $e;
        }
    }

    public function showRegistrationForm()
    {
        $membershipCategories = MembershipCategory::all();
        $branches = Branch::all();
        return view('auth.register', compact('membershipCategories', 'branches'));
    }
}
