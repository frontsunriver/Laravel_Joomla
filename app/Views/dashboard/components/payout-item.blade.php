<?php
$userdata = get_user_by_id($payoutItem->user_id);
?>
<ul class="nav nav-tabs nav-bordered">
    <li class="nav-item">
        <a href="#tab-invoice-payment-info" data-toggle="tab" aria-expanded="false" class="nav-link active">
            {{__('Payout Information')}}
        </a>
    </li>
    <li class="nav-item">
        <a href="#tab-invoice-customer" data-toggle="tab" aria-expanded="true" class="nav-link">
            {{__('Payment Information')}}
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab-invoice-payment-info">
        <div class="payment-item">
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Payout ID')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $payoutItem->payout_id }}</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Amount')}}</h5>
                </div>
                <div class="col-8">
                    <span class="font-16"><strong>{{ convert_price($payoutItem->amount)  }}</strong></span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Status')}}</h5>
                </div>
                <div class="col-8">
                    <?php
                    $payout_status = payout_status_info($payoutItem->status);
                    ?>
                    <span class="booking-status booking-bgr {{$payoutItem->status}}">{{$payout_status['name']}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tab-invoice-customer">
        <div class="payment-item">
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Name')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{get_username($payoutItem->user_id) }}</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Email')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $userdata->email }}</span>
                </div>
            </div>
            <div class="item row align-items-start">
                <div class="col-4">
                    <h5 class="title">{{__('Payment Detail')}}</h5>
                </div>
                <div class="col-8">
                    <?php
                    $payment = get_payments(get_user_meta($payoutItem->user_id, 'payout_payment', 'bank_transfer'));
                    $payment_name = $payment::getName();
                    ?>
                    <p class="pt-1">{{__($payment_name)}}</p>
                    <p class="mt-2">
                        {!! nl2br(balanceTags(get_user_meta($payoutItem->user_id, 'payout_detail'))) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
