<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value)) {
    $value = $std;
}
$idName = str_replace(['[', ']'], '_', $id);
enqueue_style('flatpickr-css');
enqueue_script('flatpickr-js');

global $post;
$booking_type = isset($post->booking_type) ? $post->booking_type : '';
?>
<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}" data-delete-url="{{ dashboard_url('delete-custom-price-item') }}"
     data-operator="{{ $operation }}"
     class="form-group mb-3 col {{ $layout }} field-{{ $type }} relative">
    @include('common.loading', ['class' => 'loading-custom-price'])
    <label for="{{ $idName }}">
        {{ __($label) }}
        @if (!empty($desc))
            <i class="dripicons-information field-desc" data-toggle="popover" data-placement="right"
               data-trigger="hover"
               data-content="{{ __($desc) }}"></i>
        @endif
    </label>
    <div class="w-100"></div>
    <a href="javascript: void(0)" data-post-id="{{ $post_id }}" data-toggle="modal"
       data-target="#hh-bulk-edit-modal"
       class="btn btn-success btn-xs">{{ __('Add new') }}</a>
    <div class="price-render mt-4">
        <?php
        $customPrice = \App\Controllers\CustomPriceController::getAllPrices($post_id, $post_type);
        ?>
        @include('dashboard.components.services.'.$post_type. '.custom_price', ['custom_price' =>$customPrice, 'booking_type' => $booking_type ])
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif
<div id="hh-bulk-edit-modal" data-action="{{ dashboard_url('add-new-custom-price') }}" class="modal fade" tabindex="-1"
     role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        @include('common.loading')
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Add new Price')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                @if($post_type == 'experience')
                    <div id="setting-time_bulk" class="form-group field-select2_multiple"
                         data-condition="booking_type:is(date_time)">
                        <label for="time_of_day_bulk">{{ __('Time') }}</label>
                        <select id="time_of_day_bulk" class="form-control select2-multiple" data-toggle="select2"
                                name="time_of_day_bulk"
                                multiple="multiple" data-placeholder="{{ __('Choose ...') }}">
                            <?php
                            $listTime = list_hours(30);
                            ?>
                            @foreach($listTime as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div id="setting-type_of_bulk" class="form-group field-radio">
                    <div class="row">
                        <div class="col">
                            <div class="radio radio-success">
                                <input type="radio"
                                       name="type_of_bulk"
                                       value="days_of_week"
                                       id="type_of_bulk_week" checked>
                                <label for="type_of_bulk_week">{{ __('Days of Week') }}</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="radio radio-success">
                                <input type="radio"
                                       name="type_of_bulk"
                                       value="days_of_month"
                                       id="type_of_bulk_month">
                                <label for="type_of_bulk_month">{{ __('Days of Month') }}</label>
                            </div>
                        </div>
                        @if($post_type == 'home')
                        <div class="col">
                            <div class="radio radio-success">
                                <input type="radio"
                                       name="type_of_bulk"
                                       value="days_of_custom"
                                       id="type_of_bulk_custom">
                                <label for="type_of_bulk_custom">{{ __('Custom Date') }}</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="radio radio-success">
                                <input type="radio"
                                       name="type_of_bulk"
                                       value="days_of_discount"
                                       id="type_of_bulk_discount">
                                <label for="type_of_bulk_discount">{{ __('Special price') }}</label>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <?php
                enqueue_script('select2-js');
                enqueue_style('select2-css');
                ?>
                <div id="setting-days_of_week_bulk" class="form-group field-select2_multiple has-validation"
                     data-unique="" data-operator="and"
                     data-condition="type_of_bulk:is(days_of_week)">
                    <label for="days_of_week_bulk">{{ __('Days of Week') }} <span class="text-muted f11">(Leave blank to apply all)</span></label>
                    <select id="days_of_week_bulk" class="form-control select2-multiple" data-toggle="select2"
                            multiple="multiple" data-placeholder="{{ __('Choose ...') }}">
                        <option value="monday">{{__('Monday')}}</option>
                        <option value="tuesday">{{__('Tuesday')}}</option>
                        <option value="wednesday">{{__('Wednesday')}}</option>
                        <option value="thursday">{{__('Thursday')}}</option>
                        <option value="friday">{{__('Friday')}}</option>
                        <option value="saturday">{{__('Saturday')}}</option>
                        <option value="sunday">{{__('Sunday')}}</option>
                    </select>
                </div>
                <div id="setting-days_of_month_bulk" class="form-group field-select2_multiple has-validation"
                     data-unique="" data-operator="and"
                     data-condition="type_of_bulk:is(days_of_month)">
                    <label for="days_of_month_bulk">{{ __('Days of Month') }} <span class="text-muted f11">(Leave blank to apply all)</span></label>
                    <select id="days_of_month_bulk" class="form-control select2-multiple" data-toggle="select2"
                            multiple="multiple" data-placeholder="{{ __('Choose ...') }}">
                        @for($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                        @endfor
                    </select>
                </div>
                <div id="setting-days_of_custom_bulk" class="form-group field-select2_multiple has-validation"
                     data-unique="" data-operator="and"
                     data-condition="type_of_bulk:is(days_of_custom)">
                    <label for="days_of_custom_bulk">{{ __('Start Date') }} <span class="text-muted f11">(Select rent Start date)</span></label>
                    <input type="text" class="form-control"
                        data-plugin="datepicker"
                        data-date-format="d.m.Y."
                        data-min-date="{{ date('d.m.Y.')}}"
                        id="start_date"
                        name="start_date" value="">
                    <label for="days_of_custom_bulk">{{ __('End Date') }} <span class="text-muted f11">(Select rent End date)</span></label>
                    <input type="text" class="form-control"
                        data-plugin="datepicker"
                        data-date-format="d.m.Y."
                        data-min-date="{{ date('d.m.Y.')}}"
                        id="end_date"
                        name="end_date" value="">
                    
                    <label for="price_bulk">{{ __('Price per night') }}</label>
                    <input type="text" name="price_per_night" value="0" id="price_per_night" 
                            class="form-control">

                    <label for="price_bulk">{{ __('Minimum stay Date') }}</label>
                    <input type="number" name="min_stay_date" min="1" id="min_stay_date" value="1" 
                            class="form-control">
                </div>
                <div id="setting-days_of_discount_bulk" class="form-group field-select2_multiple has-validation"
                     data-unique="" data-operator="and"
                     data-condition="type_of_bulk:is(days_of_discount)">
                    <label for="days_of_discount_bulk">{{ __('Start Date') }} <span class="text-muted f11">(Select rent Start date)</span></label>
                    <input type="text" class="form-control"
                        data-plugin="datepicker"
                        data-date-format="d.m.Y."
                        data-min-date="{{ date('d.m.Y.')}}"
                        id="start_date_discount"
                        name="start_date_discount" value="">
                    <label for="days_of_discount_bulk">{{ __('End Date') }} <span class="text-muted f11">(Select rent End date)</span></label>
                    <input type="text" class="form-control"
                        data-plugin="datepicker"
                        data-date-format="d.m.Y."
                        data-min-date="{{ date('d.m.Y.')}}"
                        id="end_date_discount"
                        name="end_date_discount" value="">
                    
                    <label for="price_bulk">{{ __('Discount Percent') }}</label>
                    <input type="text" name="discount_percent" value="0" id="discount_percent" 
                            class="form-control">
                    <div class="row">
                        <div class="col-sm-6" id="bulk_first_minute">
                            <label for="available_bulk">{{ __('First Minute') }}</label>
                            <div class="w-100"></div>
                            <input type="checkbox" id="first_minute" name="first_minute"
                                    data-plugin="switchery" data-color="#1abc9c"
                                    value="on" checked/>
                        </div>
                        <div class="col-sm-6" id="bulk_last_minute">
                            <label for="available_bulk">{{ __('Last Minute') }}</label>
                            <div class="w-100"></div>
                            <input type="checkbox" id="last_minute" name="last_minute"
                                    data-plugin="switchery" data-color="#1abc9c"
                                    value="on" checked/>
                        </div>    
                    </div>
                </div>
                <div id="setting-month_bulk" class="form-group field-select2_multiple">
                    <label for="month_bulk">{{ __('Months') }} <span class="text-danger">*</span></label>
                    <select id="month_bulk" class="form-control select2-multiple has-validation"
                            data-validation="required" data-toggle="select2"
                            multiple="multiple" data-placeholder="{{ __('Choose ...') }}">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                        @endfor
                    </select>
                </div>
                <div id="setting-year_bulk" class="form-group field-select2_multiple">
                    <label for="year_bulk">{{ __('Years') }} <span class="text-danger">*</span></label>
                    <select id="year_bulk" class="form-control select2-multiple has-validation"
                            data-validation="required" data-toggle="select2"
                            multiple="multiple" data-placeholder="{{ __('Choose ...') }}">
                        @for($i = date('Y'); $i <= (date('Y') + 2); $i ++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="row">
                    @if($post_type == 'experience')
                        <div class="col">
                            <div id="setting-price-bulk-adult" class="form-group form-sm"
                                 data-condition="price_categories:is(enable_adults),booking_type:not(package)">
                                <label for="price_bulk_adult">{{ __('Adult') }}</label>
                                <input id="price_bulk_adult" type="text" name="adult_price_bulk" value="0"
                                       data-hh-bind-value-from="#price_categories-price-adult"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col">
                            <div id="setting-price-bulk-child" class="form-group form-sm"
                                 data-condition="price_categories:is(enable_children),booking_type:not(package)">
                                <label for="price_bulk_child">{{ __('Child') }}</label>
                                <input id="price_bulk_child" type="text" name="child_price_bulk"
                                       data-hh-bind-value-from="#price_categories-price-child" value="0"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col">
                            <div id="setting-price-bulk-infant" class="form-group form-sm"
                                 data-condition="price_categories:is(enable_infants),booking_type:not(package)">
                                <label for="price_bulk_infant">{{ __('Infant') }}</label>
                                <input id="price_bulk_infant" type="text" name="infant_price_bulk"
                                       data-hh-bind-value-from="#price_categories-price-infant" value="0"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="w-100"></div>
                        <div class="col-12 col-sm-4">
                            <div id="setting-max-people-bulk-infant" class="form-group form-sm"
                                 data-condition="price_categories:is(enable_infants),booking_type:not(package)">
                                <label for="max_people_bulk">{{ __('Max. People') }}</label>
                                <input id="max_people_bulk" type="number" min="0" step="1" name="max_people_bulk"
                                       data-hh-bind-value-from="#number_of_guest" value="0"
                                       class="form-control">
                            </div>
                        </div>
                        <input type="hidden" id="available_bulk" name="available"
                               value="on" checked/>

                        <input type="hidden" name="booking_type_bulk" data-hh-bind-value-from="#booking_type" value="">
                    @elseif($post_type == 'home')
                        <div class="col" id="bulk_price">
                            <div class="form-group form-sm">
                                <label for="price_bulk">{{ __('Price') }} <span class="text-danger">*</span></label>
                                <input id="price_bulk" type="text" name="price" value="0"
                                       class="form-control has-validation" data-validation="required">
                            </div>
                        </div>
                        <div class="col" id="bulk_available">
                            <div class="form-group form-sm">
                                <label for="available_bulk">{{ __('Available') }}</label>
                                <div class="w-100"></div>
                                <input type="checkbox" id="available_bulk" name="available"
                                       data-plugin="switchery" data-color="#1abc9c"
                                       value="on" checked/>
                            </div>
                        </div>
                        
                    @else
                        <div class="col" >
                            <div class="form-group form-sm">
                                <label for="price_bulk">{{ __('Price') }} <span class="text-danger">*</span></label>
                                <input id="price_bulk" type="text" name="price" value="0"
                                       class="form-control has-validation" data-validation="required">
                            </div>
                        </div>
                        <div class="col" >
                            <div class="form-group form-sm">
                                <label for="available_bulk">{{ __('Available') }}</label>
                                <div class="w-100"></div>
                                <input type="checkbox" id="available_bulk" name="available"
                                       data-plugin="switchery" data-color="#1abc9c"
                                       value="on" checked/>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <input type="hidden" id="post_id_bulk" name="post_id_bulk" value="{{ $post_id }}">
            <input type="hidden" name="post_type_bulk" value="{{ $post_type }}">
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-info waves-effect waves-light add-price">{{__('Add New')}}
                </button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->
<style>
    #hh-bulk-edit-modal .switchery {
        margin-top: 6px;
    }
</style>
