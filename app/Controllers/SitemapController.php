<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SitemapController extends Controller
{

    public function _siteMapCompressSave(Request $request)
    {
        $sitemap_per_page = $request->get('sitemap_per_page', '10');
        $sitemap_home_enable = $request->get('sitemap_home_enable', 'off');
        $sitemap_car_enable = $request->get('sitemap_car_enable', 'off');
        $sitemap_experience_enable = $request->get('sitemap_experience_enable', 'off');
        $sitemap_post_enable = $request->get('sitemap_post_enable', 'off');
        $sitemap_page_enable = $request->get('sitemap_page_enable', 'off');
        update_opt('sitemap_per_page', $sitemap_per_page);
        update_opt('sitemap_home_enable', $sitemap_home_enable);
        update_opt('sitemap_car_enable', $sitemap_car_enable);
        update_opt('sitemap_experience_enable', $sitemap_experience_enable);
        update_opt('sitemap_post_enable', $sitemap_post_enable);
        update_opt('sitemap_page_enable', $sitemap_page_enable);

        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Saved Successfully'),
            'reload' => false
        ]);
    }

    public function _siteMapCompress(Request $request)
    {
        return view('dashboard.screens.administrator.site-map');
    }

    public function _createSitemapIndex()
    {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap.index', 1);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {

            $sitemap_per_page = sitemap_per_page();
            // Get List Post Page
            if (get_opt('sitemap_post_enable', 'on') == 'on') {
                $number_post = DB::table('post')->count();

                if ($number_post > $sitemap_per_page) {
                    $max_post_pages = (int)ceil($number_post / $sitemap_per_page);
                    for ($i = 1; $i <= $max_post_pages; $i++) {
                        $sitemap->add(url('post-' . $i . '.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                    }
                } else {
                    $sitemap->add(url('post.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                }
            }

            // Get List Page
            if (get_opt('sitemap_page_enable', 'on') == 'on') {
                $number_page = DB::table('page')->count();
                $max_page_pages = 1;
                if ($number_page <= $sitemap_per_page) {
                    $sitemap->add(url('page.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                } else {
                    $max_page_pages = (int)ceil($number_page / $sitemap_per_page);
                    for ($i = 1; $i <= $max_page_pages; $i++) {
                        $sitemap->add(url('page-' . $i . '.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                    }
                }

            }
            // Get List Service page
            $list_services = get_posttypes(true);
            foreach ($list_services as $key => $service) {
                $enable_service = get_option('enable_' . $key, 'off');
                $stm_enable_service = get_opt('sitemap_' . $key . '_enable', 'on');
                if ($enable_service == 'on' && $stm_enable_service == 'on') {
                    $number_service = DB::table($key)->count();
                    $max_service_pages = 1;
                    if ($number_service > $sitemap_per_page) {
                        $max_service_pages = (int)ceil($number_service / $sitemap_per_page);
                        for ($i = 1; $i <= $max_service_pages; $i++) {
                            $sitemap->add(url($key . '-' . $i . '.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                        }
                    }else{
                        $sitemap->add(url($key . '.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                    }

                }
            }
        }
        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        $list_page = Config::get('awebooking.pages_name');
        foreach ($list_page as $page) {
            if ($page['sitemap'] == true) {
                $sitemap->add(url($page['screen']), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
            }
        }
        return $sitemap->render('xml');
    }

    public function _createSitemapPost(Request $request, $page = 1)
    {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap.post-' . $page, 60);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {
            // get all posts from db
            $posts = DB::table('post')->orderBy('created_at', 'desc');
            $sitemap_per_page = sitemap_per_page();
            $offset = ($page - 1) * $sitemap_per_page;
            $posts = $posts->limit($sitemap_per_page)->offset($offset)->get();

            // add every post to the sitemap
            foreach ($posts as $post) {
                $url = get_the_permalink($post->post_id, $post->post_slug, 'post');
                $sitemap->add($url, date(DATE_ISO8601, $post->created_at), '1.0', 'daily');
            }
        }

        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml');
    }

    public function _createSitemapHome(Request $request, $page = 1)
    {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap.home-' . $page, 60);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {
            // get all posts from db
            $posts = DB::table('home')->orderBy('created_at', 'desc');
            $sitemap_per_page = sitemap_per_page();
            $offset = ($page - 1) * $sitemap_per_page;
            $posts = $posts->limit($sitemap_per_page)->offset($offset)->get();

            // add every post to the sitemap
            foreach ($posts as $post) {
                $url = get_the_permalink($post->post_id, $post->post_slug, 'home');
                $sitemap->add($url, date(DATE_ISO8601, $post->created_at), '1.0', 'daily');
            }
        }

        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml');
    }

    public function _createSitemapCar(Request $request, $page = 1)
    {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap.car-' . $page, 60);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {
            // get all posts from db
            $posts = DB::table('car')->orderBy('created_at', 'desc');
            $sitemap_per_page = sitemap_per_page();
            $offset = ($page - 1) * $sitemap_per_page;
            $posts = $posts->limit($sitemap_per_page)->offset($offset)->get();

            // add every post to the sitemap
            foreach ($posts as $post) {
                $url = get_the_permalink($post->post_id, $post->post_slug, 'car');
                $sitemap->add($url, date(DATE_ISO8601, $post->created_at), '1.0', 'daily');
            }
        }

        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml');
    }

    public function _createSitemapExperience(Request $request, $page = 1)
    {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap.experience-' . $page, 60);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {
            // get all posts from db
            $posts = DB::table('experience')->orderBy('created_at', 'desc');
            $sitemap_per_page = sitemap_per_page();
            $offset = ($page - 1) * $sitemap_per_page;
            $posts = $posts->limit($sitemap_per_page)->offset($offset)->get();

            // add every post to the sitemap
            foreach ($posts as $post) {
                $url = get_the_permalink($post->post_id, $post->post_slug, 'experience');
                $sitemap->add($url, date(DATE_ISO8601, $post->created_at), '1.0', 'daily');
            }
        }

        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml');
    }

    public function _createSitemapPage(Request $request, $page = 1)
    {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap.page-' . $page, 60);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {
            // get all posts from db
            $posts = DB::table('page')->orderBy('created_at', 'desc');
            $sitemap_per_page = sitemap_per_page();
            $offset = ($page - 1) * $sitemap_per_page;
            $posts = $posts->limit($sitemap_per_page)->offset($offset)->get();

            // add every post to the sitemap
            foreach ($posts as $post) {
                $url = get_the_permalink($post->post_id, $post->post_slug, 'page');
                $sitemap->add($url, date(DATE_ISO8601, $post->created_at), '1.0', 'daily');
            }
        }

        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml');
    }
}
