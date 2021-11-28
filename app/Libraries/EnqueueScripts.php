<?php
global $hh_lazyload;

class EnqueueScripts
{
    private static $styles = [];
    private static $scripts = [];

    private static $enqueuedStyles = [];
    private static $enqueuedScripts = [];

    public function __construct()
    {
        add_action('init', [$this, '_registerScripts']);

        add_action('header', [$this, '_renderParams']);

        add_action('init_frontend_header', [$this, '_enqueueHeader']);
        add_action('init_dashboard_header', [$this, '_enqueueHeaderDashboard']);
        add_action('init_frontend_footer', [$this, '_enqueueFooter']);
        add_action('init_dashboard_footer', [$this, '_enqueueFooterDashboard']);

        add_action('init_frontend_header', [$this, '_customCSS'], 20);
        add_action('init_dashboard_header', [$this, '_customCSS'], 20);

        add_action('init_frontend_header', [$this, '_datePickerLanguage'], 20);
        add_action('init_dashboard_header', [$this, '_datePickerLanguage'], 20);

        add_action('init_frontend_header', [$this, '_customHeaderCode'], 20);

        add_action('init_frontend_footer', [$this, '_customFooterCode'], 20);

        add_action('hh_updated_option', [$this, '_applySSL']);
        add_action('hh_updated_option', [$this, '_setTimeZone']);
        add_action('hh_updated_option', [$this, '_setCronJobConfig']);
    }

    public function _renderParams()
    {
        $hh_params = [
            'locale' => str_replace('_', '-', app()->getLocale()),
            'language_code' => strtolower(str_replace('_', '-', app()->getLocale())),
            'timezone' => e(get_timezone()),
            // 'mapbox_token' => e(get_option('mapbox_key')),
            'mapbox_token' => "pk.eyJ1IjoibmV5bWFyMjE1IiwiYSI6ImNrd2o1dXNtYTFmMWczM25vYzkzY3JkNmYifQ.TKhPlTMgIRXjLLrnkcrynQ",
            'currency' => current_currency(),
            'media' => [
                'get_all_media_url' => e(dashboard_url('all-media')),
                'get_inline_media_url' => e(dashboard_url('get-inline-media')),
                'add_media_url' => e(dashboard_url('add-media')),
                'media_modal_search_url' => e(dashboard_url('search-media-modal')),
                'media_modal_number_item' => 50,
                'media_upload_permission' => get_media_upload_permission(),
                'media_upload_size' => get_media_upload_size(),
                'media_upload_message' => get_media_upload_message(),
            ],
            'facebook_login' => e(get_option('facebook_login', 'off')),
            'facebook_api' => e(get_option('facebook_api')),
            'use_google_captcha' => e(get_option('use_google_captcha', 'off')),
            'google_captcha_key' => e(get_option('google_captcha_site_key')),
            'gdpr' => [
                'enable' => e(get_option('enable_gdpr', 'off')),
                'page' => e(get_the_permalink(get_option('gdpr_page', ''), '', 'page')),
                'i18n' => [
                    'description' => __('We use cookies to offer you a better browsing experience, personalise content and ads, to provide social media features and to analyse our traffic. Read about how we use cookies and how you can control them by clicking Cookie Settings. You consent to our cookies if you continue to use this website.'),
                    'settings' => __('Cookie settings'),
                    'accept' => __('Accept cookies'),
                    'statement' => __('Our cookie statement'),
                    'save' => __('Save settings'),
                    'always_on' => __('Always on'),
                    'cookie_essential_title' => __('Essential website cookies'),
                    'cookie_essential_desc' => __('Necessary cookies help make a website usable by enabling basic functions like page navigation and access to secure areas of the website. The website cannot function properly without these cookies.'),
                    'cookie_performance_title' => __('Performance cookies'),
                    'cookie_performance_desc' => __('These cookies are used to enhance the performance and functionality of our websites but are non-essential to their use. For example it stores your preferred language or the region that you are in.'),
                    'cookie_analytics_title' => __('Analytics cookies'),
                    'cookie_analytics_desc' => __('We use analytics cookies to help us measure how users interact with website content, which helps us customize our websites and application for you in order to enhance your experience.'),
                    'cookie_marketing_title' => __('Marketing cookies'),
                    'cookie_marketing_desc' => __('These cookies are used to make advertising messages more relevant to you and your interests. The intention is to display ads that are relevant and engaging for the individual user and thereby more valuable for publishers and third party advertisers.')
                ],
            ],
            'lazy_load' => get_option('enable_lazyload', 'off'),
            'get_icon_url' => url('get-icon'),
            'set_icon_url' => url('set-icon'),
            'rtl' => is_rtl(),
            'enable_seo' => get_option('enable_seo', 'off'),
            'ckeditor' => [
                'button_image_ckeditor' => '<button class="btn btn-add-image-editor" data-url="' . dashboard_url('all-media') . '"><i class="hh-icon ti-image"></i>' . __('Add Image') . '</button>'
            ]
        ];
        ?>
        <script>
            var hh_params = <?php echo json_encode($hh_params); ?>
        </script>
        <?php
    }

    public function _setCronJobConfig($option_Value)
    {
        $option_Value = unserialize($option_Value);
        if (isset($option_Value['payout_date']) && isset($option_Value['payout_time'])) {
            setEnvironmentValue([
                'PAYOUT_DATE' => $option_Value['payout_date'],
                'PAYOUT_TIME' => $option_Value['payout_time']
            ]);
        }
        if (isset($option_Value['ical_time_type']) && isset($option_Value['ical_hour']) && isset($option_Value['ical_minute'])) {
            setEnvironmentValue([
                'ICAL_TYPE' => $option_Value['ical_time_type'],
                'ICAL_HOUR' => $option_Value['ical_hour'],
                'ICAL_MINUTE' => $option_Value['ical_minute'],
            ]);
        }
    }

    public function _datePickerLanguage()
    {
        $datepicker_language = [
            'direction' => is_rtl() ? 'rtl' : 'ltr',
            'applyLabel' => __('Apply'),
            'cancelLabel' => __('Cancel'),
            'fromLabel' => __('From'),
            'toLabel' => __('To'),
            'customRangeLabel' => __('Custom'),
            'daysOfWeek' => [__('Sun'), __('Mo'), __('Tu'), __('We'), __('Th'), __('Fr'), __('Sa')],
            'monthNames' => [__('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December')],
            'firstDay' => 1,
            'today' => __('Today'),
        ];
        echo '<script> var locale_daterangepicker = ' . json_encode($datepicker_language) . '</script>';
    }

    public function _customFooterCode()
    {
        $code = get_option('custom_footer_code');
        if (!empty($code)) {
            echo esc_text($code);
        }
    }

    public function _customHeaderCode()
    {
        $code = get_option('custom_header_code');
        if (!empty($code)) {
            echo esc_text($code);
        }
    }

    public function _customCSS()
    {
        $main_color = get_option('main_color', '#f8546d');
        if (!empty($main_color)) {
            echo "<style>
                :root {
                  --pink: {$main_color};
                  --black: #2a2a2a;
                  --blue: #1abc9c;
                  --white: #FFFFFF;
                }
            </style>";
        }
        $google_font = get_option('google_font');
        if (!empty($google_font)) {
            $google_font = explode(';', $google_font);
            if (count($google_font) == 3 && !empty($google_font[0])) {
                $font_name = ucwords(str_replace('-', ' ', $google_font[0]));
                $font_weight = $google_font[1];
                $font_lang = $google_font[2];

                $url = 'https://fonts.googleapis.com/css?family=' . $font_name;
                if (!empty($font_weight)) {
                    $url .= ':' . $font_weight;
                }
                if (!empty($font_lang)) {
                    $url .= ':' . $font_lang;
                }
                echo '<link href="' . e($url) . '" rel="stylesheet">';
                echo "<style>
                    body{
                        font-family: '{$font_name}', sans-serif;
                    }
                    .awe-booking h1, .awe-booking h2, .awe-booking h3, .awe-booking h4, .awe-booking h5, .awe-booking h6{
                        font-family: '{$font_name}', sans-serif;
                    }
                </style>";
            }
        }
        $css = get_option('custom_css');
        $css = base64_decode($css);
        if (!empty($css)) {
            echo '<style>' . balanceTags($css) . '</style>';
        }
    }

    public function _applySSL($option_Value)
    {
        $option_Value = unserialize($option_Value);
        if (isset($option_Value['use_ssl'])) {
            if ($option_Value['use_ssl'] == 'on') {
                updateEnv('APP_ENV', 'production_ssl');
            } else {
                updateEnv('APP_ENV', 'local');
            }
        }
    }

    public function _setTimeZone($option_Value)
    {
        $option_Value = maybe_unserialize($option_Value);
        if (isset($option_Value['timezone'])) {
            if (!empty($option_Value['timezone'])) {
                updateConfig('timezone', $option_Value['timezone']);
            } else {
                updateConfig('timezone', 'UTC');
            }
        }
    }

    public function _registerScripts()
    {
        $this->addScript('vendor-js', asset('js/vendor.min.js'), false, true);

        $this->addStyle('vendor-css', asset('css/vendor.min.css'), true, true);

        $this->addScript('global-js', asset('js/global.js'), false, true);

        $lazy_load = get_option('enable_lazyload', 'off');

        if ($lazy_load == 'on') {
            $this->addScript('lazy-js', asset('vendor/lazy/jquery.lazyscrollloading.js'), false, true, 'frontend');
        }
        if (is_rtl()) {
            $this->addStyle('app-css', asset('vendor/app-rtl.min.css'), true, true);
        } else {
            $this->addStyle('app-css', asset('vendor/app.min.css'), true, true);
        }
        $this->addStyle('main-css', asset('css/main.min.css'), true, true);
        $this->addStyle('frontend-css', asset('css/frontend.min.css'), true, true, 'frontend');
        if (is_rtl()) {
            $this->addStyle('frontend-rtl', asset('css/frontend-rtl.min.css'), true, true, 'frontend');
        }
        $this->addStyle('option-css', asset('css/option.min.css'), true, true, 'dashboard');
        if (is_rtl()) {
            $this->addStyle('option-rtl', asset('css/option-rtl.min.css'), true, true, 'dashboard');
        }
        $this->addStyle('dashboard-css', asset('css/dashboard.min.css'), true, true, 'dashboard');
        if (is_rtl()) {
            $this->addStyle('dashboard-rtl', asset('css/dashboard-rtl.min.css'), true, true, 'dashboard');
        }

        $this->addScript('image-loaded-js', asset('vendor/imagesloaded.pkgd.js'), false, true);
        $this->addScript('jquery-ui-js', asset('vendor/jquery-ui/jquery-ui.js'), false, true);
        $this->addScript('bootstrap-validate-js', asset('vendor/bootstrap-validate.js'), false, true);
        $this->addScript('toast-js', asset('vendor/jquery-toast/jquery.toast.js'), false, true);
        $this->addScript('bootstrap-maxlength-js', asset('vendor/bootstrap-maxlength/bootstrap-maxlength.js'), false, true);

        if (get_option('use_google_captcha') == 'on') {
            $this->addScript('google-captcha', 'https://www.google.com/recaptcha/api.js?render=' . get_option('google_captcha_site_key'), false, true, '', true);
        }

        $this->addScript('nested-sort-js', asset('vendor/jquery.mjs.nestedSortable.js'), false, true);

        $this->addScript('nice-select-js', asset('vendor/jquery-nice-select/jquery.nice-select.js'));
        $this->addStyle('nice-select-css', asset('vendor/jquery-nice-select/nice-select.css'));

        $this->addScript('select2-js', asset('vendor/select2/select2.js'));
        $this->addStyle('select2-css', asset('vendor/select2/select2.css'));

        $this->addScript('magnific-popup-js', asset('vendor/magnific-popup/magnific-popup.js'));
        $this->addStyle('magnific-popup-css', asset('vendor/magnific-popup/magnific-popup.css'));

        $this->addScript('switchery-js', asset('vendor/switchery/switchery.js'));
        $this->addStyle('switchery-css', asset('vendor/switchery/switchery.css'));

        $this->addScript('flatpickr-js', asset('vendor/flatpickr/flatpickr.js'));
        $this->addStyle('flatpickr-css', asset('vendor/flatpickr/flatpickr.css'));

        $this->addScript('bootstrap-colorpicker-js', asset('vendor/bootstrap-colorpicker/bootstrap-colorpicker.js'));
        $this->addStyle('bootstrap-colorpicker-css', asset('vendor/bootstrap-colorpicker/bootstrap-colorpicker.css'));

        $this->addScript('mapbox-gl-js', asset('vendor/mapbox/mapbox-gl.js'));
        $this->addScript('mapbox-gl-geocoder-js', asset('vendor/mapbox/mapbox-gl-geocoder.js'));
        $this->addStyle('mapbox-gl-css', asset('vendor/mapbox/mapbox-gl.css'));
        $this->addStyle('mapbox-gl-geocoder-css', asset('vendor/mapbox/mapbox-gl-geocoder.css'));

        $this->addScript('dropzone-js', asset('vendor/dropzone/dropzone.min.js'));
        $this->addStyle('dropzone-css', asset('vendor/dropzone/dropzone.min.css'));

        $this->addScript('countdown-js', asset('countdown.min.js'));

        $this->addScript('accounting-js', asset('vendor/accounting.min.js'));

        $enable_gdpr = get_option('enable_gdpr', 'off');
        if ($enable_gdpr == 'on') {
            $this->addScript('gdpr-js', asset('vendor/gdpr/gdpr.js'), false, true, 'frontend');
            $this->addStyle('gdpr-css', asset('vendor/gdpr/gdpr.css'), true, true, 'frontend');
        }

        $this->addScript('datatables-js', asset('vendor/datatables/datatable.js'));
        $this->addScript('pdfmake-js', asset('vendor/pdfmake/pdfmake.js'));
        $this->addScript('vfs-fonts-js', asset('vendor/pdfmake/vfs_fonts.js'));
        $this->addStyle('datatables-css', asset('vendor/datatables/datatable.css'));

        $this->addScript('tinymce-js', asset('vendor/tinymce/tinymce.min.js'));

        $this->addScript('ck-editor-js', asset('vendor/ckeditor/ckeditor.js'));

        $this->addScript('confirm-js', asset('vendor/confirm/jquery-confirm.js'));
        $this->addStyle('confirm-css', asset('vendor/confirm/jquery-confirm.css'));

        $this->addScript('light-gallery-js', asset('vendor/lightGallery/js/lightgallery.js'));
        $this->addStyle('light-gallery-css', asset('vendor/lightGallery/css/lightgallery.css'));

        $this->addScript('daterangepicker-js', asset('vendor/daterangepicker/daterangepicker.js'));
        $this->addStyle('daterangepicker-css', asset('vendor/daterangepicker/daterangepicker.css'));

        $this->addStyle('home-slider', asset('vendor/slider/css/style.css'));
        $this->addScript('home-slider', asset('vendor/slider/js/slider.js'));

        $this->addStyle('iconrange-slider', asset('vendor/ion-rangeslider/ion.rangeSlider.css'));
        $this->addScript('iconrange-slider', asset('vendor/ion-rangeslider/ion.rangeSlider.js'));

        $this->addScript('sticky-js', asset('vendor/sticky-menu/jquery.sticky.js'));

        $this->addStyle('range-slider', asset('vendor/rangeslider/rangeslider.css'));
        $this->addScript('range-slider', asset('vendor/rangeslider/rangeslider.js'));

        $this->addScript('flot', asset('vendor/flot-charts/jquery.flot.js'));
        $this->addScript('flot-time', asset('vendor/flot-charts/jquery.flot.time.js'));
        $this->addScript('flot-tooltip', asset('vendor/flot-charts/jquery.flot.tooltip.min.js'));
        $this->addScript('flot-crosshair', asset('vendor/flot-charts/jquery.flot.crosshair.js'));
        $this->addScript('flot-selection', asset('vendor/flot-charts/jquery.flot.selection.js'));

        $this->addScript('nicescroll-js', asset('vendor/jquery.nicescroll.js'));
        $this->addScript('scroll-magic-js', asset('vendor/scroll-magic.js'));

        $this->addScript('ace-js', asset('vendor/ace/ace.js'));

        $this->addScript('owl-carousel', asset('vendor/owl-carousel/owl.carousel.min.js'));
        $this->addStyle('owl-carousel', asset('vendor/owl-carousel/assets/owl.carousel.min.css'));
        $this->addStyle('owl-carousel-theme', asset('vendor/owl-carousel/assets/owl.theme.default.min.css'));

        $this->addScript('context-menu-pos', asset('vendor/jquery-contextmenu/jquery.ui.position.min.js'));
        $this->addScript('context-menu', asset('vendor/jquery-contextmenu/jquery.contextMenu.min.js'));
        $this->addStyle('context-menu', asset('vendor/jquery-contextmenu/jquery.contextMenu.min.css'));

        $this->addScript('search-home-js', asset('js/search/home.js'));
        $this->addScript('search-car-js', asset('js/search/car.js'));
        $this->addScript('search-experience-js', asset('js/search/experience.js'));

        // customize css and js
        $this->addStyle('image-gallery-css', asset('css/image-gallery.css'));

        do_action('hh_register_scripts', $this);

        if (file_exists(app_path('awe-custom/Assets/css/awe-custom.css')) && is_dir(public_path('awe-custom'))) {
            $this->addStyle('awe-custom', asset('awe-custom/Assets/css/awe-custom.css'), false, true);
        }

        if (file_exists(app_path('awe-custom/Assets/js/awe-custom.js')) && is_dir(public_path('awe-custom'))) {
            $this->addScript('awe-custom-js', asset('awe-custom/Assets/js/awe-custom.js'), false, true);
        }
    }

    public function _enqueueHeader()
    {
        $enable_lazyload = get_option('enable_lazyload', 'off');
        global $hh_lazyload;
        $hh_lazyload = $enable_lazyload;

        $enable_optimize = get_option('optimize_site', 'off');
        $current_route = \Illuminate\Support\Facades\Route::current();

        if ($enable_optimize == 'on' && is_object($current_route)) {
            HHMinify::get_inst()->renderCSS(true, 'frontend');
            HHMinify::get_inst()->renderJS(true, 'frontend');
        } else {
            $this->styleRender(true, 'frontend');
            $this->scriptRender(true, 'frontend');
        }
    }

    public function _enqueueHeaderDashboard()
    {
        $this->styleRender(true, 'dashboard');
        $this->scriptRender(true, 'dashboard');
    }

    public function _enqueueFooter()
    {
        $current_route = \Illuminate\Support\Facades\Route::current();
        $enable_optimize = get_option('optimize_site', 'off');
        if ($enable_optimize == 'on' && is_object($current_route)) {
            HHMinify::get_inst()->renderCSS(false, 'frontend');
            HHMinify::get_inst()->renderJS(false, 'frontend');
        } else {
            $this->styleRender(false, 'frontend');
            $this->scriptRender(false, 'frontend');
        }
    }

    public function _enqueueFooterDashboard()
    {
        $this->styleRender(false, 'dashboard');
        $this->scriptRender(false, 'dashboard');
    }

    public function addStyle($name, $url, $in_header = false, $queue = false, $type = '', $external = false)
    {
        if (!isset(self::$styles[$name])) {
            self::$styles[$name] = [
                'name' => $name,
                'url' => $url,
                'queue' => $queue,
                'header' => $in_header,
                'type' => $type,
                'external' => $external
            ];
        }
    }

    public function addScript($name, $url, $in_header = false, $queue = false, $type = '', $external = false)
    {
        if (!isset(self::$scripts[$name])) {
            self::$scripts[$name] = [
                'name' => $name,
                'url' => $url,
                'queue' => $queue,
                'header' => $in_header,
                'type' => $type,
                'external' => $external
            ];
        }
    }

    public function enqueueStyles()
    {
        foreach (self::$styles as $name => $style) {
            $this->_enqueueStyle($name);
        }
    }

    public function enqueueScripts()
    {
        foreach (self::$scripts as $name => $script) {
            $this->_enqueueScript($name);
        }
    }

    public function _enqueueScript($name)
    {
        if (isset(self::$scripts[$name])) {
            self::$scripts[$name]['queue'] = true;
        }
    }

    public function _enqueueStyle($name)
    {
        if (isset(self::$styles[$name])) {
            self::$styles[$name]['queue'] = true;
        }

    }

    public function styleRender($in_header = false, $type = '')
    {
        foreach (self::$styles as $name => $style) {
            if ($style['queue'] && $style['header'] == $in_header && !in_array($name, self::$enqueuedStyles) && in_array($style['type'], ['', $type])) {
                self::$enqueuedStyles[] = $name;
                echo '<link href="' . $style['url'] . '" rel="stylesheet">' . "\r\n";
            }
        }
    }

    public function scriptRender($in_header = false, $type = '')
    {
        foreach (self::$scripts as $name => $script) {
            if ($script['queue'] && $script['header'] == $in_header && !in_array($name, self::$enqueuedScripts) && in_array($script['type'], ['', $type])) {
                self::$enqueuedScripts[] = $name;
                echo '<script id="' . e($name) . '" src="' . $script['url'] . '"></script>' . "\r\n";
            }
        }
    }

    public function get_styles()
    {
        return self::$styles;
    }

    public function get_enqueued_styles()
    {
        return self::$enqueuedStyles;
    }

    public function set_enqueued_styles($name)
    {
        self::$enqueuedStyles[] = $name;
    }

    public function get_scripts()
    {
        return self::$scripts;
    }

    public function get_enqueued_scripts()
    {
        return self::$enqueuedScripts;
    }

    public function set_enqueued_scripts($name)
    {
        self::$enqueuedScripts[] = $name;
    }

    public static function get_inst()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}
