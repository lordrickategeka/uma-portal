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
use App\Models\PaymentMethod;
use App\Models\UserPaymentMethod;
use Spatie\Permission\Models\Role;

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
            // first step
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

            // second step
            'uma_branch' => 'required|exists:branches,name',
            'employer' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'membership_category_id' => ['required', 'exists:membership_categories,id'],
            'referee' => ['required', 'string'],
            'referee_phone1' => ['required', 'string'],
            'referee_phone2' => ['nullable', 'string'],

            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240'], // 10MB Max
            'signature' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240'],
            'national_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240'],

            'payment_mode' => ['required', 'string'],
        ];

        // Conditional validation based on category
        if (isset($data['category'])) {
            if ($data['category'] === 'Specialist') {
                $rules['specialization'] = ['required', 'string'];
            }

            if (in_array($data['category'], ['Medical Student', 'Medical Officer', 'Specialist'])) {
                $rules['umdpc_number'] = ['required', 'string'];
                $rules['license_document'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240'];
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

            // Flash the success message to the session
            $message = "Welcome to the UMA portal, your login credentials are as below: <br>" .
                "Email: " . $user->email . "<br>" .
                "Password: " . $user->temp_password . "<br>" .
                "These credentials have also been sent to your email (" . $user->email . ").<br>" .
                "Please login for further proceedings.";

            // Redirect to the portal with the success message
            return redirect()->route('login')
                ->with('status', ['type' => 'success', 'message' => $message]);
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
                'temp_password' => $password,
            ]);

            $memberRole = Role::firstOrCreate(['name' => 'member']);
            $user->assignRole($memberRole);

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
                    $licensePath = $data['license_document']->store('licenses', 'public');
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
                'user_id' => $user->id,
                'payment_method_id' => $data['payment_mode'], // the selected mode from the form
                'account_number' => $data['payment_phone'] ?? null, // Mobile number used to send money
                'is_default' => true,
            ];
            UserPaymentMethod::create($paymentInfo);

            // Log the profile data before saving
            // Log::info('Creating user profile', ['user_id' => $user->id, 'profile_data' => $profileData]);
            // 6. Save the profile
            if ($user) {
                $profile = $user->profile()->create($profileData);
                Log::info('Creating user profile', ['user_id' => $user->id, 'profile_data' => $profileData]);
            } else {
                throw new \Exception('User model was not created');
            }

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
        $payment_methods = PaymentMethod::all();
        return view('auth.register', compact('membershipCategories', 'branches', 'payment_methods'));
    }
}
