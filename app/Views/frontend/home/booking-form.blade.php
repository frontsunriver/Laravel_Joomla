<?php
global $post;
?>
<form class="form-action" action="{{ url('add-to-cart-home') }}" method="post" data-loading-from=".form-body"
      data-validation-id="form-add-cart">
    @if($post->booking_type == 'per_night')
        <div class="form-group">
            <label for="checkinout">{{ __('Check In/Out') }}</label>
            <div class="date-wrapper date-double"
                 data-date-format="{{ hh_date_format_moment() }}"
                 data-action="{{ url('get-home-availability-single') }}">
                <input type="text" class="input-hidden check-in-out-field"
                       name="checkInOut" data-home-id="{{ $post->post_id }}"
                       data-home-encrypt="{{ hh_encrypt($post->post_id) }}">
                <input type="text" class="input-hidden check-in-field"
                       name="checkIn">
                <input type="text" class="input-hidden check-out-field"
                       name="checkOut">
                <span class="check-in-render"
                      data-date-format="DD.MM.YYYY."></span>
                <span class="divider"></span>
                <span class="check-out-render"
                      data-date-format="DD.MM.YYYY."></span>
            </div>
        </div>
    @elseif($post->booking_type == 'per_hour')
        <div class="form-group">
            <label for="checkinout">{{__('Check In')}}</label>
            <div class="date-wrapper date-single"
                 data-date-format="{{ hh_date_format_moment() }}"
                 data-action-time="{{ url('get-home-availability-time-single') }}"
                 data-action="{{ url('get-home-availability-single') }}">
                <input type="text"
                       class="input-hidden check-in-out-single-field"
                       name="checkInOut" data-home-id="{{ $post->post_id }}"
                       data-home-encrypt="{{ hh_encrypt($post->post_id) }}">
                <input type="text" class="input-hidden check-in-field"
                       name="checkIn">
                <input type="text" class="input-hidden check-out-field"
                       name="checkOut">
                <span class="check-in-render"
                      data-date-format="{{ hh_date_format_moment() }}"></span>
            </div>
        </div>
        <div class="form-group form-group-date-time d-none">
            <label>{{ __('Time') }}</label>
            <div class="date-wrapper date-time">
                <div class="date-render check-in-render"
                     data-time-format="{{ hh_time_format() }}">
                    <div class="render">{{__('Start Time')}}</div>
                    <div class="dropdown-time">

                    </div>
                    <input type="hidden" name="startTime" value=""
                           class="input-checkin"/>
                </div>
                <span class="divider"></span>
                <div class="date-render check-out-render"
                     data-time-format="{{ hh_time_format() }}">
                    <div class="render">{{__('End Time')}}</div>
                    <div class="dropdown-time">

                    </div>
                    <input type="hidden" name="endTime" value=""
                           class="input-checkin"/>
                </div>
            </div>
        </div>
    @endif
    <?php
    $max_guest = (int)$post->number_of_guest;
    ?>
    <div class="form-group">
        <label for="guest">{{__('Guests')}}</label>
        <div
            class="guest-group @if($post->enable_extra_guest == 'on') has-extra-guest @endif">
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
            </div>
        </div>
    </div>
    <div class="form-group">
        <?php
        $requiredExtra = $post->required_extra;
        ?>
        @if ($requiredExtra)
            <div class="extra-services">
                <label class="d-block mb-2" for="extra-services">
                    {{__('Extra')}}
                    <span class="text-danger f12">{{__('(required)')}}</span>
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
                <label class="d-block mb-2" for="extra-services">
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
        <input type="hidden" name="homeID" value="{{ $post->post_id }}">
        <input type="hidden" name="homeEncrypt"
               value="{{ hh_encrypt($post->post_id) }}">
        <input type="submit" class="btn btn-primary btn-block text-uppercase"
               name="sm"
               value="{{__('Book Now')}}">
    </div>
    <div class="form-message"></div>
</form>
