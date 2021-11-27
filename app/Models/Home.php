<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Home extends Model
{
    protected $table = 'home';
    protected $primaryKey = 'post_id';

    public function updateAuthor($data, $author)
    {
        return DB::table($this->getTable())->where('author', $author)->update($data);
    }

    public function getAllIcalItems()
    {
        $sql = DB::table($this->getTable())->selectRaw('post_id, import_ical_url')->where('import_ical_url', '<>', '')->where('status', 'publish');
        $results = $sql->get();
        return is_object($results) ? $results : null;
    }

    public function countHomeInHomeType($home_type_id)
    {
        $sql = DB::table($this->getTable())->selectRaw("COUNT(*)");
        $sql->where('home_type', $home_type_id);
        $sql->whereRaw("status = 'publish'");
        $results = $sql->count();
        return $results;
    }

    public function getSearchResult($data)
    {
        $default = [
            'page' => 1,
            'lat' => '0',
            'lng' => '0',
            'address' => '',
            'checkIn' => '',
            'checkOut' => '',
            'checkInTime' => '',
            'checkOutTime' => '',
            'startTime' => '12:00 AM',
            'endTime' => '11:30 PM',
            'bookingType' => '',
            'num_adults' => 0,
            'num_children' => 0,
            'num_infants' => 0,
            'price_filter' => '',
            'home-type' => '',
            'home-amenity' => '',
            'home-facilites' => '',
            'bedrooms' => '',
            'bathrooms' => '',
            'first_minute' => '',
            'last_minute' => '',
            'number' => posts_per_page()
        ];
        $data = wp_parse_args($data, $default);
        $number = intval($data['number']);

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS *");

        if (!empty($data['lat']) && !empty($data['lng']) && $data['lat'] != 'NaN') {
            $distance = get_option('home_search_radius', '25');
            $data['lat'] = esc_sql($data['lat']);
            $data['lng'] = esc_sql($data['lng']);
            $sql->selectRaw("( 6371 * acos( cos( radians({$data['lat']}) ) * cos( radians( home.location_lat ) ) * cos( radians( home.location_lng ) - radians({$data['lng']}) ) + sin( radians({$data['lat']}) ) * sin( radians( home.location_lat ) ) ) ) AS distance");
            $sql->orHavingRaw("distance <= " . $distance);
            $sql->orderByDesc('distance');
        } elseif (!empty($data['address'])) {
            $address = urldecode($data['address']);
            $data['address'] = esc_sql($data['address']);
            $sql->whereRaw("home.location_city LIKE '%{$address}%'");
            $sql->orderByDesc('home.post_id');
        }
        
        if(!empty($data['bedrooms']) && ($data['bedrooms'] != '0')){
            $val = intval($data['bedrooms']);
            if($val == 6){
                $sql->whereRaw(" home.number_of_bedrooms >= {$val}");
            }else {
                $sql->whereRaw(" home.number_of_bedrooms = {$val}");
            }
        }

        if(!empty($data['bathrooms'])){
            $val = intval($data['bathrooms']);
            if($val == 3){
                $sql->whereRaw("home.number_of_bathrooms >= {$val}");
            }else {
                $sql->whereRaw("home.number_of_bathrooms = {$val}");
            }
            
        }

        if (!empty($data['num_adults']) || !empty($data['num_children'])) {
            $number_of_guest = intval($data['num_adults']) + intval($data['num_children']);
            $sql->whereRaw("number_of_guest >= {$number_of_guest}");
        }

        

        if (!empty($data['price_filter'])) {
            $min_max = get_origin_filter_price($data['price_filter']);
            $sql->leftJoin('home_price', function($join) use ($min_max){
                $where_home_price = "";
                if (!empty($data['first_minute'])) {
                    $where_home_price .= " and home_price.first_minute = 'on'";
                }
        
                if (!empty($data['last_minute'])) {
                    $where_home_price .= " and home_price.last_minute = 'on'";
                }
                $join->on('home.post_id', '=', 'home_price.home_id');
                $join->whereRaw("
                    (home_price.price_per_night >= {$min_max['min']} AND home_price.price_per_night <= {$min_max['max']} OR
                    home.base_price >= {$min_max['min']} AND home.base_price <= {$min_max['max']}) {$where_home_price}
                ");
            });
        }

        

        if (empty($data['bookingType'])) {
            if (!empty($data['checkIn']) && !empty($data['checkOut'])) {
                $check_in = strtotime($data['checkIn']);
                $check_out = strtotime('-1 day', strtotime($data['checkOut']));
                $sql->selectRaw("COUNT(home_availability.home_id) as total_id");
                $sql->leftJoin('home_availability', function ($join) use ($check_in, $check_out) {
                    $join->on('home.post_id', '=', 'home_availability.home_id');
                    $join->whereRaw("
                    (
                        (start_date >= {$check_in} AND end_date <= {$check_out}) OR
                        (start_date <= {$check_in} AND end_date >= {$check_in}) OR
                        (start_date <= {$check_out} AND end_date >= {$check_out})
                     )
                ");
                });
                $sql->groupBy(['home.post_id']);
                $sql->havingRaw("total_id = 0");
            }
            $sql->whereIn('home.booking_type', ['per_night', 'per_hour']);
        }
        if (!empty($data['checkIn']) && !empty($data['checkOut']) && $data['bookingType'] == 'per_night') {
            $check_in = strtotime($data['checkIn']);
            $check_out = strtotime('-1 day', strtotime($data['checkOut']));
            $sql->selectRaw("COUNT(home_availability.home_id) as total_id");
            $sql->leftJoin('home_availability', function ($join) use ($check_in, $check_out) {
                $join->on('home.post_id', '=', 'home_availability.home_id');
                $join->whereRaw("
                    (
                        (start_date >= {$check_in} AND end_date <= {$check_out}) OR
                        (start_date <= {$check_in} AND end_date >= {$check_in}) OR
                        (start_date <= {$check_out} AND end_date >= {$check_out})
                     )
                ");
            });
            $sql->groupBy(['home.post_id']);
            $sql->havingRaw("total_id = 0");
            $sql->where('home.booking_type', $data['bookingType']);
        }
        if (!empty($data['checkInTime']) && !empty($data['checkOutTime']) && $data['bookingType'] == 'per_hour') {
            $check_in = strtotime($data['checkInTime'] . ' ' . urldecode($data['startTime']));
            $check_out = strtotime($data['checkOutTime'] . ' ' . urldecode($data['endTime']));
            if ($check_in < $check_out) {
                $sql->selectRaw("COUNT(home_availability.home_id) as total_id");
                $sql->leftJoin('home_availability', function ($join) use ($check_in, $check_out) {
                    $join->on('home.post_id', '=', 'home_availability.home_id');
                    $join->whereRaw("
                    (
                        (start_time >= {$check_in} AND end_time <= {$check_out}) OR
                        (start_time <= {$check_in} AND end_time >= {$check_in}) OR
                        (start_time <= {$check_out} AND end_time >= {$check_out})
                     )
                    ");
                });
                $sql->groupBy(['home.post_id']);
                $sql->havingRaw("total_id = 0");
            }
            $sql->where('home.booking_type', $data['bookingType']);
        }


        if (!empty($data['home-type'])) {
            $data['home-type'] = esc_sql($data['home-type']);
            $sql->whereRaw("home.home_type IN ({$data['home-type']})");
        }

        if (!empty($data['home-amenity'])) {
            $amen_arr = explode(',', $data['home-amenity']);
            $sql_amen = [];
            foreach ($amen_arr as $k => $v) {
                array_push($sql_amen, "( FIND_IN_SET({$v}, home.amenities) )");
            }
            if (!empty($sql_amen)) {
                $sql->whereRaw("(" . implode(' OR ', $sql_amen) . ")");
            }
        }

        if (!empty($data['home-facilities'])) {
            // $amen_arr = explode(',', $data['home-facilities']);
            $amen_arr = json_decode($data['home-facilities']);
            $sql_amen = [];
            foreach ($amen_arr as $k => $v) {
                foreach ($v as $value) {
                    array_push($sql_amen, "( FIND_IN_SET({$v}, home.facilities) )");
                }
            }
            if (!empty($sql_amen)) {
                $sql->whereRaw("(" . implode(' OR ', $sql_amen) . ")");
            }
        }

        $sql->whereRaw("status = 'publish'");

        $offset = ($data['page'] - 1) * $number;
        $sql->limit($number)->offset($offset);

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function listOfHomes($data)
    {
        $default = [
            'id' => '',
            'location' => [],
            'checkIn' => '',
            'checkOut' => '',
            'page' => 1,
            'orderby' => 'post_id',
            'order' => 'desc',
            'is_featured' => '',
            'not_in' => [],
            'number' => posts_per_page(),
        ];

        $data = wp_parse_args($data, $default);
        $number = $data['number'];
        $is_featured = $data['is_featured'];

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS home.*")->where('home.status', 'publish')->orderBy($data['orderby'], $data['order']);

        if (!empty($data['id'])) {
            $sql->whereRaw("home.post_id IN ({$data['id']})");
        }
        if (!empty($data['not_in']) && is_array($data['not_in'])) {
            $not_in = implode(',', $data['not_in']);
            $sql->whereRaw("home.post_id NOT IN ({$not_in})");
        }
        if (!empty($data['location'])) {
            $lat = (isset($data['location']['lat'])) ? (float)$data['location']['lat'] : 0;
            $lng = (isset($data['location']['lng'])) ? (float)$data['location']['lng'] : 0;
            $radius = (isset($data['location']['radius'])) ? (float)$data['location']['radius'] : get_option('home_search_radius', '25');
            $sql->selectRaw("( 6371 * acos( cos( radians({$lat}) ) * cos( radians( home.location_lat ) ) * cos( radians( home.location_lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( home.location_lat ) ) ) ) AS distance");
            $sql->groupBy('home.post_id')->having("distance", '<=', $radius);
        }
        if (!empty($is_featured)) {
            $sql->where('is_featured', 'on');
        }
        if ($number != -1) {
            $offset = ($data['page'] - 1) * $number;
            $sql->limit($number)->offset($offset);
        }
        $results = $sql->get();

        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getAllHomes($data)
    {
        $default = [
            'search' => '',
            'page' => 1,
            'status' => [],
            'orderby' => 'post_id',
            'order' => 'desc',
            'author' => '',
            'number' => posts_per_page()
        ];
        $data = wp_parse_args($data, $default);
        $number = $data['number'];

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS home.*")->orderBy($data['orderby'], $data['order']);
        if (!is_admin()) {
            $sql->where('author', get_current_user_id());
        } else {
            if (!empty($data['author'])) {
                $sql->where('author', $data['author']);
            }
        }
        if ($number != -1) {
            $offset = ($data['page'] - 1) * $number;
            $sql->limit($number)->offset($offset);
        }

        if (!empty($data['search'])) {
            $data['search'] = esc_sql($data['search']);
            if (is_numeric($data['search'])) {
                $sql->where('home.post_id', $data['search']);
            } else {
                $sql->whereRaw("(home.post_title LIKE '%{$data['search']}%' OR home.post_content LIKE '%{$data['search']}%')");
            }
        }

        if (!empty($data['status']) && is_array($data['status'])) {
            $sql->whereIn('home.status', $data['status']);
        } else {
            $sql->whereNotIn('home.status', ['revision']);
        }

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getById($home_id)
    {
        $post = DB::table($this->table)->where('post_id', $home_id)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function getByName($home_name)
    {
        $post = DB::table($this->table)->where('post_slug', $home_name)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function getMinMaxPrice()
    {
        $result = DB::table($this->getTable())->selectRaw("min(base_price) as min, max(base_price) as max")->get()->first();
        if (!empty($result) && is_object($result)) {
            if ($result->min == $result->max) {
                $result->min = 0;
            }
            return (array)$result;
        }
        return ['min' => 0, 'max' => 500];
    }

    public function review_count()
    {
        return 0;
    }

    public function updateStatus($home_id, $new_status = '')
    {
        return DB::table($this->getTable())->where('post_id', $home_id)->update(['status' => $new_status]);
    }

    public function updateMultiHome($data, $post_id)
    {
        return DB::table($this->getTable())->whereIn('post_id', $post_id)->update($data);
    }

    public function updateHome($data, $home_id)
    {
        return DB::table($this->getTable())->where('post_id', $home_id)->update($data);
    }

    public function createHome($data = [])
    {
        $time = time();
        $default = [
            'post_title' => sprintf(__('New Home - %s'), $time),
            'post_slug' => sprintf(__('new-home-%s'), $time),
            'created_at' => $time,
            'author' => get_current_user_id(),
            'status' => 'revision'
        ];

        $data = wp_parse_args($data, $default);
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function duplicate($home_id)
    {

        $home = $this->getById($home_id);
        if (!is_null($home)) {
            $home->post_id = null;
            $home->status = 'pending';
            $home->post_title = $home->post_title . '-' . time();
            $home->post_slug = $home->post_slug . '-' . time();
            $new_home = DB::table($this->getTable())->insertGetId((array)$home);
            if ($new_home) {
                return DB::insert(DB::raw("INSERT INTO term_relation ( term_id, service_id, post_type ) SELECT
                    t.term_id, {$new_home}, 'home'
                    FROM
                        term_relation AS t
                    WHERE
                        t.service_id = {$home_id}"));
            }
        }
        return null;
    }

    public function deleteHomeItem($home_id)
    {
        DB::table('home_availability')->where('home_id', $home_id)->delete();
        DB::table('home_price')->where('home_id', $home_id)->delete();
        DB::table('term_relation')->where('service_id', $home_id)->where('post_type', 'home')->delete();
        DB::table('comments')->where('post_id', $home_id)->where('post_type', 'home')->delete();

        return DB::table($this->getTable())->where('post_id', $home_id)->delete();
    }

    public function searchCitiesList($query) {
        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS *");
        $sql->whereRaw("home.location_city LIKE '%{$query}%'");
        $results = $sql->get();
        return $results;
    }

}
