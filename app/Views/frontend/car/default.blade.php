@include('frontend.components.header')
<?php
enqueue_script('scroll-magic-js');
global $post;
?>
<div class="single-page single-car pb-5">
    <!-- Gallery -->
    <?php
    $gallery = $post->gallery;
    $thumbnail_id = get_car_thumbnail_id($post->post_id);
    $thumbnailUrl = get_attachment_url($thumbnail_id, 'full');
    ?>
    <div class="hh-gallery hh-thumbnail has-background-image" data-src="{{ $thumbnailUrl }}"
         style="background-image: url({{ $thumbnailUrl }})">
        <div class="controls">
            <a href="javascript: void(0);" class="view-gallery item-link"><span>{{__('View Photos')}}</span> <i
                    class="ti-gallery"></i> </a>
            @if(!empty($post->video))
                <?php
                enqueue_script('magnific-popup-js');
                enqueue_style('magnific-popup-css');
                ?>
                <a href="{{$post->video}}" class="view-video item-link ml-1 hh-iframe-popup"
                   data-effect="mfp-zoom-in"><span>{{__('View Video')}}</span> <i class="ti-video-clapper"></i> </a>
            @endif
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
                        <h4>{{__('Passenger:')}}</h4>

                        <span> {{ $post->passenger }} </span>
                    </div>
                    <div class="item">
                        <h4>{{__('Gear Shift:')}}</h4>
                        <span>{{ get_translate($post->gear_shift) }}</span>
                    </div>
                    <div class="item">
                        <h4>{{__('Baggage:')}}</h4>
                        <span>{{ $post->baggage }}</span>
                    </div>
                    <div class="item">
                        <h4>{{__('Door:')}}</h4>
                        <span>{{ $post->door }}</span>
                    </div>
                    <?php
                    $car_type_arr = [];
                    $tax_car_types = $post->tax_car_types;
                    if (!empty($tax_car_types) && is_object($tax_car_types)) {
                        foreach ($tax_car_types as $type_object) {
                            array_push($car_type_arr, get_translate($type_object->term_title));
                        }
                    }
                    ?>
                    @if(!empty($car_type_arr))
                        <div class="item">
                            <h4>{{__('Type:')}}</h4>
                            <span>{{ implode(', ', $car_type_arr) }}</span>
                        </div>
                    @endif
                </div>
                <h2 class="heading mt-4 mb-2">{{__('Detail')}}</h2>
                {!! balanceTags(get_translate($post->post_content)) !!}
                <?php
                $features = $post->tax_car_features;
                ?>
                @if (!empty($features) && is_object($features))
                    <h2 class="heading mt-4 mb-2">{{__('Features')}}</h2>
                    <div class="amenities row">
                        @foreach ($features as $feature)
                            <div class="col-6 col-sm-4 col-lg-3">
                                <div class="amenity-item" data-toggle="ots-tooltip"
                                     data-title="{{ get_translate($feature->term_description) }}">
                                    @if (!$feature->term_icon)
                                        <i class="fe-check"></i>
                                    @else
                                        {!! get_icon($feature->term_icon, '#2a2a2a', '30px', '')  !!}
                                    @endif
                                    <span class="title">{{ get_translate($feature->term_title) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <h2 class="heading mt-3 mb-2">{{__('Policies')}}</h2>
                <?php
                $enableCancellation = $post->enable_cancellation;
                $cancelBeforeDay = (int)$post->cancel_before;
                $cancellationDetail = $post->cancellation_detail;
                ?>
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
                <h2 class="heading mt-4 mb-2">{{__('On Map')}}</h2>
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
                    </div>
                    @if(!empty($description))
                        <div class="clearfix mt-2">
                            {!! balanceTags(nl2br($description)) !!}
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
                $checkInOut = request()->get('checkInOut', '');
                $checkIn = request()->get('checkIn', '');
                $checkOut = request()->get('checkOut', '');
                $checkInTime = request()->get('checkInTime', '');
                $checkOutTime = request()->get('checkOutTime', '');
                if (strlen($checkInTime) == 7) {
                    $checkInTime = '0' . $checkInTime;
                }
                if (strlen($checkOutTime) == 7) {
                    $checkOutTime = '0' . $checkOutTime;
                }
                ?>
                <?php
                $booking_form = $post->booking_form;
                $booking_type = get_car_booking_type();
                $enable_external = $post->enable_external;
                ?>
                <div id="form-book-car"
                     class="form-book form-book-car"
                     data-real-price="{{ url('get-car-price-realtime') }}"
                     data-booking-type="{{$booking_type}}">
                    <div class="popup-booking-form-close">{!! get_icon('001_close', '#FFFFFF', '28px', '28px') !!}</div>
                    <div class="form-head">
                        <div class="price-wrapper">
                            <span class="price">{{ convert_price($post->base_price) }}</span>
                            @if($enable_external == 'off')
                                <span class="unit">/{{get_car_unit()}}</span>
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
                                        @if($enable_external == 'on')
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
                                        @if($enable_external == 'on')
                                            @include('frontend.car.external-form')
                                        @else
                                            @include('frontend.car.booking-form')
                                        @endif

                                    </div>
                                @endif
                                @if($booking_form == 'instant_enquiry' || $booking_form == 'enquiry')
                                    <div class="tab-pane @if($booking_form == 'enquiry') active @endif"
                                         id="booking-form-enquiry">
                                        <form action="{{ url('car-send-enquiry-form') }}" data-google-captcha="yes"
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
        $list_services = \App\Controllers\Services\CarController::get_inst()->listOfCars([
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
            <h2 class="heading mt-4 mb-2">{{__('Cars Near By')}}</h2>
            <div class="hh-list-of-services">
                <div class="row">
                    @foreach($list_services['results'] as $item)
                        <div class="col-12 col-md-6 col-lg-3">
                            @include('frontend.car.loop.grid', ['item' => $item, 'show_distance' => true])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @if(enable_review())
            <div class="row">
                <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-content">
                    @include('frontend.car.review')
                </div>
            </div>
        @endif
    </div>
    <div class="mobile-book-action">
        <div class="container">
            <div class="action-inner">
                <div class="action-price-wrapper">
                    <span class="price">{{ convert_price($post->base_price) }}</span>
                    <span class="unit">/{{get_car_unit()}}</span>
                </div>
                <a class="btn btn-primary action-button" id="mobile-check-availability">{{__('Check Availability')}}</a>
            </div>
        </div>
    </div>
</div>
@include('frontend.components.footer')
