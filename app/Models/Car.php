<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Car extends Model
{
    protected $table = 'car';
    protected $primaryKey = 'post_id';

    public function updateAuthor($data, $author)
    {
        return DB::table($this->getTable())->where('author', $author)->update($data);
    }

    public function countCarInCarType($car_type_id)
    {
        $sql = DB::table($this->getTable())->selectRaw("COUNT(*)");
        $sql->where('car_type', $car_type_id);
        $sql->whereRaw("status = 'publish'");
        $results = $sql->count();
        return $results;
    }

    public function listOfCars($data)
    {
        $default = [
            'id' => '',
            'location' => [],
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

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS car.*")->where('car.status', 'publish')->orderBy($data['orderby'], $data['order']);

        if (!empty($data['id'])) {
            $sql->whereRaw("car.post_id IN ({$data['id']})");
        }
        if (!empty($data['not_in']) && is_array($data['not_in'])) {
            $not_in = implode(',', $data['not_in']);
            $sql->whereRaw("car.post_id NOT IN ({$not_in})");
        }
        if (!empty($data['location'])) {
            $lat = (isset($data['location']['lat'])) ? (float)$data['location']['lat'] : 0;
            $lng = (isset($data['location']['lng'])) ? (float)$data['location']['lng'] : 0;
            $radius = (isset($data['location']['radius'])) ? (float)$data['location']['radius'] : get_option('car_search_radius', '25');
            $sql->selectRaw("( 6371 * acos( cos( radians({$lat}) ) * cos( radians( car.location_lat ) ) * cos( radians( car.location_lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( car.location_lat ) ) ) ) AS distance");
            $sql->groupBy('car.post_id')->having("distance", '<=', $radius);
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

    public function checkAvailable($data)
    {
        $default = [
            'carID' => '',
            'checkIn' => '',
            'checkOut' => '',
            'checkInTime' => '',
            'checkOutTime' => '',
            'quantity' => 0,
            'number' => 0
        ];

        $data = wp_parse_args($data, $default);

        if (!empty($data['checkIn']) && !empty($data['checkOut'])) {
            $car_id = $data['carID'];
            $quantity = $data['quantity'];
            $number = $data['number'];
            $check_in = strtotime($data['checkIn']);
            $check_out = strtotime($data['checkOut']);
            $check_in_time = strtotime($data['checkIn'] . ' ' . urldecode($data['checkInTime']));
            $check_out_time = strtotime($data['checkOut'] . ' ' . urldecode($data['checkOutTime']));

            $carPriceModel = new CarPrice();
            $bookingModel = new Booking();

            $res_unavailable = $carPriceModel->selectRaw("COUNT(*) AS total_id")->where('car_id', $car_id)->whereRaw("(available = 'off')
                    AND
                    (
                        (start_date <= {$check_in} AND end_date >= {$check_out})
                        OR
                        (start_date >= {$check_in} AND end_date <= {$check_out})
                        OR
                        (start_date <= {$check_in} AND end_date >= {$check_in})
                        OR
                        (start_date <= {$check_out} AND end_date >= {$check_out})
                    )")->get()->first();

            if ($res_unavailable['total_id'] > 0) {
                return [
                    'status' => 1,
                    'available' => 0
                ];
            }

            if (!empty($quantity) && $quantity > 0) {
                $booking_type = get_car_booking_type();
                if ($booking_type == 'hour') {
                    $res_booked = $bookingModel->selectRaw("*, SUM(number) AS total_booking")->where('service_id', $car_id)->whereRaw("status IN ('completed', 'incomplete')
                    AND
                    service_type = 'car'
                    AND
                    (
                        (start_time <= {$check_in_time} AND end_time >= {$check_out_time})
                        OR
                        (start_time >= {$check_in_time} AND end_time <= {$check_out_time})
                        OR
                        (start_time <= {$check_in_time} AND end_time >= {$check_in_time})
                        OR
                        (start_time <= {$check_out_time} AND end_time >= {$check_out_time})
                    )")->get()->first();
                } else {
                    $res_booked = $bookingModel->selectRaw("*, SUM(number) AS total_booking")->where('service_id', $car_id)->whereRaw("status IN ('completed', 'incomplete')
                    AND
                    service_type = 'car'
                    AND
                    (
                        (start_date <= {$check_in} AND end_date >= {$check_out})
                        OR
                        (start_date >= {$check_in} AND end_date <= {$check_out})
                        OR
                        (start_date <= {$check_in} AND end_date >= {$check_in})
                        OR
                        (start_date <= {$check_out} AND end_date >= {$check_out})
                    )")->get()->first();
                }

                if ($res_booked['total_booking'] >= $quantity) {
                    return [
                        'status' => 2,
                        'available' => 0
                    ];
                } else {
                    if ($quantity - $res_booked['total_booking'] < $number) {
                        return [
                            'status' => 3,
                            'available' => ($quantity - $res_booked['total_booking'])
                        ];
                    }
                }
            }
        }
        return [
            'status' => 4,
            'available' => $data['quantity']
        ];
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
            'price_filter' => '',
            'car-type' => '',
            'car-feature' => '',
            'number' => posts_per_page()
        ];

        $data = wp_parse_args($data, $default);

        $number = intval($data['number']);

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS *");

        if (!empty($data['lat']) && !empty($data['lng'])) {
            $distance = get_option('car_search_radius', '25');
            $data['lat'] = esc_sql($data['lat']);
            $data['lng'] = esc_sql($data['lng']);
            $sql->selectRaw("( 6371 * acos( cos( radians({$data['lat']}) ) * cos( radians( car.location_lat ) ) * cos( radians( car.location_lng ) - radians({$data['lng']}) ) + sin( radians({$data['lat']}) ) * sin( radians( car.location_lat ) ) ) ) AS distance");
            $sql->orHavingRaw("distance <= " . $distance);
            $sql->orderByDesc('distance');
        } elseif (!empty($data['address'])) {
            $address = urldecode($data['address']);
            $data['address'] = esc_sql($data['address']);
            $sql->whereRaw("car.location_address LIKE '%{$address}%'");
            $sql->orderByDesc('car.post_id');
        }

        if (!empty($data['price_filter'])) {
            $min_max = get_origin_filter_price($data['price_filter']);
            $sql->whereRaw("base_price >= {$min_max['min']} AND base_price <= {$min_max['max']}");
        }

        if (!empty($data['checkIn']) && !empty($data['checkOut'])) {
            $check_in = strtotime($data['checkIn']);
            $check_out = strtotime($data['checkOut']);

            //Join with car price table
            $unavailable_in_car_price_sql = "SELECT car_id
                FROM car_price
                WHERE
                    (available = 'off')
                    AND
                    (
                        (car_price.start_date <= {$check_in} AND car_price.end_date >= {$check_out})
                        OR
                        (car_price.start_date >= {$check_in} AND car_price.end_date <= {$check_out})
                        OR
                        (car_price.start_date <= {$check_in} AND car_price.end_date >= {$check_in})
                        OR
                        (car_price.start_date <= {$check_out} AND car_price.end_date >= {$check_out})
                    )";

            $sql->whereRaw("car.post_id NOT IN ({$unavailable_in_car_price_sql})");

            $sql->selectRaw("SUM(booking.number) as total_booking");

            $booking_type = get_car_booking_type();
            if ($booking_type == 'hour') {
                //Check available with booking
                $check_in_time = strtotime($data['checkIn'] . ' ' . urldecode($data['checkInTime']));
                $check_out_time = strtotime($data['checkOut'] . ' ' . urldecode($data['checkOutTime']));

                $sql->leftJoin('booking', function ($join) use ($check_in_time, $check_out_time) {
                    $join->on('car.post_id', '=', 'booking.service_id');
                    $join->whereRaw("booking.status IN ('completed', 'incomplete')
                    AND service_type = 'car'
                    AND
                    (
                        (booking.start_time <= {$check_in_time} AND booking.end_time >= {$check_out_time})
                        OR
                        (booking.start_time >= {$check_in_time} AND booking.end_time <= {$check_out_time})
                        OR
                        (booking.start_time <= {$check_in_time} AND booking.end_time >= {$check_in_time})
                        OR
                        (booking.start_time <= {$check_out_time} AND booking.end_time >= {$check_out_time})
                    )");
                });
            } else {
                $sql->leftJoin('booking', function ($join) use ($check_in, $check_out) {
                    $join->on('car.post_id', '=', 'booking.service_id');
                    $join->whereRaw("booking.status IN ('completed', 'incomplete')
                    AND service_type = 'car'
                    AND
                    (
                        (booking.start_date <= {$check_in} AND booking.end_date >= {$check_out})
                        OR
                        (booking.start_date >= {$check_in} AND booking.end_date <= {$check_out})
                        OR
                        (booking.start_date <= {$check_in} AND booking.end_date >= {$check_in})
                        OR
                        (booking.start_date <= {$check_out} AND booking.end_date >= {$check_out})
                    )");
                });
            }
            $sql->groupBy(['car.post_id']);
            $sql->havingRaw(("(total_booking < car.quantity OR ISNULL(total_booking))"));
        }

        if (!empty($data['car-type'])) {
            $car_type_arr = explode(',', $data['car-type']);
            $sql_car_type = [];
            foreach ($car_type_arr as $k => $v) {
                array_push($sql_car_type, "( FIND_IN_SET({$v}, car.car_type) )");
            }
            if (!empty($sql_car_type)) {
                $sql->whereRaw("(" . implode(' OR ', $sql_car_type) . ")");
            }
        }

        if (!empty($data['car-feature'])) {
            $car_feature_arr = explode(',', $data['car-feature']);
            $sql_car_feature = [];
            foreach ($car_feature_arr as $k => $v) {
                array_push($sql_car_feature, "( FIND_IN_SET({$v}, car.features) )");
            }
            if (!empty($sql_car_feature)) {
                $sql->whereRaw("(" . implode(' OR ', $sql_car_feature) . ")");
            }
        }

        $sql->whereRaw("car.status = 'publish'");

        $offset = ($data['page'] - 1) * $number;
        $sql->limit($number)->offset($offset);

        $results = $sql->get();

        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getAllCars($data)
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

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS car.*")->orderBy($data['orderby'], $data['order']);

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
                $sql->where('car.post_id', $data['search']);
            } else {
                $sql->whereRaw("(car.post_title LIKE '%{$data['search']}%' OR car.post_content LIKE '%{$data['search']}%')");
            }
        }

        if (!empty($data['status']) && is_array($data['status'])) {
            $sql->whereIn('car.status', $data['status']);
        } else {
            $sql->whereNotIn('car.status', ['revision']);
        }

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function countExperienceInHomeType($experience_type_id)
    {
        $sql = DB::table($this->getTable())->selectRaw("COUNT(*)");
        $sql->where('experience_type', $experience_type_id);
        $sql->whereRaw("status = 'publish'");
        $results = $sql->count();
        return $results;
    }

    public function getById($car_id)
    {
        $post = DB::table($this->table)->where('post_id', $car_id)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function getByName($home_name)
    {
        $post = DB::table($this->table)->where('post_slug', $home_name)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function updateStatus($car_id, $new_status = '')
    {
        return DB::table($this->getTable())->where('post_id', $car_id)->update(['status' => $new_status]);
    }

    public function updateMultiCar($data, $post_id)
    {
        return DB::table($this->getTable())->whereIn('post_id', $post_id)->update($data);
    }

    public function updateCar($data, $car_id)
    {
        return DB::table($this->getTable())->where('post_id', $car_id)->update($data);
    }

    public function createCar($data = [])
    {
        $time = time();
        $default = [
            'post_title' => sprintf(__('New Car - %s'), $time),
            'post_slug' => sprintf(__('new-car-%s'), $time),
            'created_at' => $time,
            'author' => get_current_user_id(),
            'status' => 'revision',
            'quantity' => 1
        ];

        $data = wp_parse_args($data, $default);
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function deleteCarItem($car_id)
    {
        DB::table('car_price')->where('car_id', $car_id)->delete();
        DB::table('term_relation')->where('service_id', $car_id)->where('post_type', 'car')->delete();
        DB::table('comments')->where('post_id', $car_id)->where('post_type', 'car')->delete();

        return DB::table($this->getTable())->where('post_id', $car_id)->delete();
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

    public function duplicate($car_id)
    {

        $car = $this->getById($car_id);
        if (!is_null($car)) {
            $car->post_id = null;
            $car->status = 'pending';
            $car->post_title = $car->post_title . '-' . time();
            $car->post_slug = $car->post_slug . '-' . time();
            $new_car = DB::table($this->getTable())->insertGetId((array)$car);
            if ($new_car) {
                return DB::insert(DB::raw("INSERT INTO term_relation ( term_id, service_id, post_type ) SELECT
                    t.term_id, {$new_car}, 'car'
                    FROM
                        term_relation AS t
                    WHERE
                        t.service_id = {$car_id}"));
            }
        }
        return null;
    }
}
