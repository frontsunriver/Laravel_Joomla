<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Sentinel;

class TranslationController extends Controller
{
    private static $_inst;
    private static $_langs = [];

    public function __construct()
    {
        add_action('hh_dashboard_breadcrumb', [$this, '_addCreateLanguageButton']);
    }

    public function changeLanguageOrder(Request $request)
    {
        $data = request()->get('data');
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                Language::where('code', $key)->update([
                    'priority' => $val
                ]);
            }

            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Update successfully')
            ], true);
        }
        $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Update failed')
        ], true);
    }

    public function _addCreateLanguageButton()
    {
        $screen = current_screen();
        if ($screen == 'language') {
            echo view('dashboard.components.quick-add-language')->render();
        }
    }

    public function deleteLanguageItem(Request $request)
    {
        $language_id = request()->get('languageID');
        $language_encrypt = request()->get('languageEncrypt');

        if (!hh_compare_encrypt($language_id, $language_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This language is invalid')
            ], true);
        }
        $language = new Language();
        $languageObject = $language->getById($language_id);

        if (!empty($languageObject) && is_object($languageObject)) {
            $deleted = $language->deleteLanguage($language_id);
            if ($deleted) {
                $this->sendJson([
                    'status' => 1,
                    'title' => __(__('System Alert')),
                    'message' => __('This language is deleted'),
                    'reload' => true
                ], true);
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __(__('System Alert')),
                    'message' => __('Can not delete this language')
                ], true);
            }
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __(__('System Alert')),
                'message' => __('This language is invalid')
            ], true);
        }
    }

    public function changeLanguageStatus(Request $request)
    {
        $language_id = request()->get('languageID');
        $language_encrypt = request()->get('languageEncrypt');
        $status = request()->get('val', 'on');

        if (!hh_compare_encrypt($language_id, $language_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __(__('System Alert')),
                'message' => __('This Language is invalid')
            ], true);
        }

        $language = new Language();
        $data = [
            'status' => empty($status) ? 'off' : $status
        ];
        $updated = $language->updateLanguage($data, $language_id);

        if ($updated) {
            $this->sendJson([
                'status' => 1,
                'title' => __(__('System Alert')),
                'message' => __('Updated Successfully'),
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __(__('System Alert')),
                'message' => __('Can not update this language')
            ], true);
        }
    }

    public function updateLanguage(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'language' => 'required',
                'flag_name' => 'required',
                'name' => 'required'
            ],
            [
                'language.required' => __('Language is required'),
                'name.required' => __('Name is required'),
                'flag_name.required' => __('Flag is required')
            ]
        );
        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => view('common.alert', ['type' => 'danger', 'message' => $validator->errors()->first()])->render()
            ]);
        }

        $lang_code = request()->get('language');
        $flag_name = request()->get('flag_name');
        $flag_code = request()->get('flag_code');
        $name = request()->get('name');
        $status = request()->get('status', 'off');
        $rtl = request()->get('rtl', 'no');

        $langs = config('locales.languages');

        $isEdit = false;

        if (!empty($langs) && isset($langs[$lang_code])) {
            $langModel = new Language();

            $action = request()->get('action', '');
            if ($action == 'edit') {
                $id = request()->get('id', '');
                $check_exists = $langModel::where('id', $id)->first();
                if (!is_null($check_exists)) {
                    $isEdit = true;
                    $check_exists_update = $langModel::where('code', $lang_code)->where('id', '<>', $id)->first();
                    if ($check_exists_update) {
                        return $this->sendJson([
                            'status' => 0,
                            'message' => view('common.alert', [
                                'type' => 'danger',
                                'message' => __('This language already exists')
                            ])->render()
                        ]);
                    }

                    $res = $langModel->where('id', $id)->update([
                        'code' => $lang_code,
                        'name' => $name,
                        'flag_name' => $flag_name,
                        'flag_code' => $flag_code,
                        'status' => $status,
                        'rtl' => $rtl,
                    ]);

                    if ($res) {
                        return $this->sendJson([
                            'status' => 1,
                            'reload' => true,
                            'message' => view('common.alert', [
                                'type' => 'success',
                                'message' => __('Update language successfully')
                            ])->render()
                        ]);
                    } else {
                        return $this->sendJson([
                            'status' => 0,
                            'message' => view('common.alert', [
                                'type' => 'false',
                                'message' => __('Can not edit this language')
                            ])->render()
                        ]);
                    }

                } else {
                    return $this->sendJson([
                        'status' => 0,
                        'message' => view('common.alert', [
                            'type' => 'false',
                            'message' => __('Can not edit this language')
                        ])->render()
                    ]);
                }
            }

            $check_exists = $langModel::where('code', $lang_code)->first();
            if ($check_exists) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => view('common.alert', [
                        'type' => 'danger',
                        'message' => __('This language already exists')
                    ])->render()
                ]);
            }

            $langModel->code = $lang_code;
            $langModel->name = $name;
            $langModel->flag_name = $flag_name;
            $langModel->flag_code = $flag_code;
            $langModel->status = $status;
            $langModel->rtl = $rtl;
            $res = $langModel->save();

            if ($res) {
                return $this->sendJson([
                    'status' => 1,
                    'reload' => true,
                    'message' => view('common.alert', [
                        'type' => 'success',
                        'message' => __('Create new language successfully')
                    ])->render()
                ]);
            }
        }

        return $this->sendJson([
            'status' => 0,
            'message' => view('common.alert', [
                'type' => 'danger',
                'message' => !$isEdit ? __('Can not create this language') : __('Can not update this language')
            ])->render()
        ]);
    }

    public function language(Request $request, $page = 1)
    {
        $folder = $this->getFolder();
        $langModel = new Language();

        $isEdit = false;
        $currentLang = [];

        $action = request()->get('action', '');
        if ($action == 'edit') {
            $id = request()->get('id', '');
            $check_exists = $langModel::where('id', $id)->first();
            if ($check_exists) {
                $isEdit = true;
                $currentLang = $check_exists;
            } else {
                return response()->redirectToRoute('language');
            }
        }
        $search = request()->get('_s');
        $orderBy = request()->get('orderby', 'priority');
        $order = request()->get('order', 'asc');
        $status = request()->get('status', '');
        $data = [
            'search' => $search,
            'page' => $page,
            'orderby' => $orderBy,
            'order' => $order,
            'status' => $status
        ];

        $allLanguages = $langModel->getAllLanguages($data);

        $countries_data = file_get_contents(public_path('vendor/countries/countries.json'));
        $countries_data = json_decode($countries_data, true);

        return view("dashboard.screens.{$folder}.language", [
            'bodyClass' => 'hh-dashboard',
            'allLanguages' => $allLanguages,
            'countryData' => $countries_data,
            'isEdit' => $isEdit,
            'currentLang' => $currentLang]);
    }

    public function scanTranslate(Request $request)
    {
        $strings = [];
        $strings = $this->scanAllTranslateText($strings);

        if (!empty($strings)) {
            $strings = implode("\r\n", $strings);
            $lang_files = resource_path("lang/awe.lang");
            $inserted = file_put_contents($lang_files, $strings);

            $request_lang = request()->get('lang');
            $url = dashboard_url('translation');
            if (!empty($request_lang) && $request_lang != 'none') {
                $url = add_query_arg('lang', $request_lang, $url);
            }

            if ($inserted) {
                return $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => 'Scan successfully',
                    'redirect' => $url
                ]);
            } else {
                return $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Scan failed')
                ]);
            }
        }
        return $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('No text translation')
        ]);
    }

    private function scanAllTranslateText($strings)
    {
        $folders = [
            app_path(),
        ];
        foreach ($folders as $folder) {
            $view_path = $folder;
            $files = $this->scanAllFiles($view_path);
            if (!empty($files)) {
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        $content = file_get_contents($file);
                        preg_match_all("/_n\( \"(.*)\",|_n\(\"(.*)\",|_n\(\"(.*)\"\)|_n\( \"(.*)\" \)|_n\( '(.*)' \)|_n\('(.*)'\)|__\('(.*)'\)|__\( '(.*)' \)|__\('(.*)',|__\(\"(.*)\",|__\(\"(.*)\"\)|trans\('(.*)'\)|trans\(\"(.*)\"\)|trans_choice\('(.*)'\)|trans_choice\(\"(.*)\"\)|awe_lang\('(.*)'\)|awe_lang\(\"(.*)\"\)/U",
                            $content,
                            $output, PREG_PATTERN_ORDER);
                        for ($i = 1; $i <= 11; $i++) {
                            if (isset($output[$i])) {
                                foreach ($output[$i] as $k => $v) {
                                    if (!empty(trim($v))) {
                                        if (!in_array(trim($v), $strings)) {
                                            array_push($strings, trim($v));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $strings;
    }

    private function scanAllFiles($dir)
    {
        $root = scandir($dir);
        $result = [];
        foreach ($root as $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }
            if (is_file("$dir/$value")) {
                $result[] = "$dir/$value";
                continue;
            }
            foreach ($this->scanAllFiles("$dir/$value") as $value1) {
                $result[] = $value1;
            }
        }
        return $result;
    }

    public function translateString(Request $request)
    {
    	$fields = request()->get('fields', '');
    	if(empty($fields)){
		    return $this->sendJson([
			    'status' => 0,
			    'title' => __('System Alert'),
			    'message' => __('Translate failed')
		    ]);
	    }

	    $fields = json_decode($fields, true);

    	if(isset($fields[0])) {
		    $lang = $fields[0]['value'];
		    unset($fields[0]);
	    }
	    if(empty($lang))
		    $lang = 'none';

        $langs = config('locales.languages');

        if ($lang == 'none' || !isset($langs[$lang])) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Please choose language before translating')
            ]);
        }

	    $translate = [];
        if(!empty($fields)){
        	foreach ($fields as $item){
        		$key = $item['name'];
        		$spos = strpos($key, '_');
        		if($spos) {
			        $key = substr( $key, 0, $spos );
		        }
		        $key = base64_decode($key);
		        $translate[$key] = $item['value'];
	        }
        }

        if (empty($translate) || !is_array($translate)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Translate failed')
            ]);
        }

        $json_content = json_encode($translate, JSON_PRETTY_PRINT);
        $lang_files = resource_path("lang/" . $lang . ".json");
        $inserted = file_put_contents($lang_files, $json_content);
        if ($inserted) {
            return $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Translated successfully')
            ]);
        } else {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Translate failed')
            ]);
        }
    }

    public function index(Request $request)
    {
        $folder = $this->getFolder();

        $langs = config('locales.languages');

        $lang = request()->get('lang', 'none');
        $site_language = get_option('site_language', 'none');
        if ($site_language != 'none' && $lang == 'none') {
            $lang = $site_language;
        }

        $strings = $this->getContentTranslateFile("lang/awe.lang");
        if ($lang != 'none' && isset($langs[$lang])) {
            $trans = $this->getContentTranslateFile("lang/" . $lang . ".json", true);
        } else {
            $trans = [];
        }

        return view("dashboard.screens.{$folder}.translation", ['bodyClass' => 'hh-dashboard', 'strings' => $strings, 'translation' => $trans, 'langs' => $langs]);
    }

    private function getContentTranslateFile($file_path, $json = false)
    {
        $awe_files = resource_path($file_path);
        $strings = [];
        if (file_exists($awe_files)) {
            $file_content = trim(file_get_contents($awe_files));
            if (!empty($file_content)) {
                if ($json) {
                    $strings = json_decode($file_content, true);
                } else {
                    $hasRN = strpos($file_content, "\r\n");
                    if($hasRN) {
                        $strings = explode("\r\n", $file_content);
                    }else{
                        $strings = explode("\n", $file_content);
                    }
                }
            }
        }
        return $strings;
    }

    public static function get_inst()
    {
        if (empty(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }
}
