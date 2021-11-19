<?php
    if (!isset($status)) {
        return false;
    }
?>
@if($status == 0)
    {{__('Invalid url.')}}
@elseif($status == 1)
    {{__('Thank you for your confirmation.')}}
    {{__('You will receive a booking email. Please check your inbox!')}}
@elseif($status == 2)
    {{__('You have already confirmed')}}
@endif
