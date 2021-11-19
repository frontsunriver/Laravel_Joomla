<?php

use ReallySimpleJWT\Token;

function api_token_valid($token)
{
    $secret = 'sec!ReT423*&';
    $token_expired = Token::validate($token, $secret);

    $user = new \App\Models\User();

    return (!$token_expired) ? false : $user->getUserByApiToken($token);
}

function get_user_by_access_token($token)
{
    return api_token_valid($token);
}

function create_api_token($user_id)
{
    $lifetime = get_api_lifetime();
    $secret = 'sec!ReT423*&';
    $expiration = time() + $lifetime;
    $issuer = 'localhost';

    return Token::create($user_id, $secret, $expiration, $issuer);
}

function get_api_lifetime()
{
    $api_lifetime = (int)get_opt('api_lifetime', 30);
    $api_lifetime_type = get_opt('api_lifetime_type', 'day');
    switch ($api_lifetime_type) {
        case 'day':
        default:
            return $api_lifetime * 24 * 60 * 60;
        case 'hour':
            return $api_lifetime * 60 * 60;
        case 'minute':
            return $api_lifetime * 60;
        case 'seconds':
            return $api_lifetime;
    }
}
