<?php
    $homeObject = get_post($post_id, 'home');
    $booking_type = $homeObject->booking_type;
?>
@if($booking_type == 'per_hour')
    <div class="modal fade hh-get-modal-content" id="hh-show-availability-time-slot-modal" tabindex="-1" role="dialog"
         aria-hidden="true" data-url="{{ dashboard_url('get-availability-time-slot') }}">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                @include('common.loading')
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Booking Detail')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect"
                            data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
@endif
