<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/18/2020
 * Time: 2:58 PM
 */

use Illuminate\Support\Facades\File;
use MatthiasMullie\Minify;
use Illuminate\Support\Facades\Route;

class HHMinify
{
    public $cache_path;

    public function __construct()
    {

    }

    public function renderCSS($in_header = true, $type = '')
    {
        global $hh_rtl;

        $current_route = Route::current();
        $name = $current_route->getName();
        $prefix = $in_header ? 'h' : 'f';
        $prefix .= '_' . $name;
        $subfix = $hh_rtl ? $type . '_rtl' : $type;
        $this->cache_path = public_path('caching');
        $minified_css_file = $this->cache_path . '/' . $prefix . '_m_' . $subfix . '.css';

        $styles = EnqueueScripts::get_inst()->get_styles();

        // Enqueue all external scripts.
        foreach ($styles as $name => $style) {
            $enqueued_styles = EnqueueScripts::get_inst()->get_enqueued_styles();
            if ($style['queue'] && $style['header'] == $in_header && !in_array($name, $enqueued_styles) && in_array($style['type'], ['', $type]) && $style['external']) {
                echo '<link href="' . $style['url'] . '" rel="stylesheet">' . "\r\n";
                EnqueueScripts::get_inst()->set_enqueued_styles($name);
            }
        }

        // Then merge all internal scripts
        if (is_file($minified_css_file)) {
            echo '<link href="' . asset('caching/' . $prefix . '_m_' . $subfix . '.css') . '" rel="stylesheet">' . "\r\n";
        } else {
            if (!is_dir($this->cache_path)) {
                File::makeDirectory($this->cache_path, $mode = 0755, true, true);
            }

            $content_minified = '';
            foreach ($styles as $name => $style) {
                $enqueued_styles = EnqueueScripts::get_inst()->get_enqueued_styles();
                if ($style['queue'] && $style['header'] == $in_header && !in_array($name, $enqueued_styles) && in_array($style['type'], ['', $type]) && !$style['external']) {

                    $url = str_replace(asset('/'), public_path('/'), $style['url']);

                    $content = $this->get_render($url);
                    $minifier = new Minify\CSS();
                    $minifier->add($content);
                    $content_minified .= $minifier->minify();
                    EnqueueScripts::get_inst()->set_enqueued_styles($name);
                }
            }
            if (!empty($content_minified)) {
                File::put($minified_css_file, $content_minified);
                echo '<link href="' . asset('caching/' . $prefix . '_m_' . $subfix . '.css') . '" rel="stylesheet">' . "\r\n";
            }
        }
    }

    public function renderJS($in_header = true, $type = '')
    {
        $current_route = Route::current();
        $name = $current_route->getName();
        $prefix = $in_header ? 'h' : 'f';
        $prefix .= '_' . $name;
        $subfix = $type;
        $this->cache_path = public_path('caching');
        $minified_js_file = $this->cache_path . '/' . $prefix . '_m_' . $subfix . '.js';

        $scripts = EnqueueScripts::get_inst()->get_scripts();

        foreach ($scripts as $name => $script) {
            $enqueued_scripts = EnqueueScripts::get_inst()->get_enqueued_scripts();
            if ($script['queue'] && $script['header'] == $in_header && !in_array($name, $enqueued_scripts) && in_array($script['type'], ['', $type]) && $script['external']) {
                echo '<script id="' . e($name) . '" src="' . $script['url'] . '"></script>' . "\r\n";
                EnqueueScripts::get_inst()->set_enqueued_scripts($name);
            }
        }

        if (is_file($minified_js_file)) {
            echo '<script src="' . asset('caching/' . $prefix . '_m_' . $subfix . '.js') . '"></script>' . "\r\n";
        } else {
            if (!is_dir($this->cache_path)) {
                File::makeDirectory($this->cache_path, $mode = 0755, true, true);
            }
            $content_minified = '';
            foreach ($scripts as $name => $script) {
                $enqueued_scripts = EnqueueScripts::get_inst()->get_enqueued_scripts();
                if ($script['queue'] && $script['header'] == $in_header && !in_array($name, $enqueued_scripts) && in_array($script['type'], ['', $type]) && !$script['external']) {
                    $url = str_replace(asset('/'), public_path('/'), $script['url']);
                    $content = $this->get_render($url);
                    $content_minified .= ' ' . \JShrink\Minifier::minify($content);
                    EnqueueScripts::get_inst()->set_enqueued_scripts($name);
                }
            }
            if (!empty($content_minified)) {
                File::put($minified_js_file, $content_minified);
                echo '<script src="' . asset('caching/' . $prefix . '_m_' . $subfix . '.js') . '"></script>' . "\r\n";
            }

        }
    }

    public function get_render($file)
    {
        $content = file_get_contents($file);


        return is_string($content) ? $content : '';
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
