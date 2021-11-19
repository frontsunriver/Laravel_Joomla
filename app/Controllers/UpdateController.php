<?php

    namespace App\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Media;
    use App\Models\Option;
    use App\Models\Taxonomy;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;

    class UpdateController extends Controller
    {
        private $versions = [
            'version_1_1',
            'version_1_2',
            'version_1_2_1',
            'version_1_2_2',
            'version_1_2_3',
            'version_1_3',
            'version_1_3_1',
            'version_1_3_2',
            'version_1_3_3',
            'version_1_3_5',
            'version_1_4',
            'version_1_4_3',
	        'version_1_4_5',
	        'version_1_5_1',
        ];
        private $messages = [];

        public function _clearCache(Request $request, $name = '', $redirect = '')
        {
            global $hh_rtl;
            $redirect = base64_decode($redirect);
            if (filter_var($redirect, FILTER_VALIDATE_URL) === FALSE) {
                $redirect = url('/');
            }

            $cache_path = public_path('caching');
            $types = ['h', 'f'];
            $extensions = ['js', 'css'];
            foreach ($types as $type) {
                foreach ($extensions as $extension) {
                    $prefix = $type . '_' . $name;
                    $subfix = $hh_rtl ? 'frontend_rtl' : 'frontend';
                    $file = $cache_path . '/' . $prefix . '_m_' . $subfix . '.' . $extension;

                    if (is_file($file)) {
                        File::delete($file);
                    }
                }
            }
            return redirect($redirect);
        }

        public function _systemTools(Request $request)
        {
            return view('system.tools');
        }

        public function _systemToolsPost(Request $request)
        {
            $action = $request->get('system_tool');
            $password = $request->get('password');
            $system_password = env('SYSTEM_PASSWORD');
            if (!empty($system_password) && $system_password === $password && !empty($action)) {
                switch ($action) {
                    case 'clear_cache':
                        Artisan::call('cache:clear');
                        $output = Artisan::output();
                        $this->messages[] = $output;

                        Artisan::call('view:clear');
                        $output = Artisan::output();
                        $this->messages[] = $output;

                        Artisan::call('route:clear');
                        $output = Artisan::output();
                        $this->messages[] = $output;

                        File::deleteDirectory(public_path('caching'));

                        $this->messages[] = __('Deleted scripts cache');

                        break;
                    case 'clear_view';
                        Artisan::call('view:clear');
                        $output = Artisan::output();

                        $this->messages[] = $output;
                        break;
                    case 'update_version':
                        foreach ($this->versions as $version) {
                            $updated = get_opt('awebooking_' . $version, false);
                            if (!$updated) {
                                $this->$version();
                                update_opt('awebooking_' . $version, 'updated');
                            } else {
                                $this->messages[] = sprintf(__('Has updated version %s'), $version);
                            }
                        }
                        break;
                    case 'symlink':
                        Artisan::call('extension:link');
                        Artisan::call('awe:link');
                        Artisan::call('storage:link');
                        $output = Artisan::output();

                        $this->messages[] = $output;

                        break;
                    case 'on_coming':
                        Artisan::call('up');
                        $output = Artisan::output();

                        $this->messages[] = $output;
                        break;
                    case 'off_coming':
                        Artisan::call('down');
                        $output = Artisan::output();

                        $this->messages[] = $output;
                        break;
                    default:
                        $this->messages[] = __('Can not access this action');
                }
            } else {
                $this->messages[] = __('Can not access this action. Please check the password.');
            }

            return $this->sendJson([
                'status' => 1,
                'message' => view('common.update', ['messages' => $this->messages])->render()
            ]);
        }
        public function version_1_5_1(){
            $this->_migrate();

            $this->messages[] = Artisan::output();
        }
        public function version_1_4_5(){
	        $fontFile = public_path('fonts/fonts.php');
	        $fontSystemFile = public_path('fonts/fonts-system.php');
	        @include $fontFile;
	        @include $fontSystemFile;

	        $fonts_merge = [];
	        if (isset($fonts_system)) {
		        $fonts_merge = $fonts_system;
	        }
	        if (isset($fonts)) {
		        $fonts_merge = array_merge($fonts_merge, $fonts);
	        }

	        $myfile = fopen($fontSystemFile, "w");
	        @ob_start();
	        var_export($fonts_merge);
	        $content = @ob_get_clean();
	        fwrite($myfile, '<?php $fonts_system = ' . $content . '; ?>');
	        fclose($myfile);

	        $fonts_none = [];
	        $myfile1 = fopen($fontFile, "w");
	        @ob_start();
	        var_export($fonts_none);
	        $content1 = @ob_get_clean();
	        fwrite($myfile1, '<?php $fonts = ' . $content1 . '; ?>');
	        fclose($myfile1);
        }

        public function version_1_4_3()
        {
            $this->_migrate();

            $this->messages[] = Artisan::output();
        }

        public function version_1_4()
        {

            $this->_migrate();

            $this->messages[] = Artisan::output();

            /*--- Update media path, url ---*/
            $all_media = DB::table('media')->get();
            if (is_object($all_media)) {
                $media_output = [];
                foreach ($all_media as $media) {
                    $media_url = $media->media_url;
                    $media_url = str_replace('\\storage\\', '/storage/', $media_url);
                    $media_url_split = explode('/storage/', $media_url);
                    if ($media_url_split !== FALSE && isset($media_url_split[1])) {
                        $media_url_join = '/storage/' . $media_url_split[1];
                        $media_output['media_url'][] = [
                            'media_id' => $media->media_id,
                            'media_url' => $media_url_join
                        ];
                    }

                    $media_path = $media->media_path;
                    $media_path = str_replace('storage\\app', 'storage/app', $media_path);
                    $media_path_split = explode('storage/app', $media_path);
                    if ($media_path_split !== FALSE && isset($media_path_split[1])) {
                        $media_path_join = '/storage/app' . $media_path_split[1];
                        $media_output['media_path'][] = [
                            'media_id' => $media->media_id,
                            'media_path' => $media_path_join
                        ];
                    }
                }
                if (!empty($media_output)) {
                    $mediaInstance = new Media();
                    $mediaInstance->timestamps = false;
                    foreach ($media_output as $key => $media) {
                        $index = 'media_id';
                        \Batch::update($mediaInstance, $media, $index);
                    }
                    $this->messages[] = __('Updated media link successfully');
                }
            }

            /*--- Update tax ---*/
            $option = new Option();
            $options = $option->getOption('hh_theme_options');
            $current_options = unserialize($options->option_value);

            if ($current_options['tax']) {
                $current_options['car_tax'] = $current_options['tax'];
                $current_options['home_tax'] = $current_options['tax'];
                $current_options['experience_tax'] = $current_options['tax'];

            }
            if ($current_options['included_tax']) {
                $current_options['included_car_tax'] = $current_options['included_tax'];
                $current_options['included_home_tax'] = $current_options['included_tax'];
                $current_options['included_experience_tax'] = $current_options['included_tax'];

            }
            $update_options = serialize($current_options);
            $option->updateOption('hh_theme_options', $update_options);

            $this->messages[] = __('Updated tax option successfully');

            /*---- Update term relation ----*/

            /*-- Home --*/
            DB::statement("UPDATE term_relation AS a
            INNER JOIN home AS b ON a.service_id = b.post_id
            AND a.term_id = b.home_type
            SET a.post_type = 'home'");

            $all_home = DB::table('home')->get()->all();
            if (!empty($all_home) && is_array($all_home)) {
                foreach ($all_home as $home) {
                    $amenities = $home->amenities;
                    if (!empty($amenities)) {
                        $amenities = explode(',', $amenities);
                        if (is_array($amenities)) {
                            foreach ($amenities as $amenity) {
                                DB::statement("UPDATE term_relation AS a
                                INNER JOIN home AS b ON a.service_id = b.post_id
                                AND a.term_id = '{$amenity}'
                                SET a.post_type = 'home'");
                            }
                        }
                    }
                }
            }

            /*-- Experience --*/
            DB::statement("UPDATE term_relation AS a
            INNER JOIN experience AS b ON a.service_id = b.post_id
            AND a.term_id = b.experience_type
            SET a.post_type = 'experience'");

            $all_experience = DB::table('experience')->get()->all();
            if (!empty($all_experience) && is_array($all_experience)) {
                foreach ($all_experience as $experience) {
                    $amenities = $experience->amenities;
                    if (!empty($amenities)) {
                        $amenities = explode(',', $amenities);
                        if (is_array($amenities)) {
                            foreach ($amenities as $amenity) {
                                DB::statement("UPDATE term_relation AS a
                                INNER JOIN experience AS b ON a.service_id = b.post_id
                                AND a.term_id = '{$amenity}'
                                SET a.post_type = 'experience'");
                            }
                        }
                    }
                    $inclusions = $experience->inclusions;
                    if (!empty($inclusions)) {
                        $inclusions = explode(',', $inclusions);
                        if (is_array($inclusions)) {
                            foreach ($inclusions as $amenity) {
                                DB::statement("UPDATE term_relation AS a
                                INNER JOIN experience AS b ON a.service_id = b.post_id
                                AND a.term_id = '{$amenity}'
                                SET a.post_type = 'experience'");
                            }
                        }
                    }
                    $exclusions = $experience->exclusions;
                    if (!empty($exclusions)) {
                        $exclusions = explode(',', $exclusions);
                        if (is_array($exclusions)) {
                            foreach ($exclusions as $amenity) {
                                DB::statement("UPDATE term_relation AS a
                                INNER JOIN experience AS b ON a.service_id = b.post_id
                                AND a.term_id = '{$amenity}'
                                SET a.post_type = 'experience'");
                            }
                        }
                    }
                }
            }

            /*-- Car --*/
            DB::statement("UPDATE term_relation AS a
            INNER JOIN car AS b ON a.service_id = b.post_id
            AND a.term_id = b.car_type
            SET a.post_type = 'car'");

            $all_car = DB::table('car')->get()->all();
            if (!empty($all_car) && is_array($all_car)) {
                foreach ($all_car as $car) {
                    $amenities = $car->features;
                    if (!empty($amenities)) {
                        $amenities = explode(',', $amenities);
                        if (is_array($amenities)) {
                            foreach ($amenities as $amenity) {
                                DB::statement("UPDATE term_relation AS a
                                INNER JOIN car AS b ON a.service_id = b.post_id
                                AND a.term_id = '{$amenity}'
                                SET a.post_type = 'car'");
                            }
                        }
                    }

                    $equipments = maybe_unserialize($car->equipments);
                    if (!empty($equipments) && is_array($equipments)) {
                        foreach ($equipments as $term_id => $equipment) {
                            DB::statement("UPDATE term_relation AS a
                                INNER JOIN car AS b ON a.service_id = b.post_id
                                AND a.term_id = '{$term_id}'
                                SET a.post_type = 'car'");
                        }
                    }

                }
            }
            $this->messages[] = __('Updated term relation successfully');
        }

        public function version_1_3_5()
        {
            $this->_migrate();

            $output = Artisan::output();

            $this->messages[] = $output;

            DB::table('language')->update([
                'rtl' => 'no'
            ]);

        }

        public function version_1_3_3()
        {
            $this->_migrate();

            $output = Artisan::output();

            $this->messages[] = $output;
        }

        public function version_1_3_2()
        {
            $this->_migrate();

            $output = Artisan::output();

            $this->messages[] = $output;
        }

        public function version_1_3_1()
        {
            $this->_migrate();

            $output = Artisan::output();

            $this->messages[] = $output;
        }

        public function version_1_3()
        {
            $this->_migrate();
            DB::table('home')->update([
                'enable_extra_guest' => 'off',
                'extra_guest_price' => 0,
                'apply_to_guest' => 1,
            ]);
            $output = Artisan::output();

            $this->messages[] = $output;
        }

        public function version_1_2_3()
        {
            $this->_migrate();
            DB::table('home')->update(['post_type' => 'home']);
            DB::table('experience')->update(['post_type' => 'experience']);
            DB::table('car')->update(['post_type' => 'car']);

            $output = Artisan::output();

            // Rename media folder by user id
            $media_path = storage_path('app/public');
            $folders = glob($media_path . '/*', GLOB_ONLYDIR);
            if (!empty($folders) && is_array($folders)) {
                foreach ($folders as $key => $folder) {
                    $folder_arr = explode(DIRECTORY_SEPARATOR, $folder);
                    $folder_name = end($folder_arr);
                    $user = get_user_by_email($folder_name);
                    if (is_object($user)) {
                        $user_id = $user->getUserId();
                        $media_object = new Media();
                        $media_by_user = $media_object->getByAuthor($user_id);
                        if ($media_by_user) {
                            foreach ($media_by_user as $media_item) {
                                $media_u = str_replace($user->email, 'u-' . $user_id, $media_item->media_url);
                                $media_p = str_replace($user->email, 'u-' . $user_id, $media_item->media_path);
                                $media_object->updateMedia([
                                    'media_url' => $media_u,
                                    'media_path' => $media_p,
                                ], $media_item->media_id);
                            }
                        }
                        rename($media_path . '/' . $folder_name, $media_path . '/u-' . $user_id);
                    }
                }
            }
            $this->messages[] = $output;
        }

        public function version_1_2_2()
        {
            $this->_migrate();

            DB::table('home')->update(['use_long_price' => 'off']);

            $output = Artisan::output();

            $this->messages[] = $output;
        }

        public function version_1_2_1()
        {
            $this->_migrate();

            $output = Artisan::output();

            $this->messages[] = $output;
        }

        public function version_1_2()
        {
            $this->_migrate();

            $output = Artisan::output();

            // Add new taxonomy for experiences
            DB::table('taxonomy')->whereRaw("taxonomy_name IN ('post-category', 'post-tag')")->update(['post_type' => 'post']);
            DB::table('taxonomy')->whereRaw("taxonomy_name IN ('home-type', 'home-amenity', 'home-facilities')")->update(['post_type' => 'home']);

            DB::table('taxonomy');
            $tax = new Taxonomy();
            $data = [
                [
                    'taxonomy_title' => 'Languages',
                    'taxonomy_name' => 'experience-languages',
                    'post_type' => 'experience',
                    'created_at' => time()
                ],
                [
                    'taxonomy_title' => 'Inclusions',
                    'taxonomy_name' => 'experience-inclusions',
                    'post_type' => 'experience',
                    'created_at' => time()
                ],
                [
                    'taxonomy_title' => 'Exclusions',
                    'taxonomy_name' => 'experience-exclusions',
                    'post_type' => 'experience',
                    'created_at' => time()
                ],
                [
                    'taxonomy_title' => 'Experience Types',
                    'taxonomy_name' => 'experience-type',
                    'post_type' => 'experience',
                    'created_at' => time()
                ],
                [
                    'taxonomy_title' => 'Car Types',
                    'taxonomy_name' => 'car-type',
                    'post_type' => 'car',
                    'created_at' => time()
                ],
                [
                    'taxonomy_title' => 'Car Equipments',
                    'taxonomy_name' => 'car-equipment',
                    'post_type' => 'car',
                    'created_at' => time()
                ],
                [
                    'taxonomy_title' => 'Car Features',
                    'taxonomy_name' => 'car-feature',
                    'post_type' => 'car',
                    'created_at' => time()
                ]
            ];

            foreach ($data as $args) {
                if (!$tax->getByName($args['taxonomy_name'])) {
                    $tax->create($args);
                }
            }

            // Update 'service_type' column for booking table
            DB::table('booking')->update(['service_type' => 'home']);

            $this->messages[] = $output;
        }

        public function version_1_1()
        {
            $this->_migrate();
            DB::table('home')->update(['booking_form' => 'instant']);

        }

        private function _migrate()
        {
            Artisan::call('migrate');
            $output = Artisan::output();
            $this->messages[] = $output;
        }


        public function _setIconSVG(Request $request)
        {
            global $hh_fonts;
            if (!$hh_fonts) {
                include_once public_path('fonts/fonts.php');
                if (isset($fonts)) {
                    $hh_fonts = $fonts;
                }
            }
            return $this->sendJson([
                'status' => 1,
                'icons' => $hh_fonts
            ]);
        }


        public function _getIconSVG(Request $request)
        {
            $name = $request->get('name', '');
            $color = $request->get('color', '');
            $width = $request->get('width', '');
            $height = $request->get('height', '');
            $stroke = $request->get('stroke', '');
            $stroke = ($stroke == 'true')? true: false;
            return $this->sendJson([
                'status' => 1,
                'icon' => get_icon($name, $color, $width, $height, $stroke, true)
            ]);
        }
    }
