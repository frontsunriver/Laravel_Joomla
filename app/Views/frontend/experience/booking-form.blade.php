<?php
global $post;
?>
<form class="form-action" action="{{ url('add-to-cart-experience') }}"
      data-validation-id="form-add-cart"
      data-get-total="{{url('get-total-price-experience')}}"
      method="post"
      data-loading-from=".form-body">
    <?php if ($post->booking_type == 'package') {
    $packages = $post->tour_packages;
    if (!empty($packages)) {
    ?>

    <div class="form-group">
        <label>{{__('Packages')}}</label>
        <?php
        foreach($packages as $key => $package){
        $package_price = $sale_price = $package['price'];
        if (!empty($package['sale_price']) && (float)$package['sale_price'] >= 0) {
            $sale_price = $package['sale_price'];
        }
        $price_html = '<span class="price-html">';
        if ($sale_price < $package_price) {
            $price_html .= '<span class="base-price has-sale">' . convert_price($package_price) . '</span>';
            $price_html .= '<span class="sale-price">' . convert_price($sale_price) . '</span>';
        } else {
            $price_html .= '<span class="base-price">' . convert_price($package_price) . '</span>';
        }
        $price_html .= '</span>';
        ?>
        <div class="package-item mb-2">
            <div class="radio radio-success">
                <input type="radio" name="tour_package"
                       id="tour-package-{{$package['name']}}"
                       value="{{$package['name']}}">
                <label
                    for="tour-package-{{$package['name']}}">{{ get_translate($package['title']) }}
                    - {!! balanceTags($price_html) !!}</label>
            </div>
            <div class="row align-items-center mt-2">
                <div class="col item" data-toggle="tooltip" data-placement="top"
                     title=""
                     data-original-title="{{ _n("[0::No. Adults: %s][1::No. Adult: %s][2::No. Adults: %s]", $package['num_adult']) }}">{{__('AD')}}
                    : <strong>{{(int) $package['num_adult']}}</strong></div>
                <div class="col item" data-toggle="tooltip" data-placement="top"
                     title=""
                     data-original-title="{{ _n("[0::No. Children: %s][1::No. Child: %s][2::No. Children: %s]", $package['num_child']) }}">{{__('CH')}}
                    : <strong>{{(int) $package['num_child']}}</strong></div>
                <div class="col item" data-toggle="tooltip" data-placement="top"
                     title=""
                     data-original-title="{{ _n("[0::No. Infants: %s][1::No. Infant: %s][2::No. Infants: %s]", $package['num_infant']) }}">{{__('IN')}}
                    : <strong>{{(int) $package['num_infant']}}</strong></div>
            </div>
            @if(!empty($package['detail']))
                <div class="package-description">
                    {!! nl2br(balanceTags(get_translate($package['detail']))) !!}
                </div>
            @endif
        </div>
        <?php
        }
        }
        ?>
    </div>
    <?php
    } ?>
    <div class="form-group">
        <div class="d-flex align-items-center justify-content-between">
            <label class="mb-0">{{ __('Date') }} <span
                    class="label-date-render"
                    data-date-format="{{ hh_date_format_moment() }}"></span></label>
            <a href="#date-collapse" class="right" aria-expanded="true"
               data-toggle="collapse">{!! get_icon('002_download_1', '#2a2a2a','15px') !!}</a>
        </div>
        <div id="date-collapse" class="show">
            <div class="date-wrapper {{ $post->booking_type }}"
                 data-date-format="{{ hh_date_format_moment() }}"
                 data-action="{{ url('get-experience-availability-single') }}"
                 data-action-time="{{ url('get-experience-date-time') }}"
                 data-action-guest="{{ url('get-experience-guest') }}">
                <input type="text"
                       class="input-hidden check-in-out-field {{ $post->booking_type }}"
                       name="checkInOut"
                       data-experience-id="{{ $post->post_id }}"
                       data-experience-encrypt="{{ hh_encrypt($post->post_id) }}">
                <input type="text" class="input-hidden check-in-field"
                       name="checkIn">
                <input type="text" class="input-hidden check-out-field"
                       name="checkOut">
            </div>
        </div>
    </div>
    @if($post->booking_type == 'date_time')
        <div class="date-time-render d-none"></div>
    @endif
    <?php
    if($post->booking_type != 'package'){
    $max_guest = (int)$post->number_of_guest;
    ?>
    <div class="form-group">
        <?php
        $price_categories = $post->price_categories;
        ?>
        <label>{{__('Guests')}}</label>
        <div class="guest-group">
            <button type="button" class="btn btn-light dropdown-toggle"
                    data-toggle="dropdown"
                    data-text-guest="{{__('Guest')}}"
                    data-text-guests="{{__('Guests')}}"
                    data-text-infant="{{__('Infant')}}"
                    data-text-infants="{{__('Infants')}}"
                    aria-haspopup="true" aria-expanded="false">
                &nbsp;
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                @if(in_array('enable_adults', $price_categories))
                    <div class="group">
                        <span class="pull-left">{{__('Adults')}}</span>
                        <div class="control-item">
                            <i class="decrease ti-minus"></i>
                            <input type="number" min="1" step="1"
                                   max="{{ $max_guest }}"
                                   name="num_adults"
                                   value="1">
                            <i class="increase ti-plus"></i>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="num_adults" value="0">
                @endif
                @if(in_array('enable_children', $price_categories))
                    <div class="group">
                        <span class="pull-left">{{__('Children')}}</span>
                        <div class="control-item">
                            <i class="decrease ti-minus"></i>
                            <input type="number" min="0" step="1"
                                   max="{{ $max_guest }}"
                                   name="num_children"
                                   value="0">
                            <i class="increase ti-plus"></i>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="num_children" value="0">
                @endif
                @if(in_array('enable_infants', $price_categories))
                    <div class="group">
                        <span class="pull-left">{{__('Infants')}}</span>
                        <div class="control-item">
                            <i class="decrease ti-minus"></i>
                            <input type="number" min="0" step="1"
                                   max="{{ $max_guest }}"
                                   name="num_infants"
                                   value="0">
                            <i class="increase ti-plus"></i>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="num_infants" value="0">
                @endif
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="form-group">
        <?php
        $requiredExtra = $post->required_extra;
        ?>
        @if ($requiredExtra)
            <div class="extra-services">
                <label class="d-block mb-2">
                    {{__('Extra')}}
                    <span
                        class="text-danger f12">{{__('(required)')}}</span>
                    <a href="#extra-collapse" class="right"
                       data-toggle="collapse">{!! get_icon('002_download_1', '#2a2a2a','15px') !!}</a>
                </label>
                <div id="extra-collapse" class="collapse show">
                    @foreach ($requiredExtra as $ex)
                        <div class="extra-item d-flex">
                            <span
                                class="name">{{ get_translate($ex['name']) }}</span>
                            <span
                                class="price ml-auto">{{ convert_price($ex['price']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <?php
        $extra = $post->not_required_extra;
        ?>
        @if ($extra)
            <div class="extra-services">
                <label class="d-block mb-2">
                    <span>{{__('Extra (optional)')}}</span>
                    <a href="#extra-not-required-collapse" class="right"
                       data-toggle="collapse">{!! get_icon('002_download_1', '#2a2a2a','15px') !!}</a>
                </label>
                <div id="extra-not-required-collapse" class="collapse">
                    @foreach ($extra as $ex)
                        <div class="extra-item d-flex">
                            <div class="checkbox checkbox-success">
                                <input
                                    id="extra-service-{{ $ex['name_unique'] }}"
                                    class="input-extra"
                                    type="checkbox" name="extraServices[]"
                                    value="{{ $ex['name_unique'] }}">
                                <label
                                    for="extra-service-{{ $ex['name_unique'] }}">
                                    <span
                                        class="name">{{ get_translate($ex['name']) }}</span>
                                </label>
                            </div>
                            <span
                                class="price ml-auto">{{ convert_price($ex['price']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="form-group form-render">
    </div>
    <div class="form-group mt-2">
        <input type="hidden" name="experienceID" value="{{ $post->post_id }}">
        <input type="hidden" name="experienceEncrypt"
               value="{{ hh_encrypt($post->post_id) }}">
        <input type="hidden" name="bookingType"
               value="{{ $post->booking_type }}">
        <input type="submit"
               class="btn btn-primary btn-block text-uppercase"
               name="sm"
               value="{{__('Book Now')}}">
    </div>
    <div class="form-message"></div>
</form>
