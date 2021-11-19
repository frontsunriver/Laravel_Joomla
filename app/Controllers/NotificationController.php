<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Sentinel;

class NotificationController extends Controller
{
    public function _getNotifications(Request $request)
    {
        $user_id = request()->get('user_id');
        $user_encrypt = request()->get('user_encrypt');

        if (hh_compare_encrypt($user_id, $user_encrypt) && $user_id == get_current_user_id()) {
            $html = '';

            $time = time();
            $noti_model = new Notification();

            $notifications = apply_filters('awebooking_notification_alert_box', $noti_model->allNotifications());

            foreach ($notifications['results'] as $noti_item) {
                $html .= '<div class="dropdown-item notify-item"><div class="notify-icon notify-' . $noti_item->type . '">';
                if ($noti_item->type == 'booking') {
                    $html .= '<i class="fe-calendar"></i>';
                } elseif ($noti_item->type == 'global') {
                    $html .= '<i class="fe-shield"></i>';
                }
                $html .= '</div>';
                $html .= '<p class="notify-details">' . balanceTags($noti_item->title) . '</p>
                    <p class="text-muted mb-0 user-msg">
                        <small>' . balanceTags(get_time_since($noti_item->created_at)) . '</small>
                    </p>
                </div>';
            }
            $html = apply_filters('awebooking_notification_alert_box_html', $html, $user_id);

            if (empty($html)) {
                $html .= '<p class="text-muted text-center">' . __('No notification yet') . '</p>';
            }

            update_user_meta($user_id, 'last_check_notification', $time);

            do_action('awebooking_get_notifications');

            return $this->sendJson([
                'status' => 1,
                'notifications' => $html
            ]);
        }
        return [
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Can not get notification')
        ];
    }

    public function _deleteNotification(Request $request)
    {
        $notiID = request()->get('notiID');
        $notiEncrypt = request()->get('notiEncrypt');

        if (hh_compare_encrypt($notiID, $notiEncrypt)) {
            $noti_model = new Notification();
            $deleted = $noti_model->deleteNotification($notiID);
            if ($deleted) {
                return $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Deleted successfully'),
                ]);
            }
        }
        return $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Can not delete this notification')
        ]);
    }

    public function allNotifications($args)
    {
        $noti_model = new Notification();
        $allNotifications = $noti_model->allNotifications($args);

        return $allNotifications;
    }

    public function _allNotifications(Request $request, $page = 1)
    {
        $folder = $this->getFolder();

        $args = [
            'page' => $page
        ];

        $noti_model = new Notification();
        $allNotifications = $noti_model->allNotifications($args);
        return view("dashboard.screens.{$folder}.notifications", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'allNotifications' => $allNotifications]);
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
