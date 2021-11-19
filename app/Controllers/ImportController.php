<?php
/**
 * Created by PhpStorm.
 * Date: 1/7/2020
 * Time: 4:00 PM
 */

namespace App\Controllers;

use App\Http\Controllers\Controller;
use Madnest\Madzipper\Madzipper;
use Composer\Package\Archiver\ZipArchiver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Option;
use Illuminate\Support\Str;
use Sentinel;

class ImportController extends Controller
{
    private $old_url = [
        'https://data.awebooking.org',
        'http://data.awebooking.org',
        'https://awebooking.org',
        'http://awebooking.org'
    ];

    public function _adminImportFonts(Request $request)
    {
        if ($request->hasFile('fonts')) {
            $fontFile = public_path('fonts/fonts.php');
            $fontSystemFile = public_path('fonts/fonts-system.php');
            @include $fontFile;
            @include $fontSystemFile;

            if (!isset($fonts)) {
                $fonts = [];
            }

	        $fonts_merge = [];
	        if (isset($fonts)) {
		        $fonts_merge = $fonts;
	        }
	        if (isset($fonts_system)) {
		        $fonts_merge = array_merge($fonts_merge, $fonts_system);
	        }

            $start_count = count($fonts);
            $filenameWithExt = $request->file('fonts')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('fonts')->getClientOriginalExtension();

            if ($extension !== 'zip') {
                return $this->sendJson([
                    'status' => 0,
                    'message' => view('common.alert', ['type' => 'danger', 'message' => __('Please select .ZIP file format')])->render()
                ]);
            }

            $folderTime = 'zipfonts' . time();
            $publicPath = storage_path('app/' . $folderTime);
            $fileNameToStore = $filename . '.' . $extension;
            $request->file('fonts')->storeAs($folderTime, $fileNameToStore);
            $zipper = new Madzipper();
            $zipper->make($publicPath . '/' . $fileNameToStore)->extractTo($publicPath);
            $zipper->close();

            $files = glob_recursive($publicPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $tmp_name = basename($file);
                    $tmp = explode('.', $tmp_name);
                    $file_extension = end($tmp);
                    $fontFilePath = $file;
                    if ($file_extension == 'svg') {
                        $fontFileName = Str::slug($filename . '_' . $tmp[0]);
                        $fontFileName = str_replace('-', '_', $fontFileName);
                        if (!isset($fonts_merge[$fontFileName])) {
                            $content = file_get_contents($fontFilePath);
                            if (!empty($content)) {
                                preg_match_all('/<svg(.*)<\/svg>/s', $content, $icon);

                                $icon = $icon[0][0];
                                $test_icon = substr($icon, 0, strpos($icon, '>'));
                                if (strpos($test_icon, 'width') === false) {
                                    $icon = str_replace('<svg', '<svg width="24px"', $icon);
                                }
                                if (strpos($test_icon, 'height') === false) {
                                    $icon = str_replace('<svg', '<svg height="24px"', $icon);
                                }

                                if (strpos($icon, 'fill') === false) {
                                    $icon = str_replace('<g', '<g fill="#000000"', $icon);
                                }

                                $test_icon = substr($icon, 0, strpos($icon, '>'));

                                $test_icon = preg_replace('/width="[0-9.a-z]*"/', 'width="24px"', $test_icon);
                                $test_icon = preg_replace('/width=""/', 'width="24px"', $test_icon);
                                $test_icon = preg_replace('/height="[0-9.a-z]*"/', 'height="24px"', $test_icon);
                                $test_icon = preg_replace('/height=""/', 'height="24px"', $test_icon);
                                $icon = $test_icon . substr($icon, strpos($icon, '>'));

                                $icon = preg_replace('/<title>.*<\/title>/', '', $icon);

                                $icon = preg_replace('/(id="[a-zA-Z0-9-_]*")/', '', $icon);
                                $icon = str_replace('xmlns="http://www.w3.org/2000/svg"', '', $icon);
                                $icon = str_replace('xmlns:xlink="http://www.w3.org/1999/xlink"', '', $icon);

                                if (!isset($fonts_merge[$fontFileName])) {
                                    $fonts[$fontFileName] = $icon;
                                }
                            }
                        }
                    }
                }
            }
            $end_count = count($fonts);

            rmdir_recursive($publicPath);

            if ($end_count > $start_count) {
                $myfile = fopen($fontFile, "w");
                @ob_start();
                var_export($fonts);
                $content = @ob_get_clean();
                fwrite($myfile, '<?php $fonts = ' . $content . '; ?>');
                fclose($myfile);

                $number_uploaded = $end_count - $start_count;

                $message = _n("[0::%s icons has been upload successfully][1::%s icon has been upload successfully][2::%s icons has been upload successfully]", $number_uploaded);
                return $this->sendJson([
                    'status' => 1,
                    'message' => view('common.alert', ['type' => 'success', 'message' => $message])->render()
                ]);
            } else {
                return $this->sendJson([
                    'status' => 0,
                    'message' => view('common.alert', ['type' => 'danger', 'message' => __('No icons imported')])->render()
                ]);
            }
        }

        return $this->sendJson([
            'status' => 0,
            'message' => view('common.alert', ['type' => 'danger', 'message' => __('You need choose font file before importing')])->render()
        ]);
    }

    public function _getImportFontsScreen()
    {
        $folder = $this->getFolder();
        return view("dashboard.screens.{$folder}.import-fonts", ['bodyClass' => 'hh-dashboard']);
    }

    private function resetDataBeforeImport()
    {
        DB::statement("DELETE FROM post");
        DB::statement("DELETE FROM page");
        DB::statement("DELETE FROM home");
        DB::statement("DELETE FROM experience");
        DB::statement("DELETE FROM car");
        DB::statement("DELETE FROM `language`");
        DB::statement("DELETE FROM menu");
        DB::statement("DELETE FROM menu_structure");
        DB::statement("DELETE FROM media");
        DB::statement("DELETE FROM comments");
        DB::statement("DELETE FROM `options`");
        DB::statement("DELETE FROM term");
        DB::statement("DELETE FROM term_relation");
    }

    public function _adminImportData()
    {
        $step = request()->get('step');

        $next_step = $step + 1;
        if ($step == 0) {
            $password = request()->get('password');
            $system_password = env('SYSTEM_PASSWORD');
            if (empty($system_password) || $system_password !== $password) {
                echo json_encode([
                    'status' => false,
                    'message' => __('Your System Password is incorrect')
                ]);
                die;
            }
            $import_label = 'Post';
        } elseif ($step == 1) {
            $this->resetDataBeforeImport();
            $file_name = ['post'];
            $import_label = 'Post';
        } elseif ($step == 2) {
            $file_name = ['page'];
            $import_label = 'Page';
        } elseif ($step == 3) {
            $file_name = ['home'];
            $import_label = 'Home';
        } elseif ($step == 4) {
            $file_name = ['experience'];
            $import_label = 'Experience';
        } elseif ($step == 5) {
            $file_name = ['car'];
            $import_label = 'Car';
        } elseif ($step == 6) {
            $file_name = ['menu', 'menu_structure'];
            $import_label = 'Menu';
        } else {
            $file_name = [
            	'language',
                'media',
                'comments',
                'options',
                'term',
                'term_relation',
            ];
            $import_label = 'Settings';
        }

        if (!empty($file_name)) {
            foreach ($file_name as $file) {
                $sql = file_get_contents(public_path("installer/files/" . $file . ".sql"));
                if ($sql) {
                    if ($file == 'menu_structure') {
                        foreach ($this->old_url as $url) {
                            $sql = str_replace($url, url('/'), $sql);
                        }
                    }
                    $statements = array_filter(array_map('trim', explode('INSERT INTO', $sql)));
                    foreach ($statements as $stmt) {
                        if (!empty($stmt)) {
                            DB::insert("INSERT INTO " . $stmt);
                        }
                    }
                }
            }
        }

        if ($step == 7) {
            $next_step = 'final';
            $installedLogFile = storage_path('imported');
            $dateStamp = date("Y/m/d h:i:sa");
            if (!file_exists($installedLogFile)) {
                $message = sprintf(__('Your site has been imported at %s'), $dateStamp) . "\n";
                file_put_contents($installedLogFile, $message);
            } else {
                $message = sprintf(__('Your site has been imported at %s'), $dateStamp) . "\n";
                file_put_contents($installedLogFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
        }

        echo json_encode([
            'status' => true,
            'next_step' => $next_step,
            'label' => $import_label
        ]);
        die;
    }

    public function _runImport()
    {

        if (!file_exists(storage_path('imported'))) {
            $step = request()->get('step');

            $next_step = $step + 1;
            if ($step == 1) {
                $file_name = ['post'];
                $import_label = 'Post';
            } elseif ($step == 2) {
                $file_name = ['page'];
                $import_label = 'Page';
            } elseif ($step == 3) {
                $file_name = ['home'];
                $import_label = 'Home';
            } elseif ($step == 4) {
                $file_name = ['experience'];
                $import_label = 'Experience';
            } elseif ($step == 5) {
                $file_name = ['car'];
                $import_label = 'Car';
            } elseif ($step == 6) {
                $file_name = ['menu', 'menu_structure'];
                $import_label = 'Menu';
            } else {
                $file_name = [
                	'language',
                    'media',
                    'comments',
                    'options',
                    'term',
                    'term_relation',
                ];
                $import_label = 'Settings';
            }

            if (!empty($file_name)) {
                foreach ($file_name as $file) {
                    $sql = file_get_contents(public_path("installer/files/" . $file . ".sql"));
                    if ($sql) {
                        if ($file == 'menu_structure') {
                            foreach ($this->old_url as $url) {
                                $sql = str_replace($url, url('/'), $sql);
                            }
                        }
                        $statements = array_filter(array_map('trim', explode('INSERT INTO', $sql)));
                        foreach ($statements as $stmt) {
                            if (!empty($stmt)) {
                                DB::insert("INSERT INTO " . $stmt);
                            }
                        }
                    }
                }
            }

            if ($step == 7) {
                if (!empty(env('APP_NAME'))) {
                    $option = new Option();
                    $hasOption = $option->getOption(\ThemeOptions::getOptionID());
                    $optionValue = (!is_null($hasOption)) ? unserialize($hasOption->option_value) : [];

                    $optionValue['site_name'] = env('APP_NAME');

                    $optionValue = serialize($optionValue);
                    $option->updateOption(\ThemeOptions::getOptionID(), $optionValue);
                }
                $next_step = 'final';

                $installedLogFile = storage_path('imported');
                $dateStamp = date("Y/m/d h:i:sa");
                if (!file_exists($installedLogFile)) {
                    $message = 'Your site has been imported at ' . $dateStamp . "\n";
                    file_put_contents($installedLogFile, $message);
                } else {
                    $message = 'Your site has been imported at ' . $dateStamp . "\n";
                    file_put_contents($installedLogFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
                }
            }

            echo json_encode([
                'status' => true,
                'next_step' => $next_step,
                'label' => $import_label
            ]);
        } else {
            echo json_encode([
                'status' => true,
                'next_step' => 'imported',
                'label' => __('Your site has been imported')
            ]);
        }
    }

}
