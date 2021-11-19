<?php

namespace App\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends APIController
{
	public function __construct() {
		$this->model = new Comment();
	}

	public function addReview(Request $request){
		$rules = [
			'post_id' => 'required|integer',
			'post_type' => 'required|string',
			'comment_title' => 'required|string',
			'comment_content' => 'required|string'
		];

		$post_type = $request->post('post_type');

		if(in_array($post_type, ['home', 'experience', 'car'])){
            $rules['review_star'] = 'required|integer';
        }

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return $this->sendJson([
				'status' => 0,
				'message' => $validator->errors()
			]);
		}

		$post_id = $request->post('post_id');
		$comment_content = $request->post('comment_content');
		$comment_title = $request->post('comment_title');
		$comment_rate = $request->post('review_star', 5);
		$parent_id = $request->post('parent_id', 0);
		$comment_type = $request->post('post_type', 'posts');

		$status = 'publish';

		$token = $request->bearerToken();
		$user = get_user_by_access_token($token);

		if($user) {
			$text = 'comment';
			if ( ! enable_review() ) {
				$this->sendJson( [
					'status'  => 0,
					'message' => __( 'Review function was closed' )
				] );
			}

			if ( in_array( $comment_type, [ 'home', 'experience', 'car' ] ) ) {
				$text = 'review';
			}

			if ( need_approve_review() && ! is_admin() ) {
				$status = 'pending';
			}

			$user_id = $user->getUserId();
			$comment_email = $user->getUserLogin();
			$comment_name  = get_username( $user_id );

			if ( $comment_name ) {
				$data = [
					'post_id'         => intval( $post_id ),
					'comment_name'    => $comment_name,
					'comment_title'   => $comment_title,
					'comment_content' => $comment_content,
					'comment_rate'    => $comment_rate,
					'comment_email'   => $comment_email,
					'comment_author'  => $user_id,
					'post_type'       => $comment_type,
					'parent'          => $parent_id,
					'status'          => $status,
					'created_at'      => time()
				];

				$comment = new Comment();

				$newPost = $comment->createPost( $data );

				if ( $newPost ) {

					if ( in_array( $comment_type, [ 'home', 'experience', 'car' ] ) ) {
						\App\Controllers\CommentController::get_inst()->updateRating( $post_id, $comment_type );
					}

					$success_text = sprintf( __( 'Add new %s successfully' ), $text );
					if ( need_approve_review() && ! is_admin() && $comment_type == 'posts' ) {
						$success_text = sprintf( __( 'Add new %s successfully. Your comment needs to be moderated before publishing' ), $text );
					}
					if ( in_array( $comment_type, [
							'home',
							'experience',
							'car'
						] ) && need_approve_review() && ! is_admin() ) {
						$success_text = sprintf( __( 'Add new %s successfully. Your review needs to be moderated before publishing' ), $text );
					}

					return $this->sendJson( [
						'status'  => 1,
						'message' => $success_text,
					]);
				} else {
					return $this->sendJson( [
						'status'  => 0,
						'message' => sprintf( __( 'Can not add this %s' ), $text )
					]);
				}
			} else {
				return $this->sendJson( [
					'status'  => 0,
					'message' => __( 'Some fields is incorrect' )
				]);
			}
		}
		return $this->sendJson( [
			'status'  => 0,
			'message' => __( 'Data is invalid' )
		]);
	}

	public function getReviews(Request $request){
        $rules = [
            'post_id' => 'required|integer',
            'post_type' => 'required|string',
            'number' => 'integer',
            'page' => 'integer',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => $validator->errors()
            ]);
        }

        $post_id = $request->post('post_id');
        $post_type = $request->post('post_type');

        $object = get_post($post_id, $post_type);
        if($object) {
	        $comment_number = get_comment_number( $post_id, $post_type );
	        $comments       = get_comment_list( $post_id, [
		        'number' => $request->post( 'number', comments_per_page() ),
		        'page'   => request()->get( 'page', 1 ),
		        'type'   => $post_type,
	        ] );

	        return $this->sendJson( [
		        'status' => 1,
		        'total'  => $comment_number,
		        'data'   => $comments
	        ] );
        }
        return $this->sendJson([
            'status' => 0,
            'message' => __('Data is invalid')
        ]);
    }
}
