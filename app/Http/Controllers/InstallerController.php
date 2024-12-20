<?php

namespace App\Http\Controllers;

use PDO;
use PDOException;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class InstallerController extends Controller
{
    public function index()
    {
        $installViewPath = resource_path('views/install.blade.php');
        if (File::exists($installViewPath)) {
            // Check if the symlink function is available
            if (function_exists('symlink')) {
                try {
                    // Attempt to create the symbolic link between the public directory and the storage directory
                    Artisan::call('storage:link');
                } catch (\Exception $e) {
                    // Log or handle the exception (if needed)
                }
            }
            // Regardless of whether the symlink function is available or not, proceed to clear cache and return to the install view
            return $this->clearAndReturnToInstallView();
        } else {
            return redirect('/');
        }
    }

    private function clearAndReturnToInstallView()
    {
        // Clear cache
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        // Return install view
        return view('install');
    }

    public function config_db(Request $request)
    {
        $formFields = $request->validate([
            'db_name' => ['required'],
            'db_host_name' => ['required'],
            'db_user_name' => ['required'],
            'db_password' => 'nullable',
        ]);

        try {
            // Replace these values with your actual database configuration
            $pdo = new PDO("mysql:host={$formFields['db_host_name']};dbname={$formFields['db_name']}", $formFields['db_user_name'], $formFields['db_password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $envFilePath = base_path('.env');
            $envContent = file_get_contents($envFilePath);

            $envContent = preg_replace('/^DB_HOST=.*$/m', "DB_HOST={$formFields['db_host_name']}", $envContent);
            $envContent = preg_replace('/^DB_DATABASE=.*$/m', "DB_DATABASE={$formFields['db_name']}", $envContent);
            $envContent = preg_replace('/^DB_USERNAME=.*$/m', "DB_USERNAME={$formFields['db_user_name']}", $envContent);
            $envContent = preg_replace('/^DB_PASSWORD=.*$/m', "DB_PASSWORD={$formFields['db_password']}", $envContent);
            file_put_contents($envFilePath, $envContent);
            return response()->json(['error' => false, 'message' => 'Connected to the database successfully.']);
        } catch (PDOException $e) {
            return response()->json(['error' => true, 'message' => "Connection failed: " . $e->getMessage()]);
        }
    }

    public function install(Request $request)
    {
        ini_set('max_execution_time', 900);

        // Validate user-related fields
        $userFields = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'password' => 'required|min:6|confirmed'
        ]);

        // Hash the password
        $userFields['password'] = Hash::make($userFields['password']);
        $userFields['status'] = 1;
        $userFields['email_verified_at'] = now();
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        DB::purge('mysql');

        // Import the SQL dump file
        $installViewPath = resource_path('views/install.blade.php');
        $sqlDumpPath = base_path('taskify.sql');
        if (file_exists($sqlDumpPath)) {
            $sql = file_get_contents($sqlDumpPath);
            DB::unprepared($sql);


            $user = User::create($userFields);
            if ($user) {
                $user->assignRole(1);

                $workspaceFields = [
                    'user_id' => $user->id,
                    'title' => 'Default Workspace'
                ];

                $new_workspace = Workspace::create($workspaceFields);

                $workspace_id = $new_workspace->id;
                $workspace = Workspace::find($workspace_id);
                $workspace->users()->attach([$user->id]);

                File::delete($installViewPath);
                unlink($sqlDumpPath);

                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');

                return response()->json(['error' => false, 'message' => 'Congratulations! Installation completed successfully.']);
            } else {
                return response()->json(['error' => true, 'message' => 'Oops! Installation failed. Please try again.']);
            }
        } else {
            return response()->json(['error' => true, 'message' => 'Oops! Installation couldn\'t process.']);
        }
    }
}
