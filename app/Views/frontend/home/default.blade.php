@include('frontend.components.header')
<style>
    .table thead th {
        border: 0px;
        text-align: center;
    }
    .table td, .table th {
        border: 0px;
        text-align: center;
    }
</style>
<?php
enqueue_script('scroll-magic-js');
enqueue_style('image-gallery-css');
global $post;
?>

<div class="single-page single-home pb-5">
    <!-- Gallery -->
    <?php
    $gallery = $post->gallery;
    $thumbnail_id = get_home_thumbnail_id($post->post_id);
    $thumbnailUrl = get_attachment_url($thumbnail_id, 'full');
    $specialPrices = array();
    $periodPrices = array();
    foreach ($post->period_stay_date['results'] as $key => $item) {
        if($item->first_minute == 'on' || $item->last_minute == 'on'){
            array_push($specialPrices, $item);
        }else {
            array_push($periodPrices, $item);
        }
    }
    $total_array = array();
    foreach ($periodPrices as $item) {
        $special_flag = false;
        foreach ($specialPrices as $value) {
            if($value->start_time >= $item->start_time && $value->end_time <= $item->end_time){
                $special_flag = true;
                // $item->first_minute = 'on';
                // $item->last_minute = 'on';
                // $item->discount_percent = $value->discount_percent;
                $value->price_per_night = $item->price_per_night;
                $value->stay_min_date = $item->stay_min_date;
                array_push($total_array, $item);
                array_push($total_array, $value);
                break;
            }
        }
        if(!$special_flag) {
            array_push($total_array, $item);
        }
    }
    ?>
    <div class="hh-gallery hh-thumbnail has-background-image" data-src="{{ $thumbnailUrl }}"
         style="background-image: url({{ $thumbnailUrl }})">
        <div class="controls">
            <a href="javascript: void(0);" class="view-gallery item-link"><span>{{__('View Photos')}}</span> <i
                    class="ti-gallery"></i> </a>
        </div>
        <?php
        if (!empty($gallery)) {
            enqueue_script('light-gallery-js');
            enqueue_style('light-gallery-css');

            $gallery = explode(',', $gallery);
            $data = [];
            foreach ($gallery as $key => $val) {
                $url = get_attachment_url($val);
                if (!empty($url)) {
                    $data[] = [
                        'src' => $url
                    ];
                }
            }
            if (!empty($data)) {
                $data = base64_encode(json_encode($data));
                echo '<div class="data-gallery" data-gallery="' . esc_attr($data) . '"></div>';
            }
        }
        ?>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-content">
                @include('frontend.components.breadcrumb', ['currentPage' => get_translate($post->post_title)])
                <h1 class="title mt-3">
                    {{ get_translate($post->post_title) }}
                    @if($post->is_featured == 'on')
                        <span class="is-featured">{{ get_option('featured_text', __('Featured')) }}</span>
                    @endif
                    <?php
                    $special_flag = false;
                    $discount_percent = 0;
                    if (!empty($post->period_stay_date['total'])) {
                        
                        foreach ($post->period_stay_date['results'] as $key => $item) {
                            $i = strtotime(date('d.m.Y'));
                            
                            if ($i >= $item->start_time && $i <= $item->end_time) {  
                                if($item->first_minute == 'on' || $item->last_minute == 'on'){
                                    $special_flag = true;
                                    $discount_percent = $item->discount_percent;
                                    break;
                                }
                            }
                        }
                    }
                    ?>
                    @if($special_flag)
                        <span class="is-featured">{{ -$discount_percent.'%' }}</span>
                    @endif
                </h1>
                @if ($post->location_address)
                    <p class="location">
                        <i class="ti-location-pin"></i>
                        {{ get_translate($post->location_address) }}
                    </p>
                @endif
                <?php
                $rate = $post->review_count;
                ?>
                @if ($rate)
                    <div class="count-reviews">
                        <span class="count">{{ _n("[0::%s reviews][1::%s review][2::%s reviews]", $rate) }}</span>
                        {!! star_rating_render($post->rating) !!}
                    </div>
                @endif                
                <div class="featured-amenities mt-2 mb-2">
                    <div class="item">
                        <h4>{{__('Guests:')}}</h4>
                        <span> {{ $post->number_of_guest }} </span>
                    </div>
                    <div class="item">
                        <h4>{{__('Bedrooms:')}}</h4>
                        <span>{{ $post->number_of_bedrooms }}</span>
                    </div>
                    <div class="item">
                        <h4>{{__('Bathrooms:')}}</h4>
                        <span>{{ $post->number_of_bathrooms }}</span>
                    </div>
                    <div class="item">
                        <h4>{{__('Footage:')}}</h4>
                        <span>{{ $post->size }} {{ get_option('unit_of_measure', 'm2') }}</span>
                    </div>
                    <?php
                    $type = get_term_by('id', $post->home_type);
                    $type_name = $type ? get_translate($type->term_title) : '';
                    ?>
                    @if(!empty($type_name))
                        <div class="item">
                            <h4>{{__('Type:')}}</h4>
                            <span>{{ $type_name }}</span>
                        </div>
                    @endif
                </div>
                <h2 class="heading mt-3 mb-2">{{__('Detail')}}</h2>
                {!! balanceTags(get_translate($post->post_content)) !!}
                <?php
                if (!empty($gallery)) {
                    switch (count($gallery)) {
                        case 1:
                            break;
                        case 2:
                            echo '<h2 class="heading mt-3 mb-2">Gallery</h2>
                            <div class="container">
                                <div class="row gallery-grid">
                                    <div class="col-6">
                                        <a href="javascript:void(0)" style="height:auto;">
                                            <img alt="img_gallery" src="'.get_attachment_url($gallery[0]).'">
                                        </a>                            
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0)" style="height:auto;">
                                            <img alt="img_gallery" src="'.get_attachment_url($gallery[1]).'">
                                        </a> 
                                    </div>
                                </div>
                            </div>';
                            break;
                        case 3:
                            echo '<h2 class="heading mt-3 mb-2">Gallery</h2>
                            <div class="container">
                                <div class="row gallery-grid">
                                    <div class="col-8">
                                        <a href="javascript:void(0)" style="height:calc(100% - 4px);">
                                            <img alt="img_gallery" src="'.get_attachment_url($gallery[0]).'">
                                        </a>                            
                                    </div>
                                    <div class="col-4">
                                        <a href="javascript:void(0)">
                                            <img alt="img_gallery" src="'.get_attachment_url($gallery[1]).'">
                                        </a>
                                        <a href="javascript:void(0)">
                                            <img alt="img_gallery" src="'.get_attachment_url($gallery[2]).'">
                                        </a>                                        
                                    </div>
                                </div>
                            </div>';
                            break;
                        default:
                            echo '<h2 class="heading mt-3 mb-2">Gallery</h2>
                            <div class="container">
                                <div class="row gallery-grid">
                                    <div class="col-6">
                                        <a href="javascript:void(0)" style="height:calc(100% - 4px);">
                                            <img alt="img_gallery" src="'.get_attachment_url($gallery[0]).'">
                                        </a>                            
                                    </div>
                                    <div class="col-6">
                                        <div class="col-12">
                                            <a href="javascript:void(0)">
                                                <img alt="img_gallery" src="'.get_attachment_url($gallery[1]).'">
                                            </a>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <a href="javascript:void(0)">
                                                    <img alt="img_gallery" src="'.get_attachment_url($gallery[2]).'">
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="javascript:void(0)">
                                                    <img alt="img_gallery" src="'.get_attachment_url($gallery[3]).'">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                            break;
                    }                    
                }
                ?>                
                <div style="clear:both;disply:none;"></div>
                <?php
                $amenities = $post->tax_home_amenity;
                ?>
                @if (!empty($amenities) && is_object($amenities))
                    <h2 class="heading mt-3 mb-2">{{__('Amenities')}}</h2>
                    <div class="amenities row">
                        @foreach ($amenities as $amenity)
                            <div class="col-6 col-sm-4 col-lg-3">
                                <div class="amenity-item" data-toggle="ots-tooltip"
                                     data-title="{{ get_translate($amenity->term_description) }}">
                                    @if (!$amenity->term_icon)
                                        <i class="fe-check"></i>
                                    @else
                                        {!! get_icon($amenity->term_icon, '#2a2a2a', '30px', '')  !!}
                                    @endif
                                    <span class="title">{{ get_translate($amenity->term_title) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <h2 class="heading mt-3 mb-2">{{__('Facilities')}}</h2>
                <div class="amenities row">
                    <?php 
                        $facilities = $post->facilities;
                        if(!empty($facilities)){
                            $facilities = (array)json_decode($facilities);
                            
                            foreach ($facilities as $key => $value) {
                                if(!empty($value)){ ?>
                                    <div class="col-6 col-sm-4 col-lg-3">
                                        <h4 class="mt-2 mb-2"><i class="fe-check"></i>{{$key}}</h4>
                                        <?php foreach ($value as $item) { ?>
                                            <div class="amenity-item" data-toggle="ots-tooltip">
                                                <span class="title">{{$item}}</span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php }
                            }    
                        }
                    ?>
                </div>
                <h2 class="heading mt-3 mb-2">{{__('Distance')}}</h2>
                <div class="amenities row">
                    <?php 
                        $distance = $post->distance;
                        if(!empty($distance)){
                            $distance = (array)json_decode($distance);
                            
                            foreach ($distance as $key => $value) {
                                if(!empty($value)){ ?>
                                    <div class="col-6 col-sm-4 col-lg-3">
                                        <h4 class="mt-2 mb-2"><i class="fe-check"></i>{{$key}}-{{$value}}</h4>
                                    </div>
                                <?php }
                            }    
                        }
                    ?>
                </div>

                <h2 class="heading mt-5 mb-5" style="text-align:center;">{{__('Home Period Price')}}</h2>
                <div class="table-responsive-sm mb-5">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">From</th>
                                <th scope="col">To</th>
                                <th scope="col">Per Night Price</th>
                                <th scope="col">Minimum Stay</th>
                                <th scope="col">Weekly price</th>
                            </tr>
                        </thead>
                        @if (!empty($post->period_stay_date['total']))
                            <tbody>
                            @foreach ($total_array as $item)
                                <?php
                                    $base_price = 0;
                                    $i = strtotime(date('d.m.Y'));
                                    $special_flag = false;
                                    
                                    if($item->first_minute == 'on' || $item->last_minute == 'on'){
                                        $special_flag = true;
                                        $base_price = $item->price_per_night;
                                        $special_price = $item->price_per_night * ($item->discount_percent / 100);
                                    }else if($item->price == 0 && $item->price_per_night > 0){
                                        $base_price = $item->price_per_night;
                                    }else {
                                        $base_price = $item->price;    
                                    }
                                ?>
                                <tr>
                                    <td>@if($special_flag) <p style="color:#f1556c; margin-top: -10px;line-height: 0px;"> Special Offer</p> @endif<p style="color:@if($special_flag)#f1556c; @else #000; @endif">{{ date('d.m.Y.', $item->start_time) }} </p></td>
                                    <td><p style="color:@if($special_flag)#f1556c; @else #000; @endif">{{ date('d.m.Y.', $item->end_time) }}</p></td>
                                    <td>
                                        @if($special_flag) <p style="color:#000; margin-top: -10px;text-decoration: line-through; line-height: 0px;"> {{convert_price(($base_price), '€', true, array('unit' => 'EUR')) }}</p> @endif <p style="color:@if($special_flag)#f1556c; @else #000; @endif">@if($special_flag) {{convert_price(($base_price - $special_price), '€', true, array('unit' => 'EUR')) }} @else {{convert_price($base_price, '€', true, array('unit' => 'EUR')) }} @endif</p></td>
                                    <td>@if($special_flag) <p style="color:#f1556c;"> {{$item->stay_min_date}} </p> @else <p style="color:#000;">{{ $item->stay_min_date }} </p>  @endif</td>
                                    <td>
                                        <p style="color:@if($special_flag)#f1556c; @else #000; @endif">@if($special_flag) {{convert_price(($base_price - $special_price) * 7, '€', true, array('unit' => 'EUR')) }} @else {{convert_price($base_price * 7, '€', true, array('unit' => 'EUR')) }} @endif</p></td>
                                    </td>
                                </tr>
                            @endforeach
                            <tbody>
                        @endif
                    </table>
                </div>
                <h2 class="heading mt-3 mb-2">{{__('Policies')}}</h2>
                <?php
                $checkIn = $post->checkin_time;
                $checkOut = $post->checkout_time;
                $enableCancellation = $post->enable_cancellation;
                $cancelBeforeDay = (int)$post->cancel_before;
                $cancellationDetail = $post->cancellation_detail;
                ?>
                @if($checkIn)
                    <div class="item d-inline-block mr-4 mb-3">
                        <span class="font-weight-bold"><i class=" ti-time mr-1"></i>{{__('Check-in Time:')}}</span>
                        <span class="ml-2">{{ $checkIn }}</span>
                    </div>
                @endif
                @if ($checkOut)
                    <div class="item d-inline-block mr-4 mb-3">
                        <span class="font-weight-bold"><i class=" ti-time mr-1"></i>{{__('Check-out Time:')}}</span>
                        <span class="ml-2">{{ $checkOut }}</span>
                    </div>
                @endif
                <div class="w-100"></div>
                @if ($enableCancellation == 'on')
                    <div class="item d-inline-block mr-4 mb-3">
                        <span class="font-weight-bold">{{__('Cancellation:')}}</span>
                        <span class="ml-2 small-info bg-success">{{__('enable')}}</span>
                        <span class="d-inline-block ml-1">{{ sprintf(__('before %s day(s)'), $cancelBeforeDay) }}</span>
                    </div>
                    @if (get_translate($cancellationDetail))
                        <div class="w-100">{!! balanceTags(get_translate($cancellationDetail)) !!}</div>
                    @endif
                @endif
                <h2 class="heading mt-3 mb-2">{{__('Availability')}}</h2>
                <div class="hh-availability-wrapper">
                    <div class="hh-availability">
                        <input type="hidden" class="calendar_input"
                               data-id="{{$post->post_id}}"
                               data-encrypt="{{hh_encrypt($post->post_id)}}"
                               data-action="{{ url('get-inventory-home') }}"
                               data-date-format="{{hh_date_format_moment()}}">
                    </div>
                </div>
                @if($post->video)
                    <h2 class="heading mt-3 mb-2">{{__('Video')}}</h2>
                    <div class="video-wrapper">
                        {!! balanceTags(get_video_embed_url(get_translate($post->video))) !!}
                    </div>
                @endif
                <h2 class="heading mt-3 mb-2">{{__('On Map')}}</h2>
                <?php
                $lat = $post->location_lat;
                $lng = $post->location_lng;
                $zoom = $post->location_zoom;

                enqueue_style('mapbox-gl-css');
                enqueue_style('mapbox-gl-geocoder-css');
                enqueue_script('mapbox-gl-js');
                enqueue_script('mapbox-gl-geocoder-js');
                ?>
                <div class="hh-mapbox-single" data-lat="{{ $lat }}"
                     data-lng="{{ $lng }}" data-zoom="{{ $zoom }}"></div>
                <?php
                $author = get_user_by_id($post->author);
                $address = $author->address;
                $location = $author->location;
                $country = get_country_by_code($location);
                $description = $author->description;
                $video = $author->video;
                ?>
                <div class="w-100 mt-3"></div>
                <div class="hosted-author">
                    <div class="media">
                        <img src="{{ get_user_avatar($post->author, [64, 64]) }}" alt="{{ __('User Avatar') }}"
                             class="avatar rounded-circle mr-3">
                        <div class="media-body">
                            <h2 class="heading mt-0 mb-1">{{sprintf(__('Hosted by %s'), get_username($author->getUserId()) )}}</h2>
                            @if(!empty($address) || !empty($location))
                                <p class="location-author d-flex align-items-center">
                                    @if(!empty($address)) {{$address}} @endif
                                    @if(!empty($location)), {{ $country['name'] }} <span
                                        class="ml-1">{!! $country['flag24'] !!}</span> @endif
                                    <span class="d-none d-sm-inline-block"><span class="dot"></span>{{ sprintf(__('Joined in %s'), date(hh_date_format(), strtotime($author->created_at))) }}</span>
                                </p>
                            @endif
                        </div>
                        <div style="flex:2;">
                            <img src="{{asset('images/verified_stamp.jpg')}}" style="width:100px;"></img>
                        </div>
                        
                    </div>
                    @if(!empty($description))
                        <div class="clearfix mt-2">
                            {!! balanceTags(nl2br($description)) !!}
                        </div>
                    @endif
                    @if(!empty($video))
                        <div class="clearfix mt-2">
                            <?php echo preg_replace("/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i", "<iframe src=\"//www.youtube.com/embed/$2\" allowfullscreen width='100%' height='500'></iframe>",$video); ?>
                        </div>
                    @endif
                    <?php do_action('hh_owner_information'); ?>
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-sidebar">
                <?php
                enqueue_style('daterangepicker-css');
                enqueue_script('daterangepicker-js');
                enqueue_script('daterangepicker-lang-js');
                ?>
                <?php
                $booking_form = $post->booking_form;
                $text_external_link = $post->text_external_link;
                $external_link = $post->use_external_link;
                ?>
                <div id="form-book-home" class="form-book"
                     data-real-price="{{ url('get-home-price-realtime') }}">
                    <div class="popup-booking-form-close">{!! get_icon('001_close', '#FFFFFF', '28px', '28px') !!}</div>
                    <div class="form-head">
                        <div class="price-wrapper">
                            <span class="price">{{ convert_price($post->base_price, '€', true, array('unit' => 'EUR')) }}</span>
                            @if($post->booking_type != 'external_link')
                                <span class="unit">/{{$post->unit}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-body relative">
                        @include('common.loading', ['class' => 'booking-loading'])
                        @if($booking_form == 'instant_enquiry')
                            <ul class="nav nav-tabs nav-bordered row">
                                <li class="nav-item nav-item-booking-form-instant col">
                                    <a href="#booking-form-instant"
                                       data-toggle="tab"
                                       aria-expanded="false"
                                       class="nav-link @if($booking_form == 'instant_enquiry' ||$booking_form == 'instant') active @endif">
                                        @if($post->booking_type == 'external_link')
                                            {{ __('External') }}
                                        @else
                                            {{ __('Instant') }}
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item nav-item-booking-form-instant col">
                                    <a href="#booking-form-enquiry"
                                       data-toggle="tab"
                                       aria-expanded="false"
                                       class="nav-link @if($booking_form == 'enquiry') active @endif">
                                        {{ __('Enquiry') }}
                                    </a>
                                </li>
                            </ul>
                        @endif
                        @if($booking_form == 'instant_enquiry')
                            <div class="tab-content">
                                @endif
                                @if($booking_form == 'instant_enquiry' || $booking_form == 'instant')
                                    <div
                                        class="tab-pane @if($booking_form == 'instant_enquiry' ||$booking_form == 'instant') active @endif"
                                        id="booking-form-instant">
                                        @if($post->booking_type == 'external_link')
                                            @include('frontend.home.external-form')
                                        @else
                                            @include('frontend.home.booking-form')
                                        @endif
                                    </div>
                                @endif
                                @if($booking_form == 'instant_enquiry' || $booking_form == 'enquiry')
                                    <div class="tab-pane @if($booking_form == 'enquiry') active @endif"
                                         id="booking-form-enquiry">
                                        <form action="{{ url('home-send-enquiry-form') }}" data-google-captcha="yes"
                                              data-validation-id="form-send-enquiry"
                                              class="form-action form-sm has-reset" data-loading-from=".form-body">
                                            <div class="form-group">
                                                <label for="full-name-enquiry-form">{{ __('Full Name') }} <span
                                                        class="text-danger">*</span></label>
                                                <input id="full-name-enquiry-form" type="text" name="name"
                                                       class="form-control has-validation" data-validation="required">
                                            </div>
                                            <div class="form-group">
                                                <label for="email-enquiry-form">{{ __('Email') }} <span
                                                        class="text-danger">*</span></label>
                                                <input id="email-enquiry-form" type="email" name="email"
                                                       class="form-control has-validation"
                                                       data-validation="required|email">
                                            </div>
                                            <div class="form-group">
                                                <label for="message-enquiry-form">{{ __('Message') }} <span
                                                        class="text-danger">*</span></label>
                                                <textarea id="message-enquiry-form" class="form-control has-validation"
                                                          name="message" data-validation="required"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-primary btn-block text-uppercase"
                                                       name="sm"
                                                       value="{{ __('Send a Request') }}">
                                            </div>
                                            <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                                            <input type="hidden" name="post_encrypt"
                                                   value="{{ hh_encrypt($post->post_id) }}">
                                            <div class="form-message"></div>
                                        </form>
                                    </div>
                                @endif
                                @if($booking_form == 'instant_enquiry')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <?php
        $lat = $post->location_lat;
        $lng = $post->location_lng;
        $list_services = \App\Controllers\Services\HomeController::get_inst()->listOfHomes([
            'number' => 4,
            'location' => [
                'lat' => $lat,
                'lng' => $lng,
                'radius' => 25
            ],
            'orderby' => 'distance',
            'order' => 'asc',
            'not_in' => [$post->post_id]
        ]);
        ?>
        @if(count($list_services['results']))
            <h2 class="heading mt-3 mb-2">{{__('Homes Near By')}}</h2>
            <div class="hh-list-of-services">
                <div class="row">
                    @foreach($list_services['results'] as $item)
                        <div class="col-12 col-md-6 col-lg-3">
                            @include('frontend.home.loop.grid', ['item' => $item, 'show_distance' => true])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @if(enable_review())
            <div class="row">
                <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-content">
                    @include('frontend.home.review')
                </div>
            </div>
        @endif
    </div>
    <div class="mobile-book-action">
        <div class="container">
            <div class="action-inner">
                <div class="action-price-wrapper">
                    <span class="price">{{ convert_price($post->base_price, '€', true, array('unit' => 'EUR')) }}</span>
                    <span class="unit">/{{$post->unit}}</span>
                </div>
                <a class="btn btn-primary action-button" id="mobile-check-availability">{{__('Check Availability')}}</a>
            </div>
        </div>
    </div>
</div>
@include('frontend.components.footer')
