<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TermRelation extends Model
{
    protected $table = 'term_relation';

    public function deleteRelationByTermID($term_id)
    {
        $deleted = DB::table($this->getTable())->where('term_id', $term_id)->delete();
        return $deleted ? $deleted : false;
    }

    public function get_the_terms($post_id, $post_type = 'post', $taxonomy = false)
    {
        if ($taxonomy) {
            return DB::table($this->getTable())->selectRaw('term.*')->join('term', 'term_relation.term_id', '=', 'term.term_id', 'inner')->join('taxonomy', 'term.taxonomy_id', '=', 'taxonomy.taxonomy_id', 'inner')->where('term_relation.service_id', $post_id)->where('term_relation.post_type', $post_type)->where('taxonomy.taxonomy_id', $taxonomy)->groupBy('term.term_id')->get();
        } else {
            return DB::table($this->getTable())->selectRaw('term.*')->join('term', 'term.term_id', 'term_relation.term_id')->where('term_relation.service_id', $post_id)->where('term_relation.post_type', $post_type)->groupBy('term.term_id')->get();
        }
    }

    public function deleteRelationByServiceID($service_id, $taxonomy_name = '', $post_type = 'post')
    {
        if (empty($taxonomy_name)) {
            $deleted = DB::table($this->getTable())->where('service_id', $service_id)->where('post_type', $post_type)->delete();
        } else {
            $deleted = DB::table($this->getTable())->join('term', 'term_relation.term_id', '=', 'term.term_id', 'inner')->join('taxonomy', 'term.taxonomy_id', '=', 'taxonomy.taxonomy_id', 'inner')->where('service_id', $service_id)->where('taxonomy_name', $taxonomy_name)->delete();
        }
        return $deleted ? $deleted : false;
    }

    public function createRelation($term_id, $service_id, $post_type = 'post')
    {
        $data = [
            'term_id' => (int)$term_id,
            'service_id' => $service_id,
            'post_type' => $post_type
        ];
        return DB::table($this->getTable())->insertGetId($data);
    }
}
