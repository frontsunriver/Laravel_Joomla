<?php

class AdminBar{
    public function __construct()
    {
        add_action('after_body_frontend', [$this, '_initAdminBar']);
    }

    public function _initAdminBar(){
        if(is_user_logged_in() && (is_admin() || is_partner())){
            echo view('frontend.components.admin-bar')->render();
        }
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
