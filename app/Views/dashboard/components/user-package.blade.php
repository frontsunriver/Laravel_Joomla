<?php
    /**
     * Created by PhpStorm.
     * User: Jream
     * Date: 12/22/2019
     * Time: 9:47 PM
     */
?>
<?php
    $user_id = get_current_user_id();
    $is_partner = is_partner($user_id);
    $is_admin = is_admin($user_id);
    $total_package = get_number_of_package();
    $member_package_url = dashboard_url('list-package');
?>
@if($is_partner || $is_admin)
    {{--<div class="card relative p-3">
        <h5>{{__('Your Package')}}</h5>
        <?php
            $package = get_package_by_user();
        ?>
        @if($package['total'] > 0)

        @else
            @if($total_package > 0)
            <div class="tth-no-membership-package">
                <h5>Please buy membership package to use functions of partner</h5>
                <a href="{{ $member_package_url }}" class="btn btn-success mt-2">
                    {{__('Buy Package')}}
                </a>
            </div>
		    @endif
        @endif
    </div>--}}
@endif
