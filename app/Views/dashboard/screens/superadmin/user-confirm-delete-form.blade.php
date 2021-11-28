<?php
    $list_users = get_users_in_role(['administrator', 'partner', 'superadmin'], $user_id);
?>
<div class="hh-delete-user-option">
    <input type="hidden" name="userID" value="{{$user_id}}"/>
    <input type="hidden" name="userEncrypt" value="{{$user_encrypt}}"/>
    <div class="radio radio-success mb-2">
        <input type="radio" name="delete_type" value="all" checked="" id="delete_type-all">
        <label for="delete_type-all">{{__('Delete all service data.')}}</label>
    </div>
    <div class="radio radio-success">
        <input type="radio" name="delete_type" value="assign" id="delete_type-user">
        <label for="delete_type-user">{{__('Assign data to another user.')}}</label>
        <select data-plugin="customselect" name="user_assign" class="user-option mt-2">
            @if(!empty($list_users))
                @foreach($list_users as $key => $val)
                    <option value="{{$key}}">{{$val}}</option>
                @endforeach
            @else
                <option value="">{{__('No users')}}</option>
            @endif
        </select>
    </div>

    <div class="notice all mt-2">
        <p>{!! __('If you select option <b>"Delete all service data"</b>, all the data of this user will be deleted including: ') !!}</p>
        <ul>
            <li>{{__('User information (Email, location, phone)')}}</li>
            <li>{{__('User media')}}</li>
            <li>{{__('User review/comment')}}</li>
            <li>{{__('User payout data')}}</li>
            <li>{{__('User notification')}}</li>
        </ul>
    </div>

    <div class="notice assign mt-2">
        <p>{!! __('If you select option <b>"Assign data to another user"</b>, the user information will be deleted and listings of this user will be assigned to user you selected (Home, Car, Experience and Media)') !!}</p>
    </div>

</div>
