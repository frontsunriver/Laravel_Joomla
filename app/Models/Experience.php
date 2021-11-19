<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Experience extends Model
{
    protected $table = 'experience';
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

    public function listOfExperiences($data)
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

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS experience.*")->where('experience.status', 'publish');

        if ($data['order'] == 'rand') {
            $sql->inRandomOrder();
        } else {
            $sql->orderBy($data['orderby'], $data['order']);
        }

        if (!empty($data['id'])) {
            $sql->whereRaw("experience.post_id IN ({$data['id']})");
        }
        if (!empty($data['not_in']) && is_array($data['not_in'])) {
            $not_in = implode(',', $data['not_in']);
            $sql->whereRaw("experience.post_id NOT IN ({$not_in})");
        }
        if (!empty($data['location'])) {
            $lat = (isset($data['location']['lat'])) ? (float)$data['location']['lat'] : 0;
            $lng = (isset($data['location']['lng'])) ? (float)$data['location']['lng'] : 0;
            $radius = (isset($data['location']['radius'])) ? (float)$data['location']['radius'] : get_option('experience_search_radius', '25');
            $sql->selectRaw("( 6371 * acos( cos( radians({$lat}) ) * cos( radians( experience.location_lat ) ) * cos( radians( experience.location_lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( experience.location_lat ) ) ) ) AS distance");
            $sql->groupBy('experience.post_id')->having("distance", '<=', $radius);
        }

        if (!empty($is_featured)) {
            $sql->where('experience.is_featured', 'on');
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

    public function getSearchResult($data)
    {
        $default = [
            'page' => 1,
            'lat' => '0',
            'lng' => '0',
            'address' => '',
            'checkIn' => '',
            'checkOut' => '',
            'num_adults' => 1,
            'num_children' => 0,
            'num_infants' => 0,
            'price_filter' => '',
            'experience-type' => '',
            'experience-languages' => '',
            'number' => posts_per_page()
        ];
        $data = wp_parse_args($data, $default);
        $number = intval($data['number']);

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS *");

        if (!empty($data['lat']) && !empty($data['lng'])) {
            $distance = get_option('experience_search_radius', '25');
            $data['lat'] = esc_sql($data['lat']);
            $data['lng'] = esc_sql($data['lng']);
            $sql->selectRaw("( 6371 * acos( cos( radians({$data['lat']}) ) * cos( radians( experience.location_lat ) ) * cos( radians( experience.location_lng ) - radians({$data['lng']}) ) + sin( radians({$data['lat']}) ) * sin( radians( experience.location_lat ) ) ) ) AS distance");
            $sql->orHavingRaw("distance <= " . $distance);
            $sql->orderByDesc('distance');
        } elseif (!empty($data['address'])) {
            $address = urldecode($data['address']);
            $data['address'] = esc_sql($data['address']);
            $sql->whereRaw("experience.location_address LIKE '%{$address}%'");
            $sql->orderByDesc('experience.post_id');
        }
        $guest = (int)$data['num_adults'] + (int)$data['num_children'] + (int)$data['num_infants'];

        if (!empty($data['price_filter'])) {
            $min_max = get_origin_filter_price($data['price_filter']);
            $sql->whereRaw("base_price >= {$min_max['min']} AND base_price <= {$min_max['max']}");
        }

        if (!empty($data['checkIn']) && !empty($data['checkOut'])) {
            $check_in = strtotime($data['checkIn']);
            $check_out = strtotime($data['checkOut']);
            $total_date = 0;
            for ($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)) {
                $total_date += $i;
            }
            $joinSub = DB::table('experience_price')->selectRaw('experience_id')->whereRaw("start_date BETWEEN {$check_in} AND {$check_out} AND max_people >= {$guest}");
            $sql->joinSub($joinSub, 'price', function ($join) {
                $join->on('experience.post_id', '=', 'price.experience_id');
            });
            $sql->whereRaw("experience.post_id not in (select avai.experience_id from (SELECT
                v.experience_id,
                sum( v.date ) AS total_date
            FROM
                experience_availability AS v
            WHERE
                v.date BETWEEN {$check_in}
                AND {$check_out}
            GROUP BY
                v.experience_id
            HAVING
                total_date = {$total_date}) as avai)");
        } else {
            $sql->where('number_of_guest', '>=', $guest);
        }


        if (!empty($data['experience-type'])) {
            $data['experience-type'] = esc_sql($data['experience-type']);
            $sql->whereRaw("experience.experience_type IN ({$data['experience-type']})");
        }

        if (!empty($data['experience-languages'])) {
            $amen_arr = explode(',', $data['experience-languages']);
            $sql_amen = [];
            foreach ($amen_arr as $k => $v) {
                array_push($sql_amen, "( FIND_IN_SET({$v}, experience.languages) )");
            }
            if (!empty($sql_amen)) {
                $sql->whereRaw("(" . implode(' OR ', $sql_amen) . ")");
            }
        }

        $sql->whereRaw("status = 'publish'");
        $sql->groupBy('experience.post_id');

        $offset = ($data['page'] - 1) * $number;
        $sql->limit($number)->offset($offset);

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
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

    public function countExperienceInExperienceType($experience_type_id)
    {
        $sql = DB::table($this->getTable())->selectRaw("COUNT(*)");
        $sql->where('experience_type', $experience_type_id);
        $sql->whereRaw("status = 'publish'");
        $results = $sql->count();
        return $results;
    }

    public function getById($experience_id)
    {
        $post = DB::table($this->table)->where('post_id', $experience_id)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function getByName($home_name)
    {
        $post = DB::table($this->table)->where('post_slug', $home_name)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function getAllExperiences($data)
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

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS experience.*");
        if ($data['order'] == 'rand') {
            $sql->inRandomOrder();
        } else {
            $sql->orderBy($data['orderby'], $data['order']);
        }
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
                $sql->where('experience.post_id', $data['search']);
            } else {
                $sql->whereRaw("(experience.post_title LIKE '%{$data['search']}%' OR experience.post_content LIKE '%{$data['search']}%')");
            }
        }

        if (!empty($data['status']) && is_array($data['status'])) {
            $sql->whereIn('experience.status', $data['status']);
        } else {
            $sql->whereNotIn('experience.status', ['revision']);
        }

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];

    }

    public function updateStatus($experience_id, $new_status = '')
    {
        return DB::table($this->getTable())->where('post_id', $experience_id)->update(['status' => $new_status]);
    }

    public function updateMultiExperience($data, $post_id)
    {
        return DB::table($this->getTable())->whereIn('post_id', $post_id)->update($data);
    }

    public function updateExperience($data, $experience_id)
    {
        return DB::table($this->getTable())->where('post_id', $experience_id)->update($data);
    }

    public function createExperience($data = [])
    {
        $time = time();
        $default = [
            'post_title' => sprintf(__('New Experience - %s'), $time),
            'post_slug' => sprintf(__('new-experience-%s'), $time),
            'created_at' => $time,
            'author' => get_current_user_id(),
            'status' => 'revision'
        ];

        $data = wp_parse_args($data, $default);
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function deleteExperienceItem($experience_id)
    {
        DB::table('experience_availability')->where('experience_id', $experience_id)->delete();
        DB::table('experience_price')->where('experience_id', $experience_id)->delete();
        DB::table('term_relation')->where('service_id', $experience_id)->where('post_type', 'experience')->delete();
        DB::table('comments')->where('post_id', $experience_id)->where('post_type', 'experience')->delete();

        return DB::table($this->getTable())->where('post_id', $experience_id)->delete();
    }

    public function duplicate($experience_id)
    {

        $experience = $this->getById($experience_id);
        if (!is_null($experience)) {
            $experience->post_id = null;
            $experience->status = 'pending';
            $experience->post_title = $experience->post_title . '-' . time();
            $experience->post_slug = $experience->post_slug . '-' . time();
            $new_experience = DB::table($this->getTable())->insertGetId((array)$experience);
            if ($new_experience) {
                return DB::insert(DB::raw("INSERT INTO term_relation ( term_id, service_id, post_type ) SELECT
                    t.term_id, {$new_experience}, 'experience'
                    FROM
                        term_relation AS t
                    WHERE
                        t.service_id = {$experience_id}"));
            }
        }
        return null;
    }
}
