<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Term extends Model
{
    protected $table = 'term';
    protected $primaryKey = 'term_id';

    public function getAllTerms($data = [])
    {
        $default = [
            'search' => '',
            'page' => 1,
            'orderby' => 'term_id',
            'order' => 'desc',
            'tax' => 'home-type',
            'number' => posts_per_page(),
            'author' => ''
        ];
        $data = wp_parse_args($data, $default);

        $number = $data['number'];
        $data['tax'] = esc_sql($data['tax']);
        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS term.*")->join('taxonomy', 'term.taxonomy_id', '=', 'taxonomy.taxonomy_id', 'inner')
            ->whereRaw("taxonomy.taxonomy_name = '{$data['tax']}'")->orderBy($data['orderby'], $data['order'])->groupBy(['term.term_id']);
        if ($number != -1) {
            $offset = ($data['page'] - 1) * $number;
            $sql->limit($number)->offset($offset);
        }

        if (!empty($data['search'])) {
            $data['search'] = esc_sql($data['search']);
            if (is_numeric($data['search'])) {
                $sql->where('term.term_id', $data['search']);
            } else {
                $sql->whereRaw("(term.term_title LIKE '%{$data['search']}%' OR term.term_name LIKE '%{$data['search']}%')");
            }
        }
        if (!empty($data['author'])) {
            $sql->where('term.author', $data['author']);
        }

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];

    }

    public function getTheTerms($post_id, $taxonomy = 'post-category')
    {
        $result = DB::table($this->table)->selectRaw('term.*, term_relation.service_id')->join('taxonomy', 'taxonomy.taxonomy_id', '=', 'term.taxonomy_id')->join('term_relation', 'term_relation.term_id', '=', 'term.term_id')
            ->where('term_relation.service_id', $post_id)->where('taxonomy.taxonomy_name', $taxonomy)->get();
        return (!empty($result) && is_object($result)) ? $result : null;
    }

    public function getTerms($taxonomy_id = 0)
    {
        $taxonomy_id = esc_sql($taxonomy_id);
        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS term.*")
            ->whereRaw("term.taxonomy_id = '{$taxonomy_id}'")->orderBy('term_title', 'asc');
        $results = $sql->get();

        return (!empty($results) && is_object($results)) ? $results : false;
    }

    public function getByName($term_name, $tax = '', $like = false)
    {
        if ($like) {
            if (empty($tax)) {
                $term = DB::table($this->table)->where('term_name', 'like', '%' . $term_name . '[:%')->get()->first();
            } else {
                $term = DB::table($this->table)->join('taxonomy', 'term.taxonomy_id', '=', 'taxonomy.taxonomy_id', 'inner')->where('term_title', 'like', '%' . $term_name . '[:%')->where('taxonomy_name', $tax)->get()->first();
            }
        } else {
            if (empty($tax)) {
                $term = DB::table($this->table)->where('term_name', $term_name)->get()->first();
            } else {
                $term = DB::table($this->table)->join('taxonomy', 'term.taxonomy_id', '=', 'taxonomy.taxonomy_id', 'inner')->where('term_title', $term_name)->where('taxonomy_name', $tax)->get()->first();
            }
        }

        return (!empty($term) && is_object($term)) ? $term : null;
    }

    public function getById($term_id)
    {
        $term = DB::table($this->table)->where('term_id', $term_id)->get()->first();
        return (!empty($term) && is_object($term)) ? $term : null;
    }

    public function deleteTerm($term_id)
    {
        DB::table('term_relation')->where('term_id', $term_id)->delete();
        return DB::table($this->table)->where('term_id', $term_id)->delete();
    }

    public function updateTerm($data, $term_id)
    {
        return DB::table($this->getTable())->where('term_id', $term_id)->update($data);
    }

    public function createTerm($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }
}
