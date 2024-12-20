<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    protected $user;
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    public function index()
    {
        $default_language = $this->user->lang;
        return view('settings.languages', compact('default_language'));
    }

    public function manage()
    {
        return view('languages.manage');
    }

    public function create()
    {

        return view('languages.create_language');
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => ['required'],
            'code' => ['required', 'unique:languages,code']

        ]);

        if (language::create($formFields)) {
            Session::flash('message', 'Language created successfully.');
            return response()->json(['error' => false]);
        } else {
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $languages = Language::orderBy($sort, $order);

        if ($search) {
            $languages = $languages->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $languages->count();
        $languages = $languages
            ->paginate(request("limit"))
            ->through(function ($language) {
                $name = $language->name;
                $primaryBadge = ($language->code == $this->user->lang) ? ' <span class="badge bg-primary">Primary</span>' : '';
                return [
                    'id' => $language->id,
                    'name' => $name . $primaryBadge,
                    'code' => $language->code,
                    'created_at' => format_date($language->created_at, true),
                    'updated_at' => format_date($language->updated_at, true),
                ];
            });

        return response()->json([
            "rows" => $languages->items(),
            "total" => $total,
        ]);
    }


    public function get($id)
    {
        $language = Language::findOrFail($id);
        return response()->json(['language' => $language]);
    }

    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => ['required'],
            'name' => ['required']
        ]);

        $language = language::findOrFail($request->id);

        if ($language->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Language updated successfully.']);
        } else {
            return response()->json(['error' => true, 'message' => 'Language couldn\'t updated.']);
        }
    }

    public function destroy($id)
    {
        // Retrieve the language record to get its code
        $language = Language::findOrFail($id);

        if ($language->code == app()->getLocale()) {
            return response()->json(['error' => true, 'message' => 'The current language cannot be deleted. Please switch to another one before delete.']);
        }

        // Construct the directory path for the language files
        $languageDirectoryPath = resource_path('lang/' . $language->code);

        // Check if the directory exists
        if (File::isDirectory($languageDirectoryPath)) {
            // Delete the directory and its content recursively
            File::deleteDirectory($languageDirectoryPath);
        }

        // Delete the language record from the database
        DeletionService::delete(Language::class, $id, 'Language');
        Session::flash('message', 'Language deleted successfully.');
        return response()->json(['error' => false]);
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:languages,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            // Retrieve the language record to get its code
            $language = Language::findOrFail($id);

            if ($language->code != app()->getLocale()) {
                // Construct the directory path for the language files
                $languageDirectoryPath = resource_path('lang/' . $language->code);

                // Check if the directory exists
                if (File::isDirectory($languageDirectoryPath)) {
                    // Delete the directory and its content recursively
                    File::deleteDirectory($languageDirectoryPath);
                }
                DeletionService::delete(Language::class, $id, 'Language');
            }
        }
        Session::flash('message', 'Language(s) deleted successfully.');
        return response()->json(['error' => false]);
    }

    public function save_labels(Request $request, Language $lang)
    {
        $data = $request->except(["_token", "_method"]);

        $langstr = '';

        foreach ($data as $key => $value) {
            $label_data =  strip_tags($value);
            $label_key = $key;
            $langstr .= "'" . $label_key . "' => '" . addslashes($label_data) . "'," . "\n";
        }
        $langstr_final = "<?php return [" . "\n\n\n" . $langstr . "];";

        $root = base_path("/resources/lang");
        $dir = $root . '/' . $request->langcode;

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = $dir . '/labels.php';

        file_put_contents($filename, $langstr_final);

        Session::flash('message', 'Language labels saved successfully.');
        return response()->json(['error' => false]);
    }

    public function change($code)
    {
        // app()->setLocale($code);
        session()->put('locale', $code);
        return redirect('/settings/languages');
    }

    public function switch($locale)
    {
        session(['my_locale' => $locale]);

        return redirect()->back()->with('message', 'Language switched successfully.');
    }

    public function set_default(Request $request)
    {
        $formFields = $request->validate([
            'lang' => ['required']

        ]);
        $locale = $request->lang;
        if (Language::where('code', '=', $locale)->exists()) {
            $this->user->lang = $locale;
            if ($this->user->save()) {
                session(['my_locale' => $locale, 'locale' => $locale]);
                Session::flash('message', 'Primary language set successfully.');
                return response()->json(['error' => false]);
            } else {
                return response()->json(['error' => true, 'message' => 'Primary language couldn\'t set.']);
            }
        } else {
            return response()->json(['error' => true, 'message' => 'Invalid language.']);
        }
    }
}
