<?php
use App\Models\Post;
use App\Models\Page;
use App\Models\Term;
use App\Models\Home;
use App\Models\Comment;

function render_comment_list($comments, $depth = 0, $is_sub = false){
	if($is_sub){
		echo '<ul class="comment-child clearfix">';
	}else{
		echo '<ul>';
	}
	foreach ($comments as $k => $v) {
		?>
		<li id="comment-<?php echo esc_attr($v->comment_id) ?>" class="comment odd alt thread-odd thread-alt depth-1">
			<div id="div-comment-<?php echo esc_attr($v->comment_id) ?>" class="article comment  clearfix" inline_comment="comment">
				<div class="comment-item-head">
					<div class="media">
						<div class="media-left">
							<img alt="" src="<?php echo get_user_avatar($v->comment_author) ?>" class="avatar avatar-50 photo avatar-default" height="50" width="50">                </div>
						<div class="media-body">
							<h4 class="media-heading">
								<?php
								if(is_user_logged_in()){
									echo esc_html(get_username($v->comment_author));
								}else{
									echo esc_html($v->comment_name);
								}
								?>
							</h4>
							<div class="date"><?php echo esc_html(date(hh_date_format(), $v->created_at )) ?></div>
						</div>
					</div>
				</div>
				<div class="comment-item-body">
					<div class="comment-content">
						<p><?php echo esc_html($v->comment_content) ?></p>
					</div>
				</div>
			</div>
			<?php if($depth < 2) { ?>
				<div class="reply-box-wrapper" id="reply-box" data-comment_id="<?php echo esc_attr($v->comment_id) ?>">
					<a href="#" class="btn btn-primary btn-sm btn-reply">Reply</a>
					<a href="#" class="btn btn-primary btn-sm btn-cancel-reply">Cancel</a>
					<div class="reply-form"></div>
				</div>
				<?php
			}
			$child_comments = get_comment_list($v->post_id, [
                'parent' => $v->comment_id
            ]);
			if($child_comments['count'] > 0){
                $depth++;
                render_comment_list($child_comments['results'], $depth, true);
            }
			?>
		</li>
		<?php
	}
	echo '</ul>';
}

function get_comment_list($post_id, $data){
    $comment = new Comment();
	$comment_data = $comment->getCommentByPostID($post_id, $data);
	return [
		'count' => $comment_data['count'],
		'results' => $comment_data['results']
	];
}

function recusiveComments($data, $parent_id = 0){
	$branch = array();
	foreach ($data as $element) {
		if ($element->parent == $parent_id) {
			$children = recusiveComments($data, $element->comment_id);
			if ($children) {
				$element->children = $children;
			}
			$branch[] = $element;
		}
	}

	return $branch;
}

function get_term_link($term_slug, $type="category"){
    switch ($type){
        case 'category':
            return url('category/' . $term_slug);
            break;
        case 'tag':
            return url('tag/' . $term_slug);
            break;
    }
}

function get_category($post_id){
	$post = new Post();
	$categories = $post->getTermByPostID($post_id, 'post-category');
	return $categories;
}

function get_tag($post_id){
	$post = new Post();
	$tags = $post->getTermByPostID($post_id, 'post-tag');
	return $tags;
}

function get_all_categories(){
	$term = new Term();
	$categories = $term->getAllTerms([
        'tax' => 'post-category',
        'number' => -1,
	]);
	$res = [];
	if($categories['total'] > 0){
		foreach ($categories['results'] as $item) {
		    array_push($res, [
                'term_id' => $item->term_id,
                'term_title' => $item->term_title,
                'term_name' => $item->term_name
            ]);
		}
	}
	return $res;
}

function page_status_info($name = '')
{
	$status = Config::get('awebooking.page_status');

	if (!empty($name) && isset($status[$name])) {
		return $status[$name];
	} else {
		return $status;
	}
}

function post_status_info($name = '')
{
	$status = Config::get('awebooking.post_status');

	if (!empty($name) && isset($status[$name])) {
		return $status[$name];
	} else {
		return $status;
	}
}
