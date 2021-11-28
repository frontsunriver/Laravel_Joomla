@include('dashboard.components.header')
<?php
enqueue_style('dropzone-css');
enqueue_script('dropzone-js');
?>

<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            @include('dashboard.components.breadcrumb', ['heading' => __('Profile')])
            {{--Start Content--}}
            <?php
            $current_user = get_current_user_data();
            $user_id = $current_user->getUserId();
            ?>
            <div class="row">
                <div class="col-12 col-lg-4 order-lg-8 ">
                    <div class="profile-preview card relative p-3  bg-pattern-1">
                        <form action="{{ dashboard_url('update-your-avatar') }}" class="form form-action"
                              data-validation-id="form-update-avatar">
                            @include('common.loading')
                            <input type="hidden" name="user_id" value="{{ $user_id }}">
                            <input type="hidden" name="user_encrypt" value="{{ hh_encrypt($user_id) }}">
                            <div class="avatar-preview">
                                <div class="media align-items-center mb-2">
                                    <img src="{{ get_user_avatar($user_id, [85,85]) }}" class="avatar"
                                         alt="{{__('Avatar')}}">
                                    <div class="media-body">
                                        <h4 class="mb-1 mt-3">{{ get_username($user_id) }}</h4>
                                        <p class="text-muted">{{ $current_user->getUserLogin() }}</p>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" class="change-avatar">{{__('Change Avatar')}}</a>
                                <div class="hh-upload-wrapper d-none">
                                    <div class="hh-upload-wrapper clearfix">
                                        <div class="attachments">
                                        </div>
                                        <div class="mt-1">
                                            <a href="javascript:void(0);" class="add-attachment"
                                               title="{{__('Add Image')}}"
                                               data-text="{{__('Add Image')}}"
                                               data-url="{{ dashboard_url('all-media') }}">{{__('Add Image')}}</a>
                                            <input type="hidden" name="avatar" class="upload_value input-upload"
                                                   data-size="85" data-url="{{ dashboard_url('get-attachments') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-left mt-3">
                                <h4 class="font-13 text-uppercase">{{__('About Me:')}}</h4>
                                <p class="text-muted font-13 mb-3">
                                    @if($current_user->description)
                                        {!! balanceTags(nl2br($current_user->description)) !!}
                                    @else
                                        {{__('Nothing :)')}}
                                    @endif
                                </p>
                                <p class="text-muted mb-2 font-13"><strong>{{__('Full Name:')}}</strong>
                                    <span class="ml-2"
                                          data-hh-bind-from="#first_name">{{ $current_user->first_name }}</span>
                                    <span class="ml-1"
                                          data-hh-bind-from="#last_name">{{ $current_user->last_name }}</span>
                                </p>
                                <p class="text-muted mb-2 font-13">
                                    <strong>{{__('Mobile:')}}</strong>
                                    <span class="ml-2">{{ $current_user->mobile }}</span></p>

                                <p class="text-muted mb-2 font-13">
                                    <strong>{{__('Email:')}}</strong>
                                    <span class="ml-2 ">{{ $current_user->email }}</span>
                                </p>

                                <p class="text-muted mb-1 font-13">
                                    <strong>{{__('Location:')}}</strong>
                                    <span class="ml-2">{{ get_country_by_code($current_user->location)['name'] }}</span>
                                </p>
                            </div>
                        </form>
                    </div>

                    {{--Password form--}}
                    <div class="card relative p-3">
                        <form action="{{ dashboard_url('update-password') }}" class="form form-action mt-1"
                              data-validation-id="form-update-password"
                              method="post">
                            @include('common.loading')
                            <input type="hidden" name="user_id" value="{{ $user_id }}">
                            <input type="hidden" name="user_encrypt" value="{{ hh_encrypt($user_id) }}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="new_password">{{__('New Password')}}</label>
                                        <input id="new_password" type="password" class="form-control has-validation"
                                               data-validation="required" name="password" value="">
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="new_re_password">{{__('Re-Password')}}</label>
                                        <input id="new_re_password" type="password" class="form-control has-validation"
                                               data-validation="required" name="password_confirmation" value="">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning btn-rounded waves-effect waves-light right">
                                <span class="btn-label">
                                    <i class="mdi mdi-check-all"></i>
                                </span>
                                {{__('Change Password')}}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-lg-8 order-lg-4">
                    <div class="card relative p-3">
                        <h5>{{__('Your Profile')}}</h5>
                        <form action="{{ dashboard_url('update-your-profile') }}" class="form form-action mt-3"
                              data-validation-id="form-update-profile"
                              method="post">
                            @include('common.loading')
                            <input type="hidden" name="user_id" value="{{ $user_id }}">
                            <input type="hidden" name="user_encrypt" value="{{ hh_encrypt($user_id) }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="email">{{__('Email')}}</label>
                                        <input id="email" type="email" class="form-control" name="email"
                                               value="{{ $current_user->email }}" disabled>
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-6 col-md-4">
                                    <div class="form-group">
                                        <label for="first_name">{{__('First Name')}}</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                               value="{{ $current_user->first_name }}">
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="form-group">
                                        <label for="last_name">{{__('Last Name')}}</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                               value="{{ $current_user->last_name }}">
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="form-group">
                                        <label for="mobile">{{__('Mobile')}}</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile"
                                               value="{{ $current_user->mobile }}">
                                    </div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">{{__('Address')}}</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                               value="{{ $current_user->address }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="location">{{__('Location')}}</label>
                                        <?php
                                        enqueue_script('nice-select-js');
                                        enqueue_style('nice-select-css');

                                        $location = $current_user->location;
                                        $countries = list_countries();
                                        ?>
                                        <select name="location" id="location" class="form-control wide"
                                                data-plugin="customselect">
                                            <option value="">{{__('---- Select ----')}}</option>
                                            @foreach($countries as $key => $value)
                                                <option value="{{ $key }}" data-icon="{{ $value['flag24'] }}"
                                                        @if($key == $location) selected @endif>{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description">{{__('Description')}}</label>
                                        <textarea name="description" id="description"
                                                  class="form-control">{{ $current_user->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description">{{__('Video')}}</label>
                                        <input type="text" class="form-control" id="video" name="video"
                                               value="{{ $current_user->video }}">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-rounded waves-effect waves-light right">
                                <span class="btn-label"><i class="mdi mdi-check-all"></i></span>
                                {{__('Update')}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')
