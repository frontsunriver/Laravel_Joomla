<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Sentinel;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function deleteRelatedData($user_id, $assign = false)
    {
        $data = [
            ['table' => 'activations', 'column' => 'user_id'],
            ['table' => 'notification', 'column' => 'user_from'],
            ['table' => 'notification', 'column' => 'user_to'],
            ['table' => 'persistences', 'column' => 'user_id'],
            ['table' => 'reminders', 'column' => 'user_id'],
            ['table' => 'role_users', 'column' => 'user_id'],
            ['table' => 'sessions', 'column' => 'user_id'],
            ['table' => 'throttle', 'column' => 'user_id'],
            ['table' => 'payout', 'column' => 'user_id'],
            ['table' => 'coupon', 'column' => 'author'],
            ['table' => 'usermeta', 'column' => 'user_id'],
            ['table' => 'users', 'column' => 'id'],
        ];
        if (!$assign) {
            $data[] = ['table' => 'media', 'column' => 'author'];
        }

        foreach ($data as $item) {
            DB::table($item['table'])->where($item['column'], $user_id)->delete();
        }
    }

    public function allPartnerRequest($data)
    {
        $default = [
            'search' => '',
            'orderby' => 'id',
            'order' => 'desc',
            'number' => posts_per_page(),
            'page' => 1
        ];
        $data = wp_parse_args($data, $default);

        $query = DB::table($this->getTable())->selectRaw('SQL_CALC_FOUND_ROWS users.*, roles.slug as role_slug, roles.name as role_name')->join('role_users', 'users.id', '=', 'role_users.user_id', 'inner')
            ->join('roles', 'role_users.role_id', '=', 'roles.id', 'inner');
        $number = $data['number'];
        if (!empty($number)) {
            $offset = ($data['page'] - 1) * $number;
            $query->limit($number)->offset($offset);
        }

        if (!empty($data['search'])) {
            $search = esc_sql($data['search']);
            if (is_numeric($data['search'])) {
                $query->where('users.id', $search);
            } else {
                $query->whereRaw("(users.email LIKE '%{$search}%' OR users.first_name LIKE '%{$search}%' OR users.last_name LIKE '%{$search}%')");
            }
        }
        $query->where('roles.id', 3);

        $query->whereIn('users.request', ['request_a_partner', 'request_a_customer']);

        $query->orderBy($data['orderby'], $data['order']);

        $results = $query->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function allUsers($data)
    {
        $default = [
            'search' => '',
            'orderby' => 'id',
            'order' => 'desc',
            'role' => '',
            'number' => posts_per_page(),
            'page' => 1
        ];
        $data = wp_parse_args($data, $default);

        $query = DB::table($this->getTable())->selectRaw('SQL_CALC_FOUND_ROWS users.*, roles.slug as role_slug, roles.name as role_name')->join('role_users', 'users.id', '=', 'role_users.user_id', 'inner')
            ->join('roles', 'role_users.role_id', '=', 'roles.id', 'inner')->where('role_users.role_id', '!=', 4);
        $number = $data['number'];
        if (!empty($number)) {
            $offset = ($data['page'] - 1) * $number;
            $query->limit($number)->offset($offset);
        }

        if (!empty($data['search'])) {
            $search = esc_sql($data['search']);
            if (is_numeric($data['search'])) {
                $query->where('users.id', $search);
            } else {
                $query->whereRaw("(users.email LIKE '%{$search}%' OR users.first_name LIKE '%{$search}%' OR users.last_name LIKE '%{$search}%')");
            }
        }
        if (!empty($data['role'])) {
            $query->where('roles.id', $data['role']);
        }

        $query->orderBy($data['orderby'], $data['order']);

        $results = $query->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getUserByApiToken($token)
    {
        $result = DB::table('usermeta')->select('user_id')->where('meta_key', 'access_token')->where('meta_value', $token)->get()->first();
        $user_id = is_object($result) ? $result->user_id : 0;

        return get_user_by_id($user_id);
    }

    public function getActivationCode($user_id)
    {
        $query = DB::table('activations')->select('code')->where('user_id', $user_id);
        $result = $query->get()->first();

        return is_object($result) ? $result->code : null;
    }

    public function getUserRole($user_id)
    {
        $query = DB::table('roles')->selectRaw('roles.id, roles.slug, roles.name')->join('role_users', 'roles.id', '=', 'role_users.role_id')->where('role_users.user_id', $user_id);
        $result = $query->get()->first();
        return (is_object($result)) ? $result : null;
    }

    public function updateUser($user_id, $data)
    {
        return DB::table($this->getTable())->where('id', $user_id)->update($data);
    }

    public function updateUserRole($user_id, $role_id)
    {
        DB::table('role_users')->where('user_id', $user_id)->delete();
        $user = get_user_by_id($user_id);
        $role = Sentinel::findRoleById($role_id);
        if ($role && $user) {
            $role->users()->attach($user);
            return true;
        }
        return false;
    }

    public function updateUserMeta($user_id, $meta_key, $meta_value = '')
    {
        if (is_array($meta_key)) {
            foreach ($meta_key as $key => $value) {
                DB::table('usermeta')->updateOrInsert(['user_id' => $user_id, 'meta_key' => $key], ['meta_value' => maybe_serialize($value)]);
            }
        } else {
            return DB::table('usermeta')->updateOrInsert(['user_id' => $user_id, 'meta_key' => $meta_key], ['meta_value' => maybe_serialize($meta_value)]);
        }
    }

    public function deleteUserMetaByWhere($where = [])
    {
        return DB::table('usermeta')->where($where)->delete();
    }

    public function getRoleByName($role_name = 'customer')
    {
        $role = DB::table('roles')->where('slug', $role_name)->get()->first();
        return (is_object($role)) ? $role : null;
    }

    public function getUserIDByToken($token)
    {
        $user_meta = DB::table('usermeta')->where([
            'meta_key' => 'access_token',
            'meta_value' => $token
        ])->first();
        if($user_meta){
             return $user_meta->user_id;
        }
        return false;
    }

    public function getUserMeta($user_id, $meta_key)
    {
        return DB::table('usermeta')->where('user_id', $user_id)->where('meta_key', $meta_key)->get()->first();
    }

    public function getUserAllMeta($user_id)
    {
        return DB::table('usermeta')->where('user_id', $user_id)->get();
    }

    public function getAllRoles()
    {
        $results = DB::table('roles')->get();
        return (is_object($results) && $results->count()) ? $results : null;
    }

    public function getAllUserList() {
        $results = DB::table('users')->selectRaw('users.*')->join('role_users', 'users.id', '=', 'role_users.user_id')->where('role_users.role_id', '!=', 3)->get();
        return (is_object($results) && $results->count()) ? $results : null;
    }
}
