<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Language extends Model
{
    protected $table = 'language';

    public function getAllLanguages($data = [])
    {
        $default = [
            'search' => '',
            'page' => 1,
            'orderby' => 'priority',
            'order' => 'ASC',
            'status' => '',
            'author' => '',
            'number' => posts_per_page()
        ];
        $data = wp_parse_args($data, $default);
        $number = $data['number'];
        $offset = ($data['page'] - 1) * $number;

        $sql = $this->selectRaw("SQL_CALC_FOUND_ROWS language.*")
            ->orderBy($data['orderby'], $data['order'])->limit($number)->offset($offset);

        if (!empty($data['search'])) {
            $data['search'] = esc_sql($data['search']);
            if (is_numeric($data['search'])) {
                $sql->where('language.id', $data['search']);
            } else {
                $sql->whereRaw("language.code LIKE '%{$data['search']}%' OR language.name LIKE '%{$data['search']}%'");
            }
        }
        if (!empty($data['status'])) {
            $sql->where('language.status', $data['status']);
        }

        $results = $sql->get();

        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];

    }

    public function updateLanguage($data, $language_id)
    {
        return $this->where('id', $language_id)->update($data);
    }

    public function getById($language_id)
    {
        return $this->where('id', $language_id)->get()->first();
    }

    public function deleteLanguage($language_id)
    {
        return $this->where('id', $language_id)->delete();
    }
}
