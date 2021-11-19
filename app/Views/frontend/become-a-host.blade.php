@include('frontend.components.header')
<?php
enqueue_script('select2-js');
enqueue_style('select2-css');
?>
<div class="become-a-host-page">
    <?php
    $background = get_attachment_url(get_option('become_a_host_background'));
    $background_footer = get_attachment_url(get_option('become_a_host_background_footer'));
    ?>
    <div class="register-form" style="background: url({{$background}}) center center no-repeat; background-size: cover">
        <div class="register-form--content">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-7 col-xl-8">
                        <div class="form-left">
                            <h1 class="title text-center text-lg-left">{{__('Why host on AweBooking?')}}</h1>
                            <h4 class="description text-center text-lg-left">{!! __('Join our community and start uploading your properties. <br/> We make it simple and secure to host travelers.') !!}</h4>
                            <a href="#become-partner-features"
                               class="explore-now d-none d-lg-inline-block">{{__('Explore Now')}}<i
                                    class="ti-angle-down "></i></a>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-5 col-xl-4">
                        <div class="form-right">
                            <div class="row">
                                <div class="col-12 col-md-8 offset-md-2 col-lg-12 offset-lg-0">
                                    <form id="form-become-a-host" action="{{url('become-a-host')}}"
                                          data-validation-id="form-become-a-host"
                                          class="form form-action relative">
                                        @include('common.loading')
                                        <h2 class="mb-4">{{__('Become a Host')}}</h2>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="become-firstname">{{__('First Name')}} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="become-firstname"
                                                           class="form-control has-validation"
                                                           data-validation="required"
                                                           name="first_name" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="become-firstname">{{__('Last Name')}} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="become-lastname"
                                                           class="form-control has-validation"
                                                           data-validation="required"
                                                           name="last_name" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="become-email">{{__('Email Address')}} <span class="text-danger">*</span></label>
                                            <input type="email" id="become-email" class="form-control has-validation"
                                                   data-validation="required" name="email"
                                                   autocomplete="off">
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="become-gender">{{__('Gender')}} <span
                                                            class="text-danger">*</span></label>
                                                    <select name="gender" id="become-gender" class="form-control"
                                                            data-plugin="customselect">
                                                        <option value="male">{{__('Male')}}</option>
                                                        <option value="female">{{__('Female')}}</option>
                                                        <option value="other">{{__('Other')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="become-phone">{{__('Mobile')}} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="become-phone"
                                                           class="form-control has-validation"
                                                           data-validation="required" name="phone"
                                                           autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="become-address">{{__('Address')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="become-address" class="form-control has-validation"
                                                   data-validation="required" name="address"
                                                   autocomplete="off">
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="become-address">{{__('City')}} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="become-city"
                                                           class="form-control has-validation"
                                                           data-validation="required" name="city"
                                                           autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="become-location">{{__('Country')}}</label>
                                                    <?php
                                                    enqueue_script('nice-select-js');
                                                    enqueue_style('nice-select-css');
                                                    $countries = list_countries();
                                                    ?>
                                                    <select name="location" id="become-location"
                                                            class="form-control wide" data-plugin="customselect">
                                                        @foreach($countries as $key => $value)
                                                            <option value="{{ $key }}"
                                                                    data-icon="{{ $value['flag24'] }}">{{ $value['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="become-zipcode">{{__('Zipcode')}}</label>
                                                    <input type="text" id="become-zipcode" class="form-control"
                                                           name="zipcode"
                                                           autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox checkbox-success">
                                                <input type="checkbox" id="become-term-condition" name="term_condition"
                                                       value="1">
                                                <label for="become-term-condition">
                                                    {!! sprintf(__('I accept %s'), '<a class="c-pink" href="'.get_the_permalink(get_option('term_condition_page'), '', 'page').'" class="text-dark">'. __('Terms and Conditions') .'</a>') !!}
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit"
                                                class="btn btn-primary btn-block mt-3">{{__('Send a Request')}}</button>
                                        <div class="form-message"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $become_features = get_option('become_a_host_features');
    if($become_features){
    ?>
    <div id="become-partner-features" class="container">
        <div class="row">
            <div class="col-lg-9 offset-lg-2">
                <div class="become-partner-features">
                    <?php
                    foreach ($become_features as $key => $become_feature) {
                    if ($key % 2 == 0) {
                    ?>
                    <div class="become-partner-feature-item pt-4 pt-lg-5">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-7">
                                <h2 class="title h1">{{ get_translate($become_feature['title']) }}</h2>
                                <div
                                    class="description font-20 mt-3">{!! balanceTags(get_translate($become_feature['detail'])) !!}</div>
                            </div>
                            <div class="col-12 col-lg-5">
                                <?php
                                $image_id = $become_feature['image'];
                                $image_url = get_attachment_url($image_id);
                                ?>
                                <img src="{{$image_url}}" alt="{{__('Image Feature')}}" class="img-fluid mt-3 mt-lg-0">
                            </div>
                        </div>
                    </div>
                    <?php
                    }else{
                    ?>
                    <div class="become-partner-feature-item pt-4 pt-lg-5">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-7 order-lg-last">
                                <h2 class="title h1">{{ get_translate($become_feature['title']) }}</h2>
                                <div
                                    class="description font-20 mt-3">{!! balanceTags(get_translate($become_feature['detail'])) !!}</div>
                            </div>
                            <div class="col-12 col-lg-5 order-lg-0">
                                <?php
                                $image_id = $become_feature['image'];
                                $image_url = get_attachment_url($image_id);
                                ?>
                                <img src="{{$image_url}}" alt="{{__('Image Feature')}}" class="img-fluid mt-3 mt-lg-0">
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="already-to-earn"
         style="background: url({{ $background_footer }}) no-repeat center center; background-size: cover">
        <div class="container">
            <div class="content">
                <h2 class="h1">{{__('Ready to earn?')}}</h2>
                <a href="#form-become-a-host" class="btn btn-white mt-3">{{__('Get Started')}}</a>
            </div>
        </div>
    </div>
</div>
@include('frontend.components.footer')
