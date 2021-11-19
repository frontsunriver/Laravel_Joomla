<?php
show_lang_section('mb-2');
$langs = get_languages_field();
?>
<div class="form-group">
    <label for="coupon_code_update">
        {{ __('Coupon Code') }}
    </label>
    <input type="text" class="form-control has-validation"
           data-validation="required" id="coupon_code_update" name="coupon_code"
           value="{{ $couponObject->coupon_code }}"
           placeholder="{{__('Recommended: Letters, numbers')}}">
</div>
<div class="form-group">
    <label for="coupon_description">
        {{ __('Coupon Description') }}
    </label>
    @foreach($langs as $key => $item)
        <textarea name="coupon_description{{get_lang_suffix($item)}}" id="coupon_description{{get_lang_suffix($item)}}"
                  class="form-control {{get_lang_class($key, $item)}}"
                  placeholder="{{__('Enter more detail')}}"
                  @if(!empty($item)) data-lang="{{$item}}" @endif>{{ get_translate($couponObject->coupon_description, $item) }}</textarea>
    @endforeach
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="coupon_type_update">
                {{ __('Coupon Type') }}
            </label><br/>
            <select name="coupon_type" id="coupon_type_update" class="wide"
                    data-plugin="customselect">
                <option value="fixed"
                        @if($couponObject->price_type == 'fixed') selected @endif>{{ __('Fixed') }}</option>
                <option value="percent"
                        @if($couponObject->price_type == 'percent') selected @endif>{{ __('Percent') }}</option>
            </select>
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="coupon_price_update">
                {{ __('Coupon Price') }}
            </label>
            <input type="number" class="form-control has-validation" min="0"
                   data-validation="required|numeric" id="coupon_price_update"
                   value="{{ $couponObject->coupon_price }}"
                   name="coupon_price"
                   placeholder="{{__('Price')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="coupon_service_type_update">{{__('Service Type')}}</label>
            <select id="coupon_service_type_update" class="wide"
                    data-plugin="customselect" name="coupon_service_type">
                <option value="">{{__('Apply All Services')}}</option>
                <?php
                $services = get_posttypes(false);
                foreach ($services as $key => $service) {
                ?>
                <option @if($couponObject->service_type == $key) selected @endif value="{{$key}}">{{$service['name']}}</option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="coupon_start_update">
                {{ __('Start Date') }}
            </label>
            <input type="text" class="form-control has-validation"
                   data-validation="required" id="coupon_start_update"
                   name="coupon_start" data-plugin="datepicker"
                   value="{{ date('Y-m-d', $couponObject->start_time) }}"
                   placeholder="{{__('Start Date')}}">
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="coupon_end_update">
                {{ __('End Date') }}
            </label>
            <input type="text" class="form-control has-validation"
                   data-validation="required" id="coupon_end_update"
                   name="coupon_end" data-plugin="datepicker"
                   value="{{ date('Y-m-d', $couponObject->end_time) }}"
                   placeholder="{{__('End Date')}}">
        </div>
    </div>
</div>
<input type="hidden" name="couponID" value="{{ $couponObject->coupon_id }}">
<input type="hidden" name="couponEncrypt" value="{{ hh_encrypt($couponObject->coupon_id) }}">
