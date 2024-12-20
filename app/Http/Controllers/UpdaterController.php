<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Update;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Migrations\Migrator;

class UpdaterController extends Controller
{
    public function index()
    {
        return view('settings.system_updater');
    }

    public function is_dir_empty($dir)
    {
        if (!is_readable($dir)) {
            return null;
        }

        return (count(scandir($dir)) == 2);
    }

    function update(Request $request)
    {
        ini_set('max_execution_time', 900);
        $zip = new ZipArchive();
        $updatePath = Config::get('constants.UPDATE_PATH');
        $fullUpdatePath = public_path($updatePath);

        if (!empty($_FILES['update_file']['name'][0])) {
            if (!File::exists(public_path($updatePath))) {
                File::makeDirectory(public_path($updatePath), 0777, true);
            }

            $uploadData = $request->file('update_file.0');
            $ext = trim(strtolower($uploadData->getClientOriginalExtension()));

            // Check if the extension is zip
            if ($ext != "zip") {
                Session::flash('error', 'Please insert a valid Zip File.');
                $response = [
                    "error" => true,
                    "message" => "Please insert a valid Zip File.",
                ];
                return response()->json($response);
            }


            if ($uploadData->move(public_path($updatePath))) {

                $filename = $uploadData->getFilename();
                ## Extract the zip file ---- start
                $zip = new ZipArchive();
                $res = $zip->open(public_path($updatePath) . $filename);

                if ($res === true) {
                    $extractPath = public_path($updatePath);
                    // Extract file
                    $zip->extractTo($extractPath);
                    $zip->close();
                    if (file_exists($updatePath . "package.json") || file_exists($updatePath . "plugin/package.json")) {

                        $system_info = get_system_update_info();
                        if (isset($system_info['updated_error']) || isset($system_info['sequence_error'])) {
                            $response = [
                                'error' => true,
                                'message' => $system_info['message']
                            ];
                            Session::flash('error', $system_info['message']);
                            File::deleteDirectory($updatePath);
                            return response()->json($response);
                        }


                        /* Plugin / Module installer script */
                        $sub_directory = (file_exists($updatePath . "plugin/package.json")) ? "plugin/" : "";

                        if (file_exists($updatePath . $sub_directory . "package.json")) {
                            $package_data = file_get_contents($updatePath . $sub_directory . "package.json");
                            $package_data = json_decode($package_data, true);
                            if (!empty($package_data)) {
                                /* Folders Creation - check if folders.json is set if yes then create folders listed in that file */
                                if (isset($package_data['folders']) && !empty($package_data['folders'])) {
                                    $jsonFilePath = $updatePath . $sub_directory . $package_data['folders'];

                                    if (file_exists($jsonFilePath)) {
                                        $lines_array = file_get_contents($jsonFilePath);

                                        if ($lines_array !== false && !empty($lines_array)) {
                                            $lines_array = json_decode($lines_array, true);

                                            if ($lines_array !== null) {
                                                foreach ($lines_array as $key => $line) {
                                                    $sourcePath = public_path($key);
                                                    $destination = base_path($line);

                                                    // Ensure directory existence
                                                    if (!is_dir($destination) && !file_exists($destination)) {
                                                        mkdir($destination, 0777, true);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                /* Files Copy - check if files.json is set if yes then copy the files listed in that file */
                                if (isset($package_data['files']) && !empty($package_data['files'])) {
                                    /* copy files from source to destination as set in the file */
                                    if (file_exists($updatePath . $sub_directory . $package_data['files'])) {
                                        $lines_array = file_get_contents($updatePath . $sub_directory . $package_data['files']);
                                        if (!empty($lines_array)) {
                                            $lines_array = json_decode($lines_array);
                                            foreach ($lines_array as $key => $line) {

                                                $sourcePath = public_path($updatePath) . $sub_directory . $key;
                                                $sourcePath = str_replace('/', DIRECTORY_SEPARATOR, $sourcePath);

                                                $destination = base_path($line);
                                                $destination = str_replace('/', DIRECTORY_SEPARATOR, $destination);
                                                $destinationDirectory = dirname($destination);

                                                if (!is_dir($destinationDirectory)) {
                                                    mkdir($destinationDirectory, 0755, true);
                                                }

                                                if (file_exists($sourcePath)) {
                                                    copy($sourcePath, $destination);
                                                }
                                            }
                                        }
                                    }
                                }
                                /* ZIP Extraction - check if archives.json is set if yes then extract the files on destination as mentioned */
                                if (isset($package_data['archives']) && !empty($package_data['archives'])) {
                                    /* extract the archives in the destination folder as set in the file */
                                    if (file_exists($updatePath . $sub_directory . $package_data['archives'])) {
                                        $lines_array = file_get_contents($updatePath . $sub_directory . $package_data['archives']);
                                        if (!empty($lines_array)) {
                                            $lines_array = json_decode($lines_array);
                                            $zip = new ZipArchive;
                                            foreach ($lines_array as $source => $destination) {
                                                // $source = $updatePath . $sub_directory . $source; // Full path to source file
                                                $destination = base_path($destination);
                                                $destination = str_replace('/', DIRECTORY_SEPARATOR, $destination); // Replace forward slashes with the correct directory separator
                                                $res = $zip->open(public_path($updatePath) . $sub_directory . $source);
                                                if ($res === TRUE) {
                                                    $zip->extractTo($destination);
                                                    $zip->close();
                                                }
                                            }
                                        }
                                    }
                                }


                                /* run the migration if there is any */
                                $pathToMigrationDir = public_path($updatePath) . $sub_directory . 'update-files/database/migrations';
                                $pathToMigrationDir = str_replace('/', DIRECTORY_SEPARATOR, $pathToMigrationDir);
                                $pathToMigrations = 'public/' . $updatePath . $sub_directory . 'update-files/database/migrations';
                                $pathToMigrations = str_replace('/', DIRECTORY_SEPARATOR, $pathToMigrations);

                                if (is_dir($pathToMigrationDir)) {
                                    try {
                                        Artisan::call('migrate', ['--path' => $pathToMigrations]);
                                    } catch (\Throwable $e) {
                                        // Handle any exceptions or errors
                                    }
                                }
                                if (isset($package_data['manual_queries']) && $package_data['manual_queries']) {
                                    if (isset($package_data['query_path']) && $package_data['query_path'] != "") {
                                        $sqlContent = File::get($fullUpdatePath . $package_data['query_path']);
                                        $queries = explode(';', $sqlContent);

                                        foreach ($queries as $query) {
                                            $query = trim($query);
                                            if (!empty($query)) {
                                                try {
                                                    DB::statement($query);
                                                } catch (\Throwable $e) {
                                                    // Handle any exceptions or errors
                                                }
                                            }
                                        }
                                    }
                                }

                                $data = array('version' => $system_info['file_current_version']);
                                Update::create($data);

                                File::deleteDirectory(public_path($updatePath));

                                // Clear application caches
                                Artisan::call('cache:clear');
                                Artisan::call('config:clear');
                                Artisan::call('route:clear');
                                Artisan::call('view:clear');
                                $response = [
                                    'error' => false,
                                    'message' => 'Congratulations! Version ' . $package_data['version'] . ' is successfully installed.',
                                ];

                                Session::flash('message', 'Congratulations! Version ' . $package_data['version'] . ' is successfully installed.');
                                return response()->json($response);
                            } else {
                                Session::flash('error', 'Invalid plugin installer file!. No package data found / missing package data.');
                                $response = [
                                    'error' => true,
                                    'message' => 'Invalid plugin installer file!. No package data found / missing package data.',
                                ];
                                File::deleteDirectory(public_path($updatePath));
                                return response()->json($response);
                            }
                        }
                    } else {
                        Session::flash('error', 'Invalid update file! It seems like you are trying to update the system using the wrong file.');
                        $response = [
                            'error' => true,
                            'message' => 'Invalid update file! It seems like you are trying to update the system using the wrong file.',
                        ];

                        File::deleteDirectory(public_path($updatePath));
                    }
                } else {
                    Session::flash('error', 'Extraction failed.');
                    $response['error'] = true;
                    $response['message'] = "Extraction failed.";
                }
            } else {
                Session::flash('error', $uploadData->getErrorString());
                $response['error'] = true;
                $response['message'] = $uploadData->getErrorString();
            }
        } else {
            Session::flash('error', 'You did not select a file to upload.');
            $response['error'] = true;
            $response['message'] = 'You did not select a file to upload.';
        }
        return response()->json($response);
    }
}
