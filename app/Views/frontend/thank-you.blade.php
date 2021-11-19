<?php
if (!isset($bookingObject)) {
    return;
}
$status = $bookingObject->status;
$status_info = booking_status_info($status);
?>
@include('frontend.components.header')
<div class="hh-checkout-redirecting pb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="payment-item">
                    <div class="payment-status">
                        <i class="{{ $status_info['icon'] }} payment-icon payment-{{ $status }}"></i>
                    </div>
                    <h4 class="payment-title">
                        {!! balanceTags(__($status_info['payment_text'])) !!}
                    </h4>
                    <div class="payment-desc">
                        {{ sprintf(__('Email booking details will be sent to the email address: %s'), $bookingObject->email) }}
                    </div>
                    <div class="payment-detail">
                        <div class="item d-flex align-items-center">
                            <span>{{__('Booking ID')}}</span>
                            <span class="ml-4">{{ $bookingObject->booking_id }}</span>
                        </div>
                        <div class="item d-flex align-items-center">
                            <span>{{__('Created Date')}}</span>
                            <span class="ml-4">{{ date(hh_date_format(), $bookingObject->created_date) }}</span>
                        </div>
                        <div class="item d-flex align-items-center">
                            <span>{{__('Payment Method')}}</span>
                            <span class="ml-4">
                                <?php
                                $paymentID = $bookingObject->payment_type;
                                $paymentObject = get_payments($paymentID);
                                ?>
                                {{ $paymentObject::getName() }}
                                </span>
                        </div>
                    </div>
                    <h5 class="mt-5">{{__('Your Information')}}</h5>
                    <div class="payment-customer-info">
                        <div class="item">
                            <span>{{__('First Name')}}</span>
                            <span>{{ $bookingObject->first_name }}</span>
                        </div>
                        <div class="item">
                            <span>{{__('Last Name')}}</span>
                            <span>{{ $bookingObject->last_name }}</span>
                        </div>
                        <div class="item">
                            <span>{{__('Email')}}</span>
                            <span>{{ $bookingObject->email }}</span>
                        </div>
                        <div class="item">
                            <span>{{__('Phone')}}</span>
                            <span>{{ $bookingObject->phone }}</span>
                        </div>
                        <div class="item">
                            <span>{{__('Address')}}</span>
                            <span>{{ $bookingObject->address }}</span>
                        </div>
                        @if($bookingObject->note)
                            <div class="item">
                                <span>{{__('Note')}}</span>
                                <span>{{ $bookingObject->note }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-center mt-5">
                        <a href="{{ dashboard_url('all-booking') }}"
                           class="btn btn-primary m-auto">{{__('Go to Your Booking')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.components.footer')
