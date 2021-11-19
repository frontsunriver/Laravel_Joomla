<?php

namespace App\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\TermRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
	public function __construct() {
		$this->model = new Post();
	}

	public function index(Request $request)
    {
        $rules = [
            'search' => 'string',
            'term_slug' => 'string',
            'term_ids' => 'array',
            'term_relation' => 'in:and,or',
            'page' => 'integer|min:1',
            'number' => 'integer|min:1',
            'status' => 'array',
            'order_by' => 'in:post_id,post_title,post_slug,created_at',
            'order' => 'in:asc,desc',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => $validator->errors()
            ]);
        }
        $data = parse_request($request, array_keys($rules));

        $posts = $this->model->getAllPosts($data);
        if (is_array($posts)) {
            $lang = $request->get('lang', get_current_language());

            $results = [];
            if($posts['total'] > 0){
                foreach ($posts['results'] as $k => $v){
                    $temp = $v;
                    $temp->post_title = get_translate($v->post_title, $lang);
                    $temp->post_content = get_translate($v->post_content, $lang);
                    $temp->thumbnail_url = get_attachment_url($v->thumbnail_id);
                    $temp->author_name = get_username($v->author);
	                $temp->created_at = date(hh_date_format(), $v->created_at);

                    //Post categories
                    $post_categories = get_category($v->post_id);
                    $categories = [];
                    if(!empty($post_categories)){
                        foreach ($post_categories as $kk => $vv) {
                            $categories[] = [
                                'id' => $vv->term_id,
                                'link' => get_term_link($vv->term_name),
                                'name' => get_translate($vv->term_title, $lang)
                            ];
                        }
                    }

                    //Post tags
                    $post_tags = get_tag($v->post_id);
                    $tags = [];
                    if(!empty($post_tags)){
                        foreach ($post_tags as $kk => $vv) {
                            $tags[] = [
                                'id' => $vv->term_id,
                                'link' => get_term_link($vv->term_name),
                                'name' => get_translate($vv->term_title, $lang)
                            ];
                        }
                    }

                    $temp->categories = $categories;
                    $temp->tags = $tags;
                    $results[] = $temp;
                }
            }

            return $this->sendJson([
                'status' => 1,
                'message' => __('Success'),
                'total' => $posts['total'],
                'results' => $results
            ]);
        }
        return $this->sendJson([
            'status' => 0,
            'message' => __('Can not get data')
        ]);
    }

    public function store(Request $request)
    {
        $post = new Post();

        $rules = [
            'post_title' => 'required|string',
            'post_slug' => 'required|string',
            'post_content' => 'string',
            'thumbnail_id' => 'integer',
            'author' => 'required|integer|min:0',
            'status' => 'required|in:publish,draft,trash,revision',
            'created_at' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => $validator->errors()
            ]);
        }

        $meta_rules = [
            'categories' => 'array',
            'tags' => 'array',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => $validator->errors()
            ]);
        }

        $data = parse_request($request, array_keys($rules));

        $new_post = $post->createPost($data);
        $post_object = get_post($new_post, 'post');
        if ($new_post && $post_object) {

            $data = parse_request($request, array_keys($meta_rules));

            $termRelation = new TermRelation();

            /* Category update */
            $categories = $data['categories'];
            if (is_array($categories)) {
                $termRelation->deleteRelationByServiceID($new_post, 'post-category');
                if (!empty($categories)) {
                    foreach ($categories as $termID) {
                        $termRelation->createRelation($termID, $new_post, 'post');
                    }
                }
            }

            /* Tag update */
            $tags = $data['tags'];
            if (is_array($tags)) {
                $termRelation->deleteRelationByServiceID($new_post, 'post-tag');
                if (!empty($tags)) {
                    foreach ($tags as $termID) {
                        $termRelation->createRelation($termID, $new_post, 'post');
                    }
                }
            }

            return $this->sendJson([
                'status' => 1,
                'post' => $post_object,
                'message' => __('Created post successfully')
            ]);

        } else {
            return $this->sendJson([
                'status' => 0,
                'message' => __('Can not create post')
            ]);
        }

    }

    public function show($id, Request $request)
    {
        $lang = $request->get('lang', get_current_language());
	    $data = $this->model->getById($id);

	    if($data){
	        $data->post_title = get_translate($data->post_title, $lang);
	        $data->post_content = get_translate($data->post_content, $lang);
		    $data->thumbnail_url = get_attachment_url($data->thumbnail_id);
		    $data->author_name = get_username($data->author);
		    $data->created_at = date(hh_date_format(), $data->created_at);

		    //Post categories
            $post_categories = get_category($data->post_id);
            $categories = [];
            if(!empty($post_categories)){
                foreach ($post_categories as $k => $v) {
                    $categories[] = [
                        'id' => $v->term_id,
                        'link' => get_term_link($v->term_name),
                        'name' => get_translate($v->term_title, $lang)
                    ];
                }
            }

            //Post tags
            $post_tags = get_tag($data->post_id);
            $tags = [];
            if(!empty($post_tags)){
                foreach ($post_tags as $k => $v) {
                    $tags[] = [
                        'id' => $v->term_id,
                        'link' => get_term_link($v->term_name),
                        'name' => get_translate($v->term_title, $lang)
                    ];
                }
            }

            $data->categories = $categories;
            $data->tags = $tags;

		    return $this->sendJson([
			    'status' => true,
			    'message' => __('Success'),
			    'data' => $data
		    ]);
	    }
	    return $this->sendJson([
		    'status' => false,
		    'message' => __('Can not get data')
	    ]);
    }
}
