<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Booking;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;

class EarningController extends Controller
{
    public function _getPayoutDetail(Request $request){
        $payoutID = request()->get('payoutID');
        $payoutEncrypt = request()->get('payoutEncrypt');

        if(!hh_compare_encrypt($payoutID, $payoutEncrypt)){
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This payout is not available')
            ]);
        }

        $payout_model = new Payout();
        $payout_item = $payout_model->getPayout($payoutID);
        if(is_null($payout_item)){
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This payout is not available')
            ]);
        }

        if(is_partner() && get_current_user_id() !== $payout_item->user_id){
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This payout is not available')
            ]);
        }

        $html = view('dashboard.components.payout-item', ['payoutItem' => $payout_item])->render();
        $this->sendJson([
            'status' => 1,
            'html' => $html,
            'message' => __('Get the invoice successfully')
        ], true);

    }
    public function _deletePayoutItem(Request $request){
        $payoutID = request()->get('payoutID');
        $payoutEncrypt = request()->get('payoutEncrypt');

        if(!hh_compare_encrypt($payoutID, $payoutEncrypt)){
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This item is not available')
            ]);
        }

        $payout_model = new Payout();
        $payout_item = $payout_model->getPayout($payoutID);
        if(is_null($payout_item)){
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This action is invalid')
            ]);
        }

        $payout_model->deletePayout($payoutID);

        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Deleted successfully'),
        ]);

    }
    public function _changeStatusPayout(Request $request){
        $payoutID = request()->get('payoutID');
        $payoutEncrypt = request()->get('payoutEncrypt');

        $status = request()->get('status', '');

        if(!hh_compare_encrypt($payoutID, $payoutEncrypt)){
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This item is not available')
            ]);
        }

        if(!in_array($status, ['pending', 'completed'])){
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This action is invalid')
            ]);
        }
        $payout_model = new Payout();
        $payout_item = $payout_model->getPayout($payoutID);
        if(is_null($payout_item)){
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This action is invalid')
            ]);
        }
        $update = $payout_model->updatePayout($payoutID, ['status' => $status]);
        if(!is_null($update)){
            do_action('hh_change_payout_status', $payoutID, $status);
            return $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Updated successfully'),
                'reload' => true
            ]);
        }
        return $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('This action is invalid')
        ]);
    }
    public function updateEarning($booking_id)
    {
        $bookingObject = get_booking($booking_id);

        $earning_model = new Earning();
        $booking_model = new Booking();

        $ownerID = $bookingObject->owner;
        $earningItem = $earning_model->getEarning($ownerID);

        $amount = $booking_model->getTotalAmountByStatus(['completed', 'incomplete'], $ownerID);
        $net = $booking_model->getNetAmount($ownerID);
        $payout = isset($earningItem->payout) ? $earningItem->payout : 0;
        $commission = (float)get_option('partner_commission', 0);
        $net -= ($net * $commission / 100);
        $balance = $net - $payout;
        if ($earningItem) {
            $earning_model->updateEarning($earningItem->user_id, [
                'amount' => $amount,
                'net_amount' => $net,
                'payout' => $payout,
                'balance' => $balance
            ]);
        } else {
            $earning_model->insertEarning([
                'user_id' => $ownerID,
                'amount' => $amount,
                'payout' => $payout,
                'balance' => $balance,
            ]);
        }
    }

    public function getEarning($user_id)
    {
        $earning_model = new Earning();

        $default = [
            'amount' => 0,
            'net_amount' => 0,
            'balance' => 0,
            'payout' => 0
        ];
        $data = $earning_model->getEarning($user_id);
        return is_null($data) ? $default : (array)$data;
    }

    public function _allPayout(Request $request, $page = 1){
        $folder = $this->getFolder();
        $search = request()->get('_s');
        $orderBy = request()->get('orderby', 'payout_id');
        $order = request()->get('order', 'desc');
        $status = request()->get('status', '');
        $data = [
            'search' => $search,
            'page' => $page,
            'orderby' => $orderBy,
            'order' => $order,
            'status' => $status
        ];
        if (!is_admin()) {
            $data['user_id'] = get_current_user_id();
        }
        $payout_model = new Payout();
        $allPayouts = $payout_model->getAllPayout($data);

        return view("dashboard.screens.{$folder}.payout", ['bodyClass' => 'hh-dashboard', 'allPayouts' => $allPayouts]);
    }

    public static function get_inst()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}
