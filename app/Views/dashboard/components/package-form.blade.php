<?php
    enqueue_script('nice-select-js');
    enqueue_style('nice-select-css');

    enqueue_script('flatpickr-js');
    enqueue_style('flatpickr-css');
?>
<div class="form-group">
    <label for="package_name_update">
        {{__('Package Name')}}
    </label>
    <input type="text" class="form-control has-validation"
           data-validation="required" id="package_name_update" name="package_name"
           value="{{ $packageObject->package_name }}">
</div>
<div class="form-group">
    <label for="package_price_update">
        {{__('Package Price ($)')}}
    </label>
    <input type="text" class="form-control" id="package_price_update" name="package_price"
           value="{{ $packageObject->package_price }}">
</div>

<div class="form-group">
    <label for="package_time_update">
        {{__('Package Time (days)')}}
    </label>
    <input type="text" class="form-control" id="package_time_update" name="package_time"
           value="{{ $packageObject->package_time }}">
</div>

<div class="form-group">
    <label for="package_commission_update">
        {{__('Package Commission (%)')}}
    </label>
    <input type="text" class="form-control" id="package_commission_update" name="package_commission"
           value="{{ $packageObject->package_commission }}">
</div>

<div class="form-group">
    <label for="package_description_update">
        {{__('Package Description')}}
    </label>
    <textarea name="package_description" id="package_description_update" class="form-control"
              placeholder="Enter more detail">{{ $packageObject->package_description }}</textarea>
</div>

<input type="hidden" name="packageID" value="{{ $packageObject->package_id }}">
<input type="hidden" name="packageEncrypt" value="{{ hh_encrypt($packageObject->package_id) }}">
