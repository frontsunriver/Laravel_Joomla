<?php
enqueue_script('nice-select-js');
enqueue_style('nice-select-css');

enqueue_script('flatpickr-js');
enqueue_style('flatpickr-css');
$user_role = get_user_role($user->getUserId(), 'id');
?>
<div class="form-group">
    <label for="user_email">{{__('Email')}}</label>
    <div class="form-control">
        {{ $user->email }}
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="user_first_name">{{__('First Name')}} <span class="f12">{{__('(Optional)')}}</span></label>
            <input type="text" class="form-control" id="user_first_name_edit" name="user_first_name"
                   value="{{ $user->first_name }}">
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="user_last_name">{{__('Last Name')}} <span class="f12">{{__('(Optional)')}}</span></label>
            <input type="text" class="form-control" id="user_last_name_edit" name="user_last_name"
                   value="{{ $user->last_name }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="user_role">{{__('Role')}}</label>
            <select name="user_role" id="user_role_edit" class="form-control wide" data-plugin="customselect">
                <option value="">{{__('---- Select ----')}}</option>
                <?php
                $roles = get_all_roles();
                ?>
                @foreach($roles as $key => $role)
                    @if($role != 'Superadmin')
                        <option @if($user_role == $key) selected @endif value="{{ $key }}">{{ $role }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="user_password">{{__('Password')}}</label>
    <div class="input-group">
        <input type="text" class="form-control" data-plugin="password" id="user_password_edit" name="user_password">
        <div class="input-group-append">
            <button class="btn btn-dark waves-effect waves-light" type="button">{{__('Create Password')}}</button>
        </div>
    </div>
</div>
<input type="hidden" name="userID" value="{{ $user->getUserId() }}">
<input type="hidden" name="userEncrypt" value="{{ hh_encrypt($user->getUserId()) }}">
