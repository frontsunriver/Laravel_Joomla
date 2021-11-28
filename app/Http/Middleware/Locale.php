<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(is_multi_language()) {
		    $lang = request()->get( 'lang', '' );

		    if ( ! session_id() ) {
			    session_start();
		    }
		    if ( ! empty( $lang ) ) {
			    $langs = get_languages();
			    if ( ! empty( $langs ) ) {
				    if ( in_array( $lang, $langs ) ) {
					    $_SESSION['hh_language'] = $lang;
				    }
			    }
		    } else {
			    $currentSectionLang = isset( $_SESSION['hh_language'] ) ? $_SESSION['hh_language'] : '';
			    $langs              = get_languages();
			    if ( empty( $currentSectionLang ) ) {
				    if ( ! empty( $langs ) ) {
					    $_SESSION['hh_language'] = $langs[0];
				    }
			    } else {
				    if ( ! empty( $langs ) && ! in_array( $currentSectionLang, $langs ) ) {
					    if ( isset( $_SESSION['hh_language'] ) ) {
						    unset( $_SESSION['hh_language'] );
					    }
				    }
			    }
		    }

		    if ( isset( $_SESSION['hh_language'] ) ) {
			    $language = $_SESSION['hh_language'];
		    } else {
			    $lang_option = get_option( 'site_language', '' );
			    if ( empty( $lang_option ) ) {
				    $lang_option = config( 'app.locale' );
			    }
			    $language = $lang_option;
		    }

	    }else{
		    $lang_option = get_option( 'site_language', '' );
		    if ( empty( $lang_option ) ) {
			    $lang_option = config( 'app.locale' );
		    }
		    $language = $lang_option;
	    }

	    app()->setLocale($language);

        global $hh_rtl, $hh_available_languages;

        if (is_multi_language()) {
            $current_lang = get_current_language_data();
            $hh_rtl = !!(isset($current_lang['rtl']) && $current_lang['rtl'] == 'on');
        } else {
            $hh_rtl = !!(get_option('right_to_left', 'off') == 'on');
        }

        if (is_null($hh_available_languages)) {
            $hh_available_languages = get_languages();
        }

        return $next($request);

    }
}
