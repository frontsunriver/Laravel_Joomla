<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => Config::get('awebooking.prefix_dashboard'), 'middleware' => ['authenticate', 'locale']], function () {

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::get('menus', 'MenuController@index')->name('menus');

    Route::post('update-menu', 'MenuController@updateMenuAction');

    Route::post('delete-menu', 'MenuController@deleteMenuAction');

    //SEO
    Route::post('save-seo', 'SeoController@_saveSeo');

    Route::post('seo-page-save', 'SeoController@_seoPageSave');

    Route::get('seo-tools', 'SeoController@_seoTools');

    // QR Code
    Route::post('get-qrcode', 'DashboardController@_getQRcode');

    // Options route
    Route::get('settings', 'OptionController@_getSetting')->name('settings');

    Route::post('settings', 'OptionController@_saveSetting')->name('save-settings');

    Route::post('save-quick-settings', 'OptionController@_saveQuickSetting')->name('save-quick-settings');

    Route::post('set-featured-image', 'OptionController@_setFeaturedImage')->name('set-featured-image');

    Route::post('delete-featured-image', 'OptionController@_deleteFeaturedImage')->name('delete-featured-image');

    Route::post('get-list-item', 'OptionController@_getListItem')->name('get-list-item');

    //Media route
    Route::get('media', 'MediaController@_getMedia')->name('media');

    Route::post('add-media', 'MediaController@_addMedia')->name('add-media');

    Route::post('search-media-modal', 'MediaController@_allMedia')->name('search-media-modal');

    Route::post('delete-media-item', 'MediaController@_deleteMediaItem')->name('delete-media-item');

    Route::post('all-media', 'MediaController@_allMedia')->name('all-media');

    Route::post('bulk-delete-media', 'MediaController@_bulkDeleteMedia')->name('bulk-delete-media');

    Route::post('media-bulk-actions', 'MediaController@_mediaBulkActions')->name('media-bulk-actions');

    Route::post('media-item-detail', 'MediaController@_mediaItemDetail')->name('media-item-detail');

    Route::post('update-media-item-detail', 'MediaController@_updateMediaItemDetail')->name('update-media-item-detail');

    Route::post('get-attachments', 'MediaController@_getAttachments')->name('get-attachments');

    Route::post('get-advance-attachments', 'MediaController@_getAdvanceAttachments')->name('get-advance-attachments');

    Route::post('get-inline-media', 'MediaController@_getInlineMedia')->name('get-inline-media');

    //Profile
    Route::get('profile', 'DashboardController@_getProfile')->name('profile');

    Route::post('update-your-profile', 'DashboardController@_updateYourProfile')->name('update-your-profile');

    Route::post('update-your-avatar', 'DashboardController@_updateYourAvatar')->name('update-your-avatar');

    Route::post('update-password', 'DashboardController@_updatePassword')->name('update-password');

    Route::post('update-your-payout-information', 'DashboardController@_updateYourPayoutInformation')->name('update-your-payout-information');

    Route::post('change-status-payout', 'EarningController@_changeStatusPayout')->name('change-status-payout');

    Route::post('delete-payout-item', 'EarningController@_deletePayoutItem')->name('delete-payout-item');

    Route::post('get-payout-detail', 'EarningController@_getPayoutDetail')->name('get-payout-detail');

    Route::get('get-earning/{user_id?}', 'EarningController@getEarning')->name('get-earning');

    // Terms route
    Route::get('get-terms/{type}/{page?}', 'TermController@_getTerms')->name('get-terms');

    Route::post('add-new-term', 'TermController@_addNewTerm')->name('add-new-term');

    Route::post('update-term-item', 'TermController@_updateTermItem')->name('update-term-item');

    Route::post('get-term-item/{name}', 'TermController@_getTermItem')->name('get-term-item');

    Route::post('delete-term-item', 'TermController@_deleteTermItem')->name('delete-term-item');

    //Icon route
    Route::post('get-font-icon', 'DashboardController@_getFontIcon')->name('get-font-icon');

    Route::get('payout/{page?}', 'EarningController@_allPayout')->name('payout');

    //Coupon route
    Route::get('coupon/{page?}', 'CouponController@_allCoupon')->name('coupon');

    Route::post('add-new-coupon', 'CouponController@_addNewCoupon')->name('add-new-coupon');

    Route::post('change-coupon-status', 'CouponController@_changeCouponStatus')->name('change-coupon-status');

    Route::post('get-coupon-item', 'CouponController@_getCouponItem')->name('get-coupon-item');

    Route::post('update-coupon-item', 'CouponController@_updateCouponItem')->name('update-coupon-item');

    Route::post('delete-coupon-item', 'CouponController@_deleteCouponItem')->name('delete-coupon-item');

    // Custom price route
    Route::post('add-new-custom-price', 'CustomPriceController@_addNewCustomPrice')->name('add-new-custom-price');

    Route::post('delete-custom-price-item', 'CustomPriceController@_deleteCustomPriceItem')->name('delete-custom-price-item');

    Route::post('change-price-status', 'CustomPriceController@_changeStatusCustomPriceItem')->name('change-price-status');

    //Route Page
    Route::get('all-page/{page?}', 'PageController@_allPage')->name('all-page');

    Route::get('add-new-page', 'PageController@_addNewPage')->name('add-new-page');

    Route::get('edit-page/{id}', 'PageController@_editPage')->name('edit-page');

    Route::post('edit-page', 'PageController@_editPageAction');

    Route::post('delete-page-item', 'PageController@_deletePageAction');

    Route::post('page-bulk-actions', 'PageController@_bulkActions');

    Route::post('change-page-status', 'PageController@_changePageStatus');

    //Route Post
    Route::get('all-post/{page?}', 'PostController@_allPost')->name('all-post');

    Route::get('add-new-post', 'PostController@_addNewPost')->name('add-new-post');

    Route::get('edit-post/{id}', 'PostController@_editPost')->name('edit-post');

    Route::post('edit-post', 'PostController@_editPostAction');

    Route::post('delete-post-item', 'PostController@_deletePostAction');

    Route::get('post-category/{page?}', 'TermController@_postCategory')->name('post-category');

    Route::post('get-post-category-item', 'TermController@_getPostCategoryItem');

    Route::get('post-tag/{page?}', 'TermController@_postTag')->name('post-tag');

    Route::post('get-post-tag-item', 'TermController@_getPostTagItem');

    Route::post('get-booking-invoice', 'BookingController@_getBookingInvoice');

    Route::post('change-booking-status', 'BookingController@_changeBookingStatus');

    Route::get('all-booking/{page?}', 'BookingController@_allBooking')->name('all-booking');

    Route::get('comment/{page?}', 'PostController@_postComment')->name('comment');

    Route::post('post-bulk-actions', 'PostController@_bulkActions');

    Route::post('change-post-status', 'PostController@_changePostStatus');

    //Comment Route
    Route::get('review/{type}/{page?}', 'CommentController@_getListReview')->name('review');

    Route::post('delete-review-item', 'CommentController@_deleteReviewAction');

    Route::post('change-review-status', 'CommentController@_changeReviewStatusAction');

    // Booking
    Route::get('booking-confirmation', 'BookingController@_bookingConfirmation');

    // User
    Route::get('user-management/{page?}', 'UserController@_userManagement')->name('user-management');

    Route::get('user-registration/{page?}', 'UserController@_userRegistration')->name('user-registration');

    Route::post('get-partner-info', 'UserController@_getPartnerInfo')->name('get-partner-info');

    Route::post('approve-user', 'UserController@_approveUser')->name('approve-user');

    Route::post('add-new-user', 'UserController@_addNewUser')->name('add-new-user');

    Route::post('delete-user', 'UserController@_deleteUser')->name('delete-user');

    Route::post('get-user-item', 'UserController@_getUserItem');

    Route::post('get-user-delete-modal', 'UserController@_getUserDeleteModal');

    Route::post('delete-user-modal', 'UserController@_deleteUserModal');

    Route::post('update-user-item', 'UserController@_updateUserItem');

    Route::post('get-inventory', 'OptionController@_getInventory')->name('get-inventory');

    Route::post('get-availability-time-slot', 'OptionController@_getAvailabilityTimeSlot')->name('get-inventory');

    Route::get('import-fonts', 'ImportController@_getImportFontsScreen')->name('import-fonts');

    Route::post('import-fonts', 'ImportController@_adminImportFonts');

    Route::get('addons', 'AddonsController@_addons')->name('addons');

    Route::post('action-extension', 'AddonsController@_actionExtension')->name('action-extension');

    //Translation
    Route::get('translation', 'TranslationController@index')->name('translation');

    Route::post('update-translate', 'TranslationController@translateString')->name('update-translate');

    Route::post('scan-translation', 'TranslationController@scanTranslate')->name('scan-translate');

    //Languages
    Route::get('language/{page?}', 'TranslationController@language')->name('language');

    Route::post('update-language', 'TranslationController@updateLanguage')->name('update-language');

    Route::post('change-language-status', 'TranslationController@changeLanguageStatus')->name('change-language-status');

    Route::post('delete-language-item', 'TranslationController@deleteLanguageItem')->name('delete-language-item');

    Route::post('change-language-order', 'TranslationController@changeLanguageOrder')->name('change-language-order');

    //Advanced
    Route::get('email-checker', 'MailController@_emailChecker')->name('email-checker');

    Route::post('email-checker', 'MailController@_emailCheckerPost')->name('email-checker-post');

    Route::get('tinypng-compress', 'TinyPNGController@_tinyPngCompress')->name('tinypng-compress');

    Route::post('tinypng-compress-save', 'TinyPNGController@_tinyPngCompressPost')->name('tinypng-compress-save');

    Route::get('site-map', 'SitemapController@_siteMapCompress')->name('site-map');

    Route::post('site-map-save', 'SitemapController@_siteMapCompressSave')->name('site-map-save');

    // API
    Route::get('api-settings', 'APIController@_index')->name('api-settings');

    Route::post('save-api-settings', 'APIController@_saveApiSettings')->name('save-api-settings');
});

//Experience Route
Route::group(['prefix' => Config::get('awebooking.prefix_dashboard'), 'middleware' => ['authenticate', 'locale', 'enable_experience']], function () {
    Route::get('my-experience/{page?}', 'Services\ExperienceController@_myExperience')->name('my-experience');

    Route::get('add-new-experience', 'Services\ExperienceController@_addNewExperience')->name('add-new-experience');

    Route::get('edit-experience/{id}', 'Services\ExperienceController@_editExperience')->name('edit-experience');

    Route::post('post-new-experience', 'Services\ExperienceController@_updateExperience')->name('post-new-experience');

    Route::post('change-status-experience', 'Services\ExperienceController@_changeStatusExperience')->name('change-status-experience');

    Route::post('delete-experience-item', 'Services\ExperienceController@_deleteExperienceItem')->name('delete-experience-item');

    Route::post('duplicate-experience', 'Services\ExperienceController@_duplicateExperience')->name('duplicate-experience');

    Route::post('experience-bulk-actions', 'Services\ExperienceController@_bulkActions');
});

Route::group(['middleware' => ['locale', 'enable_experience', 'html_compress']], function () {
    $experience = Config::get('awebooking.post_types')['experience'];

    Route::get($experience['slug'] . '/{experience_id}/{experience_name?}', 'Services\ExperienceController@_getExperienceSingle')->name($experience['slug']);

    Route::get($experience['slug'] . '/ical/{experience_id}/ical.ics', 'Services\ExperienceController@_getIcalUrl');

    Route::post('get-experience-availability-single', 'Services\ExperienceController@_getExperienceAvailabilitySingle');

    Route::post('get-experience-date-time', 'Services\ExperienceController@_getExperienceDateTime');

    Route::post('get-experience-guest', 'Services\ExperienceController@_getExperienceGuest');

    Route::post('get-total-price-experience', 'Services\ExperienceController@_getTotalPriceExperience');

    Route::post('add-to-cart-experience', 'Services\ExperienceController@_addToCartExperience');

    Route::get($experience['search_slug'], 'Services\ExperienceController@_searchPage')->name($experience['search_slug']);

    Route::post($experience['search_slug'], 'Services\ExperienceController@_getSearchResult');

    Route::post('experience-send-enquiry-form', 'Services\ExperienceController@_sendEnquiryForm');

});


//Home Route
Route::group(['prefix' => Config::get('awebooking.prefix_dashboard'), 'middleware' => ['authenticate', 'locale', 'enable_home']], function () {
    Route::get('my-home/{page?}', 'Services\HomeController@_myHome')->name('my-home');

    Route::get('add-new-home', 'Services\HomeController@_addNewHome')->name('add-new-home');

    Route::get('edit-home/{id}', 'Services\HomeController@_editHome')->name('edit-home');

    Route::post('post-new-home', 'Services\HomeController@_updateHome')->name('post-new-home');

    Route::post('change-status-home', 'Services\HomeController@_changeStatusHome')->name('change-status-home');

    Route::post('delete-home-item', 'Services\HomeController@_deleteHomeItem')->name('delete-home-item');

    Route::post('duplicate-home', 'Services\HomeController@_duplicateHome')->name('duplicate-home');

    Route::post('home-bulk-actions', 'Services\HomeController@_bulkActions');
});


Route::group(['middleware' => ['locale', 'enable_home', 'html_compress', 'sameorigin']], function () {
    $home = Config::get('awebooking.post_types')['home'];

    Route::get($home['slug'] . '/{home_id}/{home_name?}', 'Services\HomeController@_getHomeSingle')->name($home['slug']);

    Route::get($home['slug'] . '/ical/{home_id}/ical.ics', 'Services\HomeController@_getIcalUrl');

    Route::post('get-home-availability-single', 'Services\HomeController@_getHomeAvailabilitySingle');

    Route::post('get-home-availability-time-single', 'Services\HomeController@_getHomeAvailabilityTimeSingle');

    Route::post('get-home-price-realtime', 'Services\HomeController@_getHomePriceRealTime');

    Route::post('add-to-cart-home', 'Services\HomeController@_addToCartHome');

    Route::post('home-send-enquiry-form', 'Services\HomeController@_sendEnquiryForm');

    Route::post('get-home-near-you-ajax', 'Services\HomeController@_getHomeNearYouAjax');

    Route::post('get-latest-home-ajax', 'Services\HomeController@_getLatestHomeAjax');

    Route::get($home['search_slug'], 'Services\HomeController@_searchPage')->name($home['search_slug']);

    Route::post($home['search_slug'], 'Services\HomeController@_getSearchResult');

    Route::post('get-advanced-search', 'Services\HomeController@advancedSearch');

    Route::post('get-inventory-home', 'Services\HomeController@_getAvailabilityHome');
});

//Car Route
Route::group(['prefix' => Config::get('awebooking.prefix_dashboard'), 'middleware' => ['authenticate', 'locale', 'enable_car']], function () {
    Route::get('add-new-car', 'Services\CarController@_addNewCar')->name('add-new-car');

    Route::post('post-new-car', 'Services\CarController@_updateCar')->name('post-new-car');

    Route::get('my-car/{page?}', 'Services\CarController@_myCar')->name('my-car');

    Route::get('edit-car/{id}', 'Services\CarController@_editCar')->name('edit-car');

    Route::post('change-status-car', 'Services\CarController@_changeStatusCar')->name('change-status-car');

    Route::post('delete-car-item', 'Services\CarController@_deleteCarItem')->name('delete-car-item');

    Route::post('duplicate-car', 'Services\CarController@_duplicateCar')->name('duplicate-car');

    Route::post('car-bulk-actions', 'Services\CarController@_bulkActions');
});

Route::group(['middleware' => ['locale', 'enable_car', 'html_compress']], function () {
    $car = Config::get('awebooking.post_types')['car'];

    Route::post('get-car-availability-single', 'Services\CarController@_getCarAvailabilitySingle');

    Route::post('get-car-availability-time-single', 'Services\CarController@_getCarAvailabilityTimeSingle');

    Route::post('get-car-price-realtime', 'Services\CarController@_getCarPriceRealTime');

    Route::post('add-to-cart-car', 'Services\CarController@_addToCartCar');

    Route::get($car['search_slug'], 'Services\CarController@_searchPage')->name($car['search_slug']);

    Route::post($car['search_slug'], 'Services\CarController@_getSearchResult');

    Route::get($car['slug'] . '/{car_id}/{car_name?}', 'Services\CarController@_getCarSingle')->name($car['slug']);

    Route::post('car-send-enquiry-form', 'Services\CarController@_sendEnquiryForm');
});


Route::group(['prefix' => Config::get('awebooking.prefix_auth'), 'middleware' => ['is_login', 'locale']], function () {

    Route::get('login', 'AuthController@_getLogin')->name('login');

    Route::post('login', 'AuthController@_postLogin')->name('post.login');

});

Route::group(['prefix' => Config::get('awebooking.prefix_auth'), 'middleware' => 'locale'], function () {

    Route::post('logout', 'AuthController@_postLogout')->name('post.logout');
    Route::get('logout', 'AuthController@_getLogout')->name('get.logout');
});

Route::group(['prefix' => Config::get('awebooking.prefix_auth'), 'middleware' => 'locale'], function () {

    Route::get('reset-password', 'AuthController@_getResetPassword')->name('get.reset.password');

    Route::post('reset-password', 'AuthController@_postResetPassword')->name('post.reset.password');

    Route::get('sign-up', 'AuthController@_getSignUp')->name('get.sign.up');

    Route::post('sign-up', 'AuthController@_postSignUp')->name('post.sign.up');
});

Route::group(['prefix' => Config::get('awebooking.prefix_dashboard'), 'middleware' => ['authenticate', 'locale']], function () {

    Route::get('all-notifications/{page?}', 'NotificationController@_allNotifications')->name('all-notifications');

    Route::post('delete-notification', 'NotificationController@_deleteNotification')->name('delete-notification');
});

Route::group(['middleware' => ['locale', 'html_compress']], function () {
// Notification
    Route::post('get-notifications', 'NotificationController@_getNotifications');

    Route::get(Config::get('awebooking.checkout_slug'), 'CheckoutController@_checkoutPage')->name('check-out');

    Route::get(Config::get('awebooking.after_checkout_slug'), 'CheckoutController@_thankyouPage')->name('thank-you');

    Route::post(Config::get('awebooking.after_checkout_slug'), 'CheckoutController@_thankyouPage')->name('thank-you-post');

    Route::post('add-coupon', 'CouponController@_addCouponToCart');

    Route::post('remove-coupon', 'CouponController@_removeCouponFromCart');

    Route::post('checkout', 'CheckoutController@_checkoutAction');

    Route::get('page/{page_id}/{page_slug?}', 'PageController@viewPage')->name('view-page');

    Route::get('post/{post_id}/{post_slug?}', 'PostController@viewPost')->name('post');

    Route::get('blog/{page?}', 'PostController@viewBlog')->name('blog');

    Route::get('category/{term_slug}/{page?}', 'PostController@viewCategory')->name('category');

    Route::get('tag/{term_slug}/{page?}', 'PostController@viewTag')->name('tag');

    Route::post('add-post-comment', 'CommentController@addCommentAction');

    Route::get('/', 'HomePageController@index')->name('home-page');

    Route::get('/cities/list', 'HomePageController@searchCities')->name('city-list-search');

    Route::get('home', 'HomePageController@_homePage')->name('home-demo');

    Route::get('experience', 'HomePageController@_experiencePage')->name('experience-demo');

    Route::get('car', 'HomePageController@_carPage')->name('car-demo');

    Route::post('subscribe-email', 'AuthController@subscribeEmail');

// Social login
    Route::get('social-login/{social?}', 'SocialController@_checkLogin');

// Contact page
    Route::get('contact-us', 'HomePageController@_contactPage')->name('contact-us');

    Route::post('contact-us-post', 'HomePageController@_contactUsPost');

    Route::get('become-a-host', 'UserController@_becomeAHost')->name('become-a-host');

    Route::post('become-a-host', 'UserController@_becomeAHostPost')->name('become-a-host-post');

    Route::get('welcome-user/{return}', 'UserController@_afterRegisterPartner')->name('welcome-user');

    Route::post('import-demo', 'ImportController@_runImport');
});


Route::group(['middleware' => ['html_compress']], function () {

    Route::get('coming-soon', 'HomePageController@_comingSoon')->name('coming-soon');

    Route::get('system-tools', 'UpdateController@_systemTools')->name('system-tools');

    Route::post('system-tools', 'UpdateController@_systemToolsPost');

    Route::post('set-icon', 'UpdateController@_setIconSVG');

    Route::post('get-icon', 'UpdateController@_getIconSVG');

    Route::post('import-data', 'ImportController@_adminImportData');
});

/*Sitemap*/

Route::get('sitemap_index.xml', 'SitemapController@_createSitemapIndex')->name('create-sitemap-index');

Route::get('post-{page}.xml', 'SitemapController@_createSitemapPost')->name('create-sitemap-post');
Route::get('post.xml', 'SitemapController@_createSitemapPost')->name('create-sitemap-post');

Route::get('home-{page}.xml', 'SitemapController@_createSitemapHome')->name('create-sitemap-home');
Route::get('home.xml', 'SitemapController@_createSitemapHome')->name('create-sitemap-home');

Route::get('car-{page?}.xml', 'SitemapController@_createSitemapCar')->name('create-sitemap-car');
Route::get('car.xml', 'SitemapController@_createSitemapCar')->name('create-sitemap-car');

Route::get('experience-{page?}.xml', 'SitemapController@_createSitemapExperience')->name('create-sitemap-experience');
Route::get('experience.xml', 'SitemapController@_createSitemapExperience')->name('create-sitemap-experience');

Route::get('page{page?}.xml', 'SitemapController@_createSitemapPage')->name('create-sitemap-page');
Route::get('page.xml', 'SitemapController@_createSitemapPage')->name('create-sitemap-page');

Route::get('cache/{name}/{redirect}', 'UpdateController@_clearCache')->name('clear-cache');

Route::get('artisan/cache', function () {
    Artisan::call('cache:clear');
    return Artisan::output();
});
Route::get('artisan/view', function () {
    Artisan::call('view:clear');
    return Artisan::output();
});

Route::get('artisan/migration', function () {
    Artisan::call('migrate');
    return Artisan::output();
});
