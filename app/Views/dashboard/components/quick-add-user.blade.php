<?php
enqueue_script('nice-select-js');
enqueue_style('nice-select-css');

enqueue_script('flatpickr-js');
enqueue_style('flatpickr-css');
?>
<a class="btn btn-success waves-effect waves-light" href="javascript:void(0)" data-toggle="modal"
   data-target="#hh-add-new-user-modal">
    <i class="ti-plus mr-1"></i>
    {{__('Create New')}}
</a>
<div id="hh-add-new-user-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form class="form form-action relative" action="{{ dashboard_url('add-new-user') }}" data-validation-id="form-add-new-user">
            @include('common.loading')
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Add new User')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input type="text" class="form-control has-validation"
                               data-validation="required" id="user_email" name="user_email">
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="user_first_name">{{__('First Name')}} <span
                                        class="f12">(Optional)</span></label>
                                <input type="text" class="form-control" id="user_first_name" name="user_first_name">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="user_last_name">{{__('Last Name')}} <span
                                        class="f12">{{__('(Optional)')}}</span></label>
                                <input type="text" class="form-control" id="user_last_name" name="user_last_name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="user_role">{{__('Role')}}</label>
                                <select name="user_role" id="user_role" class="form-control wide"
                                        data-plugin="customselect">
                                    <option value="">{{__('---- Select ----')}}</option>
                                    <?php
                                    $roles = get_all_roles();
                                    ?>
                                    @foreach($roles as $key => $role)
                                        @if($role != 'Superadmin')
                                            <option value="{{ $key }}">{{ $role }}</option>
                                        @endif
                                    @endforeach;
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_password">{{__('Password')}}</label>
                        <div class="input-group">
                            <input type="text" class="form-control has-validation"
                                   data-validation="required|min:6:m" data-plugin="password" id="user_password"
                                   name="user_password">
                            <div class="input-group-append">
                                <button class="btn btn-dark waves-effect waves-light"
                                        type="button">{{__('Create Password')}}</button>
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
