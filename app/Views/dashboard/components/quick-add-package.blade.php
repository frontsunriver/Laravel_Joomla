<?php
    enqueue_script('nice-select-js');
    enqueue_style('nice-select-css');

    enqueue_script('flatpickr-js');
    enqueue_style('flatpickr-css');
?>
<a class="btn btn-success waves-effect waves-light" href="javascript:void(0)" data-toggle="modal"
   data-target="#hh-add-new-package-modal">
    <i class="ti-plus mr-1"></i>
    {{__('Create New')}}
</a>
<div id="hh-add-new-package-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form class="form form-action relative" action="{{ dashboard_url('add-new-package') }}"
              data-validation-id="form-add-new-package">
            @include('common.loading')
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Add new Package')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="package_name">
                            {{__('Package Name')}}
                        </label>
                        <input type="text" class="form-control has-validation"
                               data-validation="required" id="package_name" name="package_name">
                    </div>
                    <div class="form-group">
                        <label for="package_price">
                            {{__('Package Price ($)')}}
                        </label>
                        <input type="text" class="form-control" id="package_price" name="package_price"
                               placeholder="{{__('Leave empty for Free')}}">
                    </div>
                    <div class="form-group">
                        <label for="package_time">
                            {{__('Package Time (days)')}}
                        </label>
                        <input type="text" class="form-control" id="package_time" name="package_time"
                               placeholder="{{__('Leave empty for Unlimited')}}">
                    </div>
                    <div class="form-group">
                        <label for="package_commission">
                            {{__('Package Commission (%)')}}
                        </label>
                        <input type="text" class="form-control" id="package_commission" name="package_commission">
                    </div>
                    <div class="form-group">
                        <label for="package_description">
                            {{__('Package Description')}}
                        </label>
                        <textarea class="form-control" id="package_description" name="package_description"></textarea>
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
