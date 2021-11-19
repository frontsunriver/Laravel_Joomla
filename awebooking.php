<?php
/*
 * Name: AweBooking
 * Slug: awebooking
 * Description: Awesome Booking System
 * Author: the Ohteamvn
 * Version: 1.5.1
 */

class AweBooking
{
    public function __construct()
    {
        $this->_load('Hooks');
        $this->_load('Abstracts');
        $this->_load('Helpers');
        $this->_load('Libraries');
        $this->_load('Payments');
    }

    public function _load($folder)
    {
        $path = dirname(__FILE__);
        $app = $path . '/app/';
        $files = glob($app . $folder . "/*");
        if (is_array($files)) {
            foreach ($files as $key => $file) {
                if (file_exists($file)) {
                    $filename = pathinfo($file)['basename'];
                    $custom_file = $path . '/app/awe-custom/' . $folder . '/' . $filename;
                    if (substr($filename, -4) == '.php') {
                        $name = substr($filename, 0, -4);
                        if(file_exists($custom_file)){
                            require_once($custom_file);
                        }else{
                            require_once($file);
                        }
                        if (class_exists($name)) {
                            $testClass = new \ReflectionClass($name);
                            if (!$testClass->isAbstract()) {
                                if (method_exists($name, 'get_inst')) {
                                    $name::get_inst();
                                } elseif (!method_exists($name, 'not')) {
                                    new $name();
                                }
                            }
                        }
                    }
                } elseif (is_dir($file)) {
                    $dir = $folder . '/' . pathinfo($file)['basename'];
                    $this->_load($dir);
                }
            }
        }
    }
}

new AweBooking();


global $hh_filter, $hh_actions, $hh_current_filter, $hh_fonts, $post, $booking, $old_booking, $hh_lazyload, $hh_extensions, $hh_rtl, $hh_is_multi_language, $hh_available_languages;

if ($hh_filter) {
    $hh_filter = Hook::build_preinitialized_hooks($hh_filter);
} else {
    $hh_filter = array();
}

if (!isset($hh_actions)) {
    $hh_actions = array();
}

if (!isset($hh_current_filter)) {
    $hh_current_filter = array();
}
