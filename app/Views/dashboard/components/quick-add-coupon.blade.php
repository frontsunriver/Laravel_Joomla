<a class="btn btn-success waves-effect waves-light" href="javascript:void(0)" data-toggle="modal"
   data-target="#hh-add-new-coupon-modal">
    <i class="ti-plus mr-1"></i>
    {{__('Create New')}}
</a>
<div id="hh-add-new-coupon-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form class="form form-action relative form-translation" action="{{ dashboard_url('add-new-coupon') }}" data-validation-id="form-add-new-coupon">
            @include('common.loading')
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Add new Coupon')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    show_lang_section('mb-2');
                    $langs = get_languages_field();
                    ?>
                    <div class="form-group">
                        <label for="coupon_code">
                            {{__('Coupon Code')}}
                        </label>
                        <input type="text" class="form-control has-validation"
                               data-validation="required" id="coupon_code" name="coupon_code"
                               placeholder="{{__('Recommended: Letters, numbers')}}">
                    </div>
                    <div class="form-group">
                        <label for="coupon_description">
                            {{__('Coupon Description')}}
                        </label>
                        @foreach($langs as $key => $item)
                            <textarea name="coupon_description{{get_lang_suffix($item)}}"
                                      id="coupon_description{{get_lang_suffix($item)}}"
                                      class="form-control {{get_lang_class($key, $item)}}"
                                      placeholder="{{__('Enter more detail')}}"
                                      @if(!empty($item)) data-lang="{{$item}}" @endif></textarea>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="coupon_type">
                                    {{__('Coupon Type')}}
                                </label><br/>
                                <select name="coupon_type" id="coupon_type" class="wide"
                                        data-plugin="customselect">
                                    <option value="fixed">{{__('Fixed')}}</option>
                                    <option value="percent">{{__('Percent')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="coupon_price">
                                    {{__('Coupon Price')}}
                                </label>
                                <input type="number" class="form-control has-validation" min="0"
                                       data-validation="required|numeric" id="coupon_price"
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
                                        <option value="{{$key}}">{{$service['name']}}</option>
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
                                <label for="coupon_start">
                                    {{__('Start Date')}}
                                </label>
                                <input type="text" class="form-control has-validation"
                                       data-validation="required" id="coupon_start"
                                       name="coupon_start" data-plugin="datepicker"
                                       placeholder="{{__('Start Date')}}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="coupon_end">
                                    {{__('End Date')}}
                                </label>
                                <input type="text" class="form-control has-validation"
                                       data-validation="required" id="coupon_end"
                                       name="coupon_end" data-plugin="datepicker"
                                       placeholder="{{__('End Date')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit"
                            class="btn btn-info waves-effect waves-light">{{__('Add New')}}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div><!-- /.modal -->
