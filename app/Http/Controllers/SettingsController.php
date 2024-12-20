<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $timezones = get_timezone_array();
        return view('settings.general_settings', compact('timezones'));
    }

    public function pusher()
    {
        return view('settings.pusher_settings');
    }

    public function email()
    {
        return view('settings.email_settings');
    }

    public function sms_gateway()
    {
        return view('settings.sms_gateway_settings');
    }

    public function media_storage()
    {
        return view('settings.media_storage_settings');
    }

    public function templates()
    {
        return view('settings.template_settings');
    }

    public function store_general_settings(Request $request)
    {
        $request->validate([
            'company_title' => ['required'],
            'timezone' => ['required'],
            'currency_full_form' => ['required'],
            'currency_symbol' => ['required'],
            'currency_code' => ['required'],
            'date_format' => ['required']
        ]);
        $settings = [];
        $fetched_data = Setting::where('variable', 'general_settings')->first();
        if ($fetched_data != null) {
            $settings = json_decode($fetched_data->value, true);
        }
        $form_val = $request->except('_token', '_method', 'redirect_url');
        $old_logo = isset($settings['full_logo']) && !empty($settings['full_logo']) ? $settings['full_logo'] : '';
        if ($request->hasFile('full_logo')) {
            Storage::disk('public')->delete($old_logo);
            $form_val['full_logo'] = $request->file('full_logo')->store('logos', 'public');
        } else {
            $form_val['full_logo'] = $old_logo;
        }

        $old_half_logo = isset($settings['half_logo']) && !empty($settings['half_logo']) ? $settings['half_logo'] : '';
        if ($request->hasFile('half_logo')) {
            Storage::disk('public')->delete($old_half_logo);
            $form_val['half_logo'] = $request->file('half_logo')->store('logos', 'public');
        } else {
            $form_val['half_logo'] = $old_half_logo;
        }

        $old_favicon = isset($settings['favicon']) && !empty($settings['favicon']) ? $settings['favicon'] : '';
        if ($request->hasFile('favicon')) {
            Storage::disk('public')->delete($old_favicon);
            $form_val['favicon'] = $request->file('favicon')->store('logos', 'public');
        } else {
            $form_val['favicon'] = $old_favicon;
        }
        $data = [
            'variable' => 'general_settings',
            'value' => json_encode($form_val),
        ];

        if ($fetched_data == null) {
            Setting::create($data);
        } else {
            Setting::where('variable', 'general_settings')->update($data);
        }
        session()->put('date_format', $request->input('date_format'));

        Session::flash('message', 'Settings saved successfully.');
        return response()->json(['error' => false]);
    }

    public function store_pusher_settings(Request $request)
    {
        $request->validate([
            'pusher_app_id' => ['required'],
            'pusher_app_key' => ['required'],
            'pusher_app_secret' => ['required'],
            'pusher_app_cluster' => ['required']
        ]);
        $fetched_data = Setting::where('variable', 'pusher_settings')->first();
        $form_val = $request->except('_token', '_method', 'redirect_url');
        $data = [
            'variable' => 'pusher_settings',
            'value' => json_encode($form_val),
        ];

        if ($fetched_data == null) {
            Setting::create($data);
        } else {
            Setting::where('variable', 'pusher_settings')->update($data);
        }

        Session::flash('message', 'Settings saved successfully.');
        return response()->json(['error' => false]);
    }

    public function store_email_settings(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'smtp_host' => ['required'],
            'smtp_port' => ['required'],
            'email_content_type' => ['required'],
            'smtp_encryption' => ['required']
        ]);
        $fetched_data = Setting::where('variable', 'email_settings')->first();
        $form_val = $request->except('_token', '_method', 'redirect_url');
        $data = [
            'variable' => 'email_settings',
            'value' => json_encode($form_val),
        ];

        if ($fetched_data == null) {
            Setting::create($data);
        } else {
            Setting::where('variable', 'email_settings')->update($data);
        }
        Session::flash('message', 'Settings saved successfully.');
        return response()->json(['error' => false]);
    }

    public function store_media_storage_settings(Request $request)
    {
        $request->validate([
            'media_storage_type' => config('constants.ALLOW_MODIFICATION') === 0 ? 'required|in:local' : 'required|in:local,s3',
            's3_key' => $request->input('media_storage_type') === 's3' ? 'required' : 'nullable',
            's3_secret' => $request->input('media_storage_type') === 's3' ? 'required' : 'nullable',
            's3_region' => $request->input('media_storage_type') === 's3' ? 'required' : 'nullable',
            's3_bucket' => $request->input('media_storage_type') === 's3' ? 'required' : 'nullable',
        ]);
        $fetched_data = Setting::where('variable', 'media_storage_settings')->first();
        $form_val = $request->except('_token', '_method', 'redirect_url');
        $data = [
            'variable' => 'media_storage_settings',
            'value' => json_encode($form_val),
        ];

        if ($fetched_data == null) {
            Setting::create($data);
        } else {
            Setting::where('variable', 'media_storage_settings')->update($data);
        }
        Session::flash('message', 'Settings saved successfully.');
        return response()->json(['error' => false]);
    }

    public function store_sms_gateway_settings(Request $request)
    {
        $request->validate([
            'base_url' => 'required|string',
            'sms_gateway_method' => 'required|string|in:POST,GET',
            'header_key' => 'nullable|array',
            'header_value' => 'nullable|array',
            'body_key' => 'nullable|array',
            'body_value' => 'nullable|array',
            'params_key' => 'nullable|array',
            'params_value' => 'nullable|array',
            'text_format_data' => 'nullable|string',
        ]);

        // Prepare the data to store
        $data = [
            'base_url' => $request->base_url,
            'sms_gateway_method' => $request->sms_gateway_method,
            'header_data' => $request->header_key && $request->header_value ? array_combine($request->header_key, $request->header_value) : [],
            'body_formdata' => $request->body_key && $request->body_value ? array_combine($request->body_key, $request->body_value) : [],
            'params_data' => $request->params_key && $request->params_value ? array_combine($request->params_key, $request->params_value) : [],
            'text_format_data' => $request->text_format_data,
        ];

        // Convert data to JSON
        $jsonData = json_encode($data);

        // Check if the setting exists
        $existingSetting = Setting::where('variable', 'sms_gateway_settings')->first();

        if ($existingSetting) {
            // Update existing setting
            $existingSetting->update(['value' => $jsonData]);
        } else {
            // Create new setting
            Setting::create([
                'variable' => 'sms_gateway_settings',
                'value' => $jsonData,
            ]);
        }

        Session::flash('message', 'Settings saved successfully.');
        return response()->json(['error' => false]);
    }


    public function store_template(Request $request)
    {
        $formFields = $request->validate([
            'type' => 'required',
            'name' => 'required',
            'subject' => [
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('type') === 'email' && $request->input('status') === '1' && empty($value)) {
                        $fail('The subject field is required when status is active.');
                    }
                },
            ],
            'content' => [
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('status') === '1' && empty($value)) {
                        $fail('The message field is required when status is active.');
                    }
                },
            ],
            'status' => 'required',
        ], [
            'subject.required' => 'The subject field is required when status is active.',
            'content.required' => 'The message field is required when status is active.'
        ]);

        $type = $request->input('type');
        $name = $request->input('name');

        $fetched_data = Template::where('type', $type)
            ->where('name', $name)
            ->first();
        if ($fetched_data == null) {
            // When creating a new record, provide a default value for the status field
            Template::create($formFields);
        } else {
            // Use an array of conditions for the update query
            Template::where([
                ['type', '=', $type],
                ['name', '=', $name]
            ])->update($formFields);
        }

        // Session::flash('message', 'Template saved successfully.');
        return response()->json(['error' => false, 'message' => 'Saved successfully.']);
    }

    public function get_default_template(Request $request)
    {
        // Get the type and name from the request
        $type = $request->input('type');
        $name = $request->input('name');

        // Define the directory structure based on type and name
        switch ($type) {
            case 'email':
                $directory = 'views/mail/default_templates/';
                switch ($name) {
                    case 'account_creation':
                        $directory .= 'account_creation.blade.php';
                        // Include or return the file content based on $directory
                        break;
                    case 'verify_email':
                        $directory .= 'verify_email.blade.php';
                        // Include or return the file content based on $directory
                        break;
                    case 'forgot_password':
                        $directory .= 'forgot_password.blade.php';
                        // Include or return the file content based on $directory
                        break;
                    case 'project_assignment':
                        $directory .= 'project_assignment.blade.php';
                        // Include or return the file content based on $directory
                        break;
                    case 'task_assignment':
                        $directory .= 'task_assignment.blade.php';
                        // Include or return the file content based on $directory
                        break;
                    case 'workspace_assignment':
                        $directory .= 'workspace_assignment.blade.php';
                        // Include or return the file content based on $directory
                        break;
                    case 'meeting_assignment':
                        $directory .= 'meeting_assignment.blade.php';
                        // Include or return the file content based on $directory
                        break;
                    default:
                        return response()->json(['error' => true, 'message' => 'Unknown email template name.']);
                        break;
                }
                // Return or include the file based on the constructed $directory
                break;

            case 'sms':
                switch ($name) {
                    case 'project_assignment':
                        return response()->json(['error' => false, 'message' => 'Reset to default successfully.', 'content' => 'Hello, {FIRST_NAME} {LAST_NAME} You have been assigned a new project {PROJECT_TITLE}, ID:#{PROJECT_ID}.']);
                        break;
                    case 'task_assignment':
                        return response()->json(['error' => false, 'message' => 'Reset to default successfully.', 'content' => 'Hello, {FIRST_NAME} {LAST_NAME} You have been assigned a new task {TASK_TITLE}, ID:#{TASK_ID}.']);
                        break;
                    case 'workspace_assignment':
                        return response()->json(['error' => false, 'message' => 'Reset to default successfully.', 'content' => 'Hello, {FIRST_NAME} {LAST_NAME} You have been added in a new workspace {WORKSPACE_TITLE}, ID:#{WORKSPACE_ID}.']);
                        break;
                    case 'meeting_assignment':
                        return response()->json(['error' => false, 'message' => 'Reset to default successfully.', 'content' => 'Hello, {FIRST_NAME} {LAST_NAME} You have been added in a new meeting {MEETING_TITLE}, ID:#{MEETING_ID}.']);
                        break;
                    default:
                        return response()->json(['error' => true, 'message' => 'Unknown SMS template name.']);
                        break;
                }
                break;

            default:
                return response()->json(['error' => true, 'message' => 'Unknown template type.']);
                break;
        }


        // Construct the default template path
        $defaultTemplatePath = resource_path($directory);

        // Check if the default template file exists
        if (File::exists($defaultTemplatePath)) {
            // Read the content of the default template file
            $defaultTemplateContent = File::get($defaultTemplatePath);

            // Return the default template content as a response
            return response()->json(['error' => false, 'message' => 'Reset to default successfully.', 'content' => $defaultTemplateContent]);
        } else {
            // If the default template file does not exist, return an error response
            return response()->json(['error' => true, 'message' => 'Default template not found.']);
        }
    }
}
