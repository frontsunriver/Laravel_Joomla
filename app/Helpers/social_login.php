<?php
function has_social_login()
{
    $socials = [
        'facebook',
        'google'
    ];

    foreach ($socials as $social) {
        $enable = social_enable($social);
        if ($enable) {
            return true;
        }
        break;
    }
}

function social_enable($type = 'facebook')
{
    $enable = get_option($type . '_login', 'off');
    $api = '';
    switch ($type) {
        case 'facebook':
            $api = get_option($type . '_api', '');
            break;
        case 'google':
            $api = get_option($type . '_client_id', '');
            break;
    }
    return $enable == 'on' && !empty($api);
}
