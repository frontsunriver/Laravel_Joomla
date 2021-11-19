@include('frontend.components.header')
<?php
enqueue_style('home-slider');
enqueue_script('home-slider');

enqueue_style('mapbox-gl-css');
enqueue_style('mapbox-gl-geocoder-css');
enqueue_script('mapbox-gl-js');
enqueue_script('mapbox-gl-geocoder-js');

enqueue_style('daterangepicker-css');
enqueue_script('daterangepicker-js');
enqueue_script('daterangepicker-lang-js');

enqueue_style('iconrange-slider');
enqueue_script('iconrange-slider');

enqueue_script('owl-carousel');
enqueue_style('owl-carousel');
enqueue_style('owl-carousel-theme');

?>
<div class="home-page pb-5">
    <div class="hh-search-form-wrapper">
        <div class="ots-slider-wrapper" data-style="full-screen" data-slider="ots-slider">
            <div class="ots-slider">
                <?php
                $sliders = get_option('home_slider');
                $sliders = explode(',', $sliders);
                ?>
                @if(!empty($sliders) && is_array($sliders))
                    @foreach($sliders as $id)
                        <?php
                        $url = get_attachment_url($id);
                        ?>
                        <div class="item">
                            <div class="outer has-background-image" data-src="{{ $url }}"
                                 style="background-image: url('{{ $url }}')"></div>
                            <div class="inner">
                                <div class="img has-background-image" data-src="{{ $url }}"
                                     style="background-image: url('{{ $url }}');"></div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="hh-search-form-section">
            <div class="container">
                <div class="hh-search-form">
                    @include('frontend.experience.search.search-form')
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- Experience Types -->
        <?php
        $experience_types = get_terms('experience-type', true);
        ?>
        @if(count($experience_types) > 0)
            <h2 class="h3 mt-4">{{__('Find a Experience type')}}</h2>
            <div class="hh-list-terms mt-3">
                @if(count($experience_types))
                    <?php
                    $responsive = [
                        0 => [
                            'items' => 1
                        ],
                        768 => [
                            'items' => 2
                        ],
                        992 => [
                            'items' => 3
                        ],
                        1200 => [
                            'items' => 4
                        ]
                    ];
                    ?>
                    <div class="hh-carousel carousel-padding nav-style2"
                         data-responsive="{{ base64_encode(json_encode($responsive)) }}" data-margin="15" data-loop="0">
                        <div class="owl-carousel">
                            @foreach($experience_types as $item)
                                <?php
                                $url = get_attachment_url($item->term_image, [350, 300]);
                                ?>
                                <div class="item">
                                    <div class="hh-term-item">
                                        <a href="{{ get_experience_search_page('?experience-type=' . $item->term_id) }}">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="thumbnail has-matchHeight">
                                                        <div class="thumbnail-outer">
                                                            <div class="thumbnail-inner">
                                                                <img src="{{ $url }}"
                                                                     alt="{{ get_attachment_alt($item->term_image ) }}"
                                                                     class="img-fluid">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 d-flex align-items-center">
                                                    <div class="clearfix">
                                                        <h4>{{ get_translate($item->term_title) }}</h4>
                                                        <?php
                                                        $home_count = count_experience_in_experience_type($item->term_id);
                                                        ?>
                                                        <p class="text-muted">{{ sprintf(__('%s Experiences'), $home_count) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="owl-nav">
                            <a href="javascript:void(0)"
                               class="prev"><i class="ti-angle-left"></i></a>
                            <a href="javascript:void(0)"
                               class="next"><i class="ti-angle-right"></i></a>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    <!--Featured Experiences -->
        @if(is_enable_service('experience'))
            <?php
            $list_services = \App\Controllers\Services\ExperienceController::get_inst()->listOfExperiences([
                'number' => 4,
                'is_featured' => 'on'
            ]);
            ?>
            @if(count($list_services['results']))
                <h2 class="h3 mt-4">{{__('Featured Experiences')}}</h2>
                <p>{{__('Every experience is reviewed for unique access.')}}</p>
                <div class="hh-list-of-services">
                    <?php
                    $responsive = [
                        0 => [
                            'items' => 1
                        ],
                        768 => [
                            'items' => 2
                        ],
                        992 => [
                            'items' => 3
                        ],
                        1200 => [
                            'items' => 4
                        ],
                    ];
                    ?>
                    <div class="hh-carousel carousel-padding nav-style2"
                         data-responsive="{{ base64_encode(json_encode($responsive)) }}" data-margin="15" data-loop="0">
                        <div class="owl-carousel">
                            @foreach($list_services['results'] as $item)
                                <?php $item = setup_post_data($item, 'experience'); ?>
                                <div class="item">
                                    @include('frontend.experience.loop.grid', ['item' => $item])
                                </div>
                            @endforeach
                        </div>
                        <div class="owl-nav">
                            <a href="javascript:void(0)"
                               class="prev"><i class="ti-angle-left"></i></a>
                            <a href="javascript:void(0)"
                               class="next"><i class="ti-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
    <!-- Call to action -->
    <?php
    $page_id = get_option('experience_call_to_action_page');
    $cta_background_id = get_option('experience_call_to_action_background', '');
    ?>
    @if(!empty($page_id))
        <?php
        $link = get_permalink_by_id($page_id, 'page');
        $cta_background_url = get_attachment_url($cta_background_id, 'full');
        ?>
        <div class="container mt-4">
            <div class="call-to-action pl-4 pr-4 has-background-image" data-src="{{$cta_background_url}}"
                 style="background-image: url('{{$cta_background_url}}')">
                <div class="row">
                    <div class="col-lg-8">
                        <h5 class="main-text">{{__('The most exciting trip this summer')}}</h5>
                        <p class="sub-text">{{__('Enjoy moments at the beach Maldives with friends')}}</p>
                    </div>
                    <div class="col-lg-4">
                        <a href="{{ $link }}" class="btn btn-primary right">{{__('Watch now')}}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container">
        <!-- Destination -->
        <?php
        $locations = get_option('experience_top_destination');
        ?>
        @if(!empty($locations))
            <h2 class="h3 mt-4">{{__('Top destinations')}}</h2>
            <p>{{__('Book activities led by local hosts on your next trip')}}</p>
            <div class="hh-list-destinations">
                <?php
                $responsive = [
                    0 => [
                        'items' => 1
                    ],
                    768 => [
                        'items' => 2
                    ],
                    992 => [
                        'items' => 3
                    ],
                ];
                ?>
                <div class="hh-carousel carousel-padding nav-style2"
                     data-responsive="{{ base64_encode(json_encode($responsive)) }}" data-margin="15" data-loop="0">
                    <div class="owl-carousel">
                        @foreach($locations as $location)
                            <?php
                            $lat = $location['lat'];
                            $lng = $location['lng'];
                            $address = get_translate($location['name']);
                            $location_query = [
                                'lat' => $lat,
                                'lng' => $lng,
                                'address' => urlencode($address),
                            ];
                            $location_url = get_search_page('experience');
                            $location_url = add_query_arg($location_query, $location_url);
                            $rand = rand(1, 6);
                            ?>
                            <div class="item">
                                <div class="hh-destination-item">
                                    <a href="{{ $location_url }}">
                                        <div class="thumbnail has-matchHeight">
                                            <div class="thumbnail-outer">
                                                <div class="thumbnail-inner">
                                                    <img src="{{ get_attachment_url($location['image']) }}"
                                                         alt="{{ get_attachment_alt($location['image'] ) }}"
                                                         class="img-fluid">
                                                </div>
                                            </div>
                                            <div class="detail">
                                                <h2 class="text-center des-paterm-{{$rand}}">{{ $address }}</h2>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="owl-nav">
                        <a href="javascript:void(0)"
                           class="prev"><i class="ti-angle-left"></i></a>
                        <a href="javascript:void(0)"
                           class="next"><i class="ti-angle-right"></i></a>
                    </div>
                </div>
            </div>
        @endif
    <!-- Experience in Ha Noi -->
        @if(is_enable_service('experience'))
            <?php
            $list_services = \App\Controllers\Services\ExperienceController::get_inst()->listOfExperiences([
                'number' => 4,
                'location' => [
                    'lat' => '21',
                    'lng' => '105.75',
                    'radius' => 50
                ],
                'order' => 'rand'
            ]);
            ?>
            @if(count($list_services['results']))
                <h2 class="h3 mt-4">{{__('Popular experiences in Ha Noi')}}</h2>
                <p>{{__('Book activities led by local hosts on your next trip')}}</p>
                <div class="hh-list-of-services">
                    <div class="row">
                        @foreach($list_services['results'] as $item)
                            <?php $item = setup_post_data($item, 'experience'); ?>
                            <div class="col-12 col-md-6">
                                @include('frontend.experience.loop.list', ['item' => $item])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
    <!-- Testimonial -->
    <?php
    $testimonials = get_option('testimonial', []);
    $responsive = [
        0 => [
            'items' => 1
        ],
        768 => [
            'items' => 2
        ],
        992 => [
            'items' => 2
        ],
        1200 => [
            'items' => 3
        ],
    ];
    $testimonial_bgr = get_option('testimonial_background', '#dd556a');
    ?>
    @if(count($testimonials))
        <div class="section section-background pt-5 pb-5 mt-4" style="background-color: {{$testimonial_bgr}};">
            <div class="container">
                <h2 class="h3 mt-0 c-white">{{__('Say about Us')}}</h2>
                <p class="c-white">{{__('Browse beautiful places to stay with all the comforts of home, plus more')}}</p>
                <div class="hh-testimonials">
                    <div class="hh-carousel carousel-padding nav-style2"
                         data-responsive="{{ base64_encode(json_encode($responsive)) }}" data-margin="30" data-loop="0">
                        <div class="owl-carousel">
                            @foreach($testimonials as $testimonial)
                                <div class="item">
                                    <div class="testimonial-item">
                                        <div class="testimonial-inner">
                                            <div class="author-avatar">
                                                <img
                                                    src="{{ get_attachment_url($testimonial['author_avatar'], [80, 80]) }}"
                                                    alt="{{ get_translate($testimonial['author_name']) }}" class="img-fluid">
                                                <i class="mdi mdi-format-quote-open hh-icon"></i>
                                            </div>
                                            <div class="author-rate">
                                                @include('frontend.components.star', ['rate' => (int) $testimonial['author_rate']])
                                            </div>
                                            <div class="author-comment">
                                                {{ get_translate($testimonial['author_comment']) }}
                                            </div>
                                            <h2 class="author-name">
                                                {{ get_translate($testimonial['author_name']) }}
                                            </h2>
                                            @if($testimonial['date'])
                                                <div class="author-date">{{sprintf(__('on %s'), date(hh_date_format(), strtotime($testimonial['date'])))}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="owl-nav">
                            <a href="javascript:void(0)"
                               class="prev"><i class="ti-angle-left"></i></a>
                            <a href="javascript:void(0)"
                               class="next"><i class="ti-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endif
<!-- List of Blog -->
    <div class="container">
        <?php
        $list_services = \App\Controllers\PostController::get_inst()->listOfPosts([
            'number' => 2
        ]);
        $responsive = [
            0 => [
                'items' => 1
            ]
        ];
        ?>
        @if(count($list_services['results']))
            <h2 class="h3 mt-4 mb-3">{{__('The latest from Blog')}}</h2>
            <div class="hh-list-of-blog">
                <div class="row">
                    @foreach($list_services['results'] as $item)
                        <div class="col-12 col-md-6">
                            <div class="hh-blog-item style-2">
                                <a href="{{ get_the_permalink($item->post_id, $item->post_slug, 'post') }}">
                                    <div class="thumbnail">
                                        <div class="thumbnail-outer">
                                            <div class="thumbnail-inner">
                                                <img src="{{ get_attachment_url($item->thumbnail_id, 'full') }}"
                                                     alt="{{ get_attachment_alt($item->thumbnail_id ) }}"
                                                     class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="category">{{__('Action')}}
                                    <div class="date">{{ date(hh_date_format(), $item->created_at) }}</div>
                                </div>
                                <h2 class="title"><a
                                        href="{{ get_the_permalink($item->post_id, $item->post_slug, 'post') }}">{{ get_translate($item->post_title) }}</a>
                                </h2>
                                <div
                                    class="description">{!! balanceTags(short_content(get_translate($item->post_content), 55)) !!}</div>
                                <div class="w-100 mt-2"></div>
                                <div class="d-flex justify-content-between">
                                    <?php
                                    $url = get_the_permalink($item->post_id, $item->post_slug, 'post');
                                    $img = get_attachment_url($item->thumbnail_id);
                                    $desc = get_translate($item->post_title);

                                    $share = [
                                        'facebook' => [
                                            'url' => $url
                                        ],
                                        'twitter' => [
                                            'url' => $url
                                        ],
                                        'pinterest' => [
                                            'url' => $url,
                                            'img' => $img,
                                            'description' => $desc
                                        ]
                                    ];
                                    ?>
                                    @include('frontend.components.share', ['share' => $share])
                                    <a href="{{ get_the_permalink($item->post_id, $item->post_slug, 'post') }}"
                                       class="read-more">{{__('Keep Reading')}} {!! balanceTags(get_icon('002_right_arrow', '#F8546D', '12px', '')) !!}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@include('frontend.components.footer')
