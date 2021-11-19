@include('frontend.components.header')
<?php
enqueue_style('mapbox-gl-css');
enqueue_style('mapbox-gl-geocoder-css');
enqueue_script('mapbox-gl-js');
enqueue_script('mapbox-gl-geocoder-js');

$map_lat = get_option('contact_map_lat');
$map_lng = get_option('contact_map_lng');
$contact_detail = get_option('contact_detail');
?>
@if($map_lat && $map_lng)
    <div id="contact-us-map" data-lat="{{ $map_lat }}" data-lng="{{ $map_lng }}">
        <div class="map-render"></div>
        <div class="contact-us-detail">
            {!! balanceTags($contact_detail) !!}
            <form action="{{ url('contact-us-post') }}" class="form-sm mt-3 form-action form-contact-us" method="post"
                  data-validation-id="form-contact-us"
                  data-google-captcha="yes">
                <h3 class="mb-3">{{__('Stay in touch with us')}}</h3>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <input id="contact_email" type="text" class="form-control has-validation"
                                   data-validation="required|email" name="email" placeholder="{{__('Email')}}">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <input id="contact_title" type="text" class="form-control has-validation"
                                   data-validation="required" name="title" placeholder="{{__('Title')}}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <textarea name="message" id="contact_message" class="form-control"
                              placeholder="{{__('Your Message')}}"></textarea>
                </div>
                <input type="submit" class="btn btn-primary" name="submit" value="{{__('Send Message')}}">
                <div class="form-message"></div>
            </form>
        </div>
    </div>
@endif
@include('frontend.components.footer')
