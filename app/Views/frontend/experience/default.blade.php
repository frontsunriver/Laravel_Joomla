@include('frontend.components.header')
<?php
enqueue_script('scroll-magic-js');
global $post;
$booking_form = $post->booking_form;
?>
<div class="single-page single-experience pb-5">
    <div class="container">
    @include('frontend.components.service_breadcrumb', ['post_type' => 'experience'])
    <!-- Gallery -->
        <?php
        $gallery = $post->gallery;
        $thumbnail_id = get_experience_thumbnail_id($post->post_id);
        $thumbnailUrl = get_attachment_url($thumbnail_id, 'full');
        ?>
        <div class="hh-gallery hh-grid-gallery">
            <div class="controls">
                <a href="javascript: void(0);" class="view-gallery item-link"><span>{{__('View Photos')}}</span> <i
                        class="ti-gallery"></i> </a>
            </div>
            <?php
            if ( !empty($gallery) ) {
            enqueue_script('light-gallery-js');
            enqueue_style('light-gallery-css');

            $gallery = explode(',', $gallery);
            $data = [];
            $i = 0;
            foreach ( $gallery as $key => $val ) {
            $thumbnail = get_attachment_url($val, [500, 750]);
            if(in_array($i, [0, 1, 4])){
            ?>
            <div class="item">
                <div class="item-inner">
                    <img src="{{$thumbnail}}" alt="{{ get_attachment_alt($val) }}">
                </div>
            </div>
            <?php
            }elseif($i == 2 || $i == 3){
            if($i == 2){
            ?>
            <div class="item item-small">
                <div class="item-outer">
                    <div class="item-inner">
                        <img src="{{$thumbnail}}" alt="{{ get_attachment_alt($val) }}">
                    </div>
                </div>
                <div class="space"></div>
                <?php
                }elseif($i == 3){
                ?>
                <div class="item-outer">
                    <div class="item-inner">
                        <img src="{{$thumbnail}}" alt="{{ get_attachment_alt($val) }}">
                    </div>
                </div>
            </div>
            <?php
            }
            }
            $url = get_attachment_url($val);
            if (!empty($url)) {
                $data[] = [
                    'src' => $url
                ];
            }

            $i++;
            }
            if (!empty($data)) {
                $data = base64_encode(json_encode($data));
                echo '<div class="data-gallery" data-gallery="' . esc_attr($data) . '"></div>';
            }
            }
            ?>
        </div>
        <div class="row">
            <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-content">
                <div class="row">
                    <div class="col-12 col-xl-4">
                        <h1 class="title">
                            @if($post->is_featured == 'on')
                                <span class="is-featured featured-icon"
                                      title="{{__('Featured')}}">{!! balanceTags(get_icon('001_diamond')) !!}</span>
                            @endif
                            {{ get_translate($post->post_title) }}
                        </h1>
                        @if ($post->location_address)
                            <p class="location mb-1">
                                <i class="ti-location-pin"></i>
                                {{ get_translate($post->location_address) }}
                            </p>
                        @endif
                        <div class="review-summary">
                            <?php
                            $rate = $post->review_count;
                            ?>
                            <div class="count-reviews">
                                {{ number_format(round((float)$post->rating, 1), 1) }} <i class="fas fa-star"></i> <span
                                    class="count">{{ _n("[0::(%s reviews)][1::(%s review)][2::(%s reviews)]", $rate) }}</span>
                            </div>

                        </div>
                    </div>
                    <div class="col-12 col-xl-8">
                        <div class="tour-featured">
                            <div class="row">
                                <div class="col-6 col-md-6 col-lg-6 col-xl-3">
                                    <div class="item mb-2">
                                        {!! get_icon('009_sunbed', '#4a4a4a') !!}
                                        <div class="title">{{__('Duration')}}</div>
                                        <div class="desc">{{ get_translate($post->durations) }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-6 col-lg-6 col-xl-3">
                                    <div class="item mb-2">
                                        {!! get_icon('ico_child', '#4a4a4a') !!}
                                        <div class="title">{{__('Group size')}}</div>
                                        <?php
                                        $max_people = (float)$post->number_of_guest;
                                        ?>
                                        @if($max_people == -1)
                                            <div class="desc">{{ __('Unlimited')}}</div>
                                        @else
                                            <div
                                                class="desc">{{ _n("[0::%s people][1::%s person][2::%s people]", $max_people)}}</div>
                                        @endif
                                    </div>
                                </div>
                                @if($post->experience_type)
                                    <?php
                                    $tour_type = get_term_by('id', $post->experience_type);
                                    ?>
                                    @if(!is_null($tour_type))
                                        <div class="col-6 col-md-6 col-lg-6 col-xl-3">
                                            <div class="item mb-2">
                                                {!! get_icon('001_tour', '#4a4a4a') !!}
                                                <div class="title">{{__('Type')}}</div>
                                                <div class="desc">{{ get_translate($tour_type->term_title) }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                <div class="col-6 col-md-6 col-lg-6 col-xl-3">
                                    <div class="item mb-2">
                                        {!! get_icon('001_language', '#4a4a4a') !!}
                                        <div class="title">{{__('Language')}}</div>
                                        <?php

                                        $language = $post->languages;
                                        $language_return = '';
                                        ?>
                                        @if(empty($language))
                                            <div class="desc">{{ __('Not set') }}</div>
                                        @else
                                            <?php
                                            $language = explode(',', $language);
                                            foreach ($language as $lang) {
                                                $term = get_term_by('id', $lang);
                                                if (!is_null($term)) {
                                                    $language_return .= get_translate($term->term_title) . ', ';
                                                }
                                            }
                                            if (!empty($language_return)) {
                                                $language_return = substr($language_return, 0, -2);
                                            }
                                            ?>
                                        @endif
                                        <div class="desc">
                                            @if(!empty($language_return))
                                                {{$language_return}}
                                            @else
                                                {{ __('Not set') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 col-md-4">
                        <h2 class="heading mt-0 mb-2">{{__('What you will do')}}</h2>
                    </div>
                    <div class="col-12 col-md-8">
                        {!! balanceTags(get_translate($post->post_content)) !!}
                    </div>
                </div>

                <?php
                $author = get_user_by_id($post->author);
                $description = $author->description;
                ?>
                <div class="row mt-4">
                    <div class="col-12 col-md-4">
                        <h2 class="heading mt-0 mb-2">{{__('Your host')}}</h2>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="hosted-author">
                            <img src="{{ get_user_avatar($post->author, [64, 64]) }}" alt="{{ __('User Avatar') }}"
                                 class="avatar rounded-circle">
                            <h2 class="h4"> {{get_username($author->getUserId())}}</h2>
                            @if(!empty($description))
                                <div class="hr mt-0"></div>
                                <div class="clearfix">
                                    {!! balanceTags(nl2br($description)) !!}
                                </div>
                            @endif
                            <?php do_action('hh_owner_information') ?>
                        </div>
                    </div>
                </div>
                @if($post->itinerary)
                    <div class="row mt-4">
                        <div class="col-12 col-md-4">
                            <h2 class="heading mt-0 mb-2">{{__('Your Itinerary')}}</h2>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="itinerary-tour">
                                @foreach($post->itinerary as $item)
                                    <div class="item">
                                        <div class="d-block d-sm-flex align-items-center">
                                            <div class="sub-title">{{ get_translate($item['sub_title']) }}</div>
                                            <h2 class=" title">{{ get_translate($item['title']) }}</h2>
                                        </div>
                                        <div class="desc">
                                            @if($item['image'])
                                                <?php
                                                $image_url = get_attachment_url($item['image']);
                                                $image_alt = get_attachment_alt($item['image']);
                                                ?>
                                                <img src="{{$image_url}}" class="img-fluid" alt="{{$image_alt}}">
                                            @endif
                                            {!! balanceTags(get_translate($item['description'])) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <?php
                $inclusions = $post->inclusions;
                ?>
                <div class="row mt-4">
                    <div class="col-12 col-md-4">
                        <h2 class="heading heading-line  mt-0 mb-2">{{__('Inclusions')}}</h2>
                    </div>
                    <div class="col-12 col-md-8">
                        <?php
                        if ($inclusions) {
                        $inclusions = explode(',', $inclusions);
                        ?>
                        <div class="inclusions">
                            <div class="row">
                                <?php
                                foreach ($inclusions as $item) {
                                $term = get_term_by('id', $item);
                                ?>
                                @if(!is_null($term ))
                                    <div class="col-6">
                                        <div class="item">
                                            <div class="label">{{ get_translate($term->term_title) }}</div>
                                            @if($term->term_description)
                                                <div
                                                    class="desc">{!! balanceTags(get_translate($term->term_description)) !!}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        } else {
                        ?>
                        <p>{{__('Not set')}}</p>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                $exclusions = $post->exclusions;
                ?>
                <div class="row mt-4">
                    <div class="col-12 col-md-4">
                        <h2 class="heading heading-line mt-0 mb-2">{{__('Exclusions')}}</h2>
                    </div>
                    <div class="col-12 col-md-8">
                        <?php
                        if ($exclusions) {
                        $exclusions = explode(',', $exclusions);
                        ?>
                        <div class="inclusions">
                            <div class="row">
                                <?php
                                foreach ($exclusions as $item) {
                                $term = get_term_by('id', $item);
                                ?>
                                @if(!is_null($term))
                                    <div class="col-6">
                                        <div class="item">
                                            <div class="label">{{ get_translate($term->term_title) }}</div>
                                            @if($term->term_description)
                                                <div
                                                    class="desc">{!! balanceTags(get_translate($term->term_description)) !!}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        } else {
                        ?>
                        <p>{{__('Not set')}}</p>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                $enableCancellation = $post->enable_cancellation;
                $cancelBeforeDay = (int)$post->cancel_before;
                $cancellationDetail = $post->cancellation_detail;
                ?>
                @if ($enableCancellation == 'on')
                    <div class="row mt-4">
                        <div class="col-12 col-md-4">
                            <h2 class="heading mt-0 mb-2">{{__('Policies')}}</h2>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="item">
                                <span class="font-weight-bold">{{__('Cancellation:')}}</span>
                                <span class="ml-2 small-info bg-success">{{__('enable')}}</span>
                                <span
                                    class="d-inline-block ml-1">{{ sprintf(__('before %s day(s)'), $cancelBeforeDay) }}</span>
                            </div>
                            @if (get_translate($cancellationDetail))
                                <div class="w-100 mt-1">{!! balanceTags(get_translate($cancellationDetail)) !!}</div>
                            @endif
                        </div>
                    </div>
                @endif
                @if($post->video)
                    <div class="row mt-4">
                        <div class="col-12 col-md-4">
                            <h2 class="heading mt-0 mb-2">{{__('Video')}}</h2>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="video-wrapper">
                                {!! balanceTags(get_video_embed_url(get_translate($post->video))) !!}
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row mt-4">
                    <div class="col-12 col-md-4">
                        <h2 class="heading mt-0 mb-2">{{__('On Map')}}</h2>
                    </div>
                    <div class="col-12 col-md-8">
                        <?php
                        $lat = $post->location_lat;
                        $lng = $post->location_lng;
                        $zoom = $post->location_zoom;

                        enqueue_style('mapbox-gl-css');
                        enqueue_style('mapbox-gl-geocoder-css');
                        enqueue_script('mapbox-gl-js');
                        enqueue_script('mapbox-gl-geocoder-js');
                        ?>
                        <div class="hh-mapbox-single" data-lat="{{ $lat }}" data-type="experience"
                             data-lng="{{ $lng }}" data-zoom="{{ $zoom }}"></div>
                    </div>
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
                ?>
                <div id="form-book-experience" class="form-book"
                     data-real-price="{{ url('get-experience-price-realtime') }}">
                    <div class="popup-booking-form-close">{!! get_icon('001_close', '#FFFFFF', '28px', '28px') !!}</div>
                    <div class="form-head">
                        <div class="price-wrapper">
                            <span class="prefix">{{__('From')}}</span>
                            <span class="price">{{ convert_price($post->base_price) }}</span>
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
                                            @include('frontend.experience.external-form')
                                        @else
                                            @include('frontend.experience.booking-form')
                                        @endif
                                    </div>
                                @endif
                                @if($booking_form == 'instant_enquiry' || $booking_form == 'enquiry')
                                    <div class="tab-pane @if($booking_form == 'enquiry') active @endif"
                                         id="booking-form-enquiry">
                                        <form action="{{ url('experience-send-enquiry-form') }}"
                                              data-google-captcha="yes"
                                              data-validation-id="form-enquiry"
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
        $list_services = \App\Controllers\Services\ExperienceController::get_inst()->listOfExperiences([
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
            <h2 class="heading mt-4 mb-3">{{__('Experiences Near By')}}</h2>
            <div class="hh-list-of-services list-experience">
                <div class="row">
                    @foreach($list_services['results'] as $item)
                        <?php $item = setup_post_data($item, 'experience'); ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            @include('frontend.experience.loop.grid', ['item' => $item, 'show_distance' => true])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @if(enable_review())
            <div class="row mt-3">
                <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-content">
                    @include('frontend.experience.review')
                </div>
            </div>
        @endif
    </div>
    <div class="mobile-book-action">
        <div class="container">
            <div class="action-inner">
                <div class="action-price-wrapper">
                    <span class="price">{{ convert_price($post->base_price) }}</span>
                    <span class="unit">/{{$post->unit}}</span>
                </div>
                <a class="btn btn-primary action-button" id="mobile-check-availability">{{__('Check Availability')}}</a>
            </div>
        </div>
    </div>
</div>
@include('frontend.components.footer')
