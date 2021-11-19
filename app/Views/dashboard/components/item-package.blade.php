<?php
    /**
     * Created by PhpStorm.
     * User: Jream
     * Date: 12/22/2019
     * Time: 10:23 PM
     */
?>
<div class="col-md-3">
    <div class="card card-pricing border-warning" style="border: 1px solid #dfdfdf">
        <div class="card-body text-center">
            <p class="card-pricing-plan-name font-weight-bold text-uppercase pb-0 mb-0">
                {{ $item->package_name }}
            </p>
            <h2 class="card-pricing-price mt-0 mb-0">
                @if(!empty($item->package_price))
                    ${{ $item->package_price }}
                @else
                    {{__('Free')}}
                @endif
            </h2>
            <ul class="card-pricing-features">
                <li>
                    {{__('Package time')}}:
                    @if(!empty($item->package_time))
                        {{ $item->package_time }} day(s)
                    @else
                        {{__('Unlimited')}}
                    @endif
                </li>
                <li>
                    {{__('Commisson')}}:
                    {{ $item->package_commission }}%
                </li>
                @if(!empty($item->package_description))
                    <li>
                        {{__('Description')}}:
                        {{ $item->package_description }}
                    </li>
                @endif
            </ul>
            <button class="btn btn-primary waves-effect waves-light mt-4 mb-2 width-sm">{{__('Select')}}</button>
        </div>
    </div> <!-- end Pricing_card -->
</div> <!-- end col -->
