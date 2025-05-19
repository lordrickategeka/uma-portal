<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserManagerController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('dashboard.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('dashboard.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'Roles updated.');
    }

    public function showImportForm()
    {
        return view('dashboard.users.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:20480', // 20MB max
        ]);

        try {
            // Store the uploaded file
            $path = $request->file('file')->store('imports');
            
            // Get the import mode (queue or immediate)
            $useQueue = $request->has('use_queue');
            
            if ($useQueue) {
                // Queue the import job
                ProcessUserImport::dispatch($path);
                
                return redirect()->route('admin.users.index')
                    ->with('success', 'User import has been queued and will be processed in the background.');
            } else {
                // Increase the time limit for this request
                set_time_limit(300); // 5 minutes
                
                // Immediate import with smaller chunk size
                $import = new UsersImport;
                $import->setChunkSize(100); // Smaller chunks to avoid timeout
                
                Excel::import($import, storage_path('app/' . $path));
                
                return redirect()->route('admin.users.index')
                    ->with('success', 'Users imported successfully.');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            
            Log::error('User import validation failed', ['errors' => $errors]);
            
            return redirect()->back()
                ->withErrors(['import_errors' => $errors])
                ->withInput();
        } catch (\Exception $e) {
            Log::error('User import failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return redirect()->back()
                ->withErrors(['file' => 'Error importing users: ' . $e->getMessage()])
                ->withInput();
        }
    }

     public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add headers
        $sheet->setCellValue('A1', 'first_name');
        $sheet->setCellValue('B1', 'last_name');
        $sheet->setCellValue('C1', 'email');
        $sheet->setCellValue('D1', 'password');
        $sheet->setCellValue('E1', 'email_verified_at');
        $sheet->setCellValue('F1', 'temp_password');
        
        // Add sample data (optional)
        $sheet->setCellValue('A2', 'John');
        $sheet->setCellValue('B2', 'Doe');
        $sheet->setCellValue('C2', 'john.doe@example.com');
        $sheet->setCellValue('D2', 'password123');
        $sheet->setCellValue('E2', now()->toDateTimeString());
        $sheet->setCellValue('F2', '');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        
        // Bold the header row
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        
        // Create temporary file
        $fileName = 'users_import_template.xlsx';
        $tempPath = storage_path('app/temp/' . $fileName);
        
        // Ensure directory exists
        if (!Storage::exists('temp')) {
            Storage::makeDirectory('temp');
        }
        
        // Save file
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);
        
        // Return file as download
        return response()->download($tempPath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
