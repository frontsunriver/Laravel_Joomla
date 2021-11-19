<?php
$comments = get_comment_list($post->post_id, [
    'number' => comments_per_page(),
    'page' => request()->get('review_page', 1),
    'type' => 'experience',
]);
$comment_number = get_comment_number($post->post_id, 'experience');
?>

<div class="home-comment-list mt-3">
    <h3 class="comment-count">
        {{ _n("[0::%s reviews for this experience][1::%s review for this experience][2::%s reviews for this experience]", $comment_number) }}
    </h3>
    @if(!empty($comment_number))
        <?php
        render_experience_comment_list($comments['results']);
        frontend_pagination([
            'total' => $comments['count'],
            'posts_per_page' => comments_per_page(),
            'query_string' => '',
            'current_url' => '',
            'type' => 'experience',
            'page' => request()->get('review_page', 1)
        ], true);
        ?>
    @endif
</div>
@if(user_can_review(get_current_user_id(), $post->post_id, 'experience'))
    <div class="post-comment parent-form" id="hh-comment-section">
        <div class="comment-form-wrapper">
            <form action="{{ url('add-post-comment') }}" class="comment-form form-sm form-action form-add-post-comment"
                  data-validation-id="form-add-comment"
                  data-google-captcha="yes"
                  method="post" data-reload-time="1000">
                <h3 class="comment-title">{{__('Leave a Review')}}</h3>
                <p class="notice">{{__('Your email address will not be published. Required fields are marked *')}}</p>
                @include('common.loading')
                <input type="hidden" name="post_id" value="{{ $post->post_id }}"/>
                <input type="hidden" name="comment_id" value="0"/>
                <input type="hidden" name="comment_type" value="experience"/>
                <div class="row">
                    <?php if(!is_user_logged_in()){ ?>
                    <div class="form-group col-lg-6">
                        <input id="comment-name" type="text" name="comment_name" class="form-control has-validation"
                               placeholder="{{__('Your name*')}}" data-validation="required"/>
                    </div>
                    <div class="form-group col-lg-6">
                        <input id="comment-email" type="email" name="comment_email" class="form-control has-validation"
                               placeholder="{{__('Your email*')}}" data-validation="required"/>
                    </div>
                    <?php } ?>
                    <div class="form-group col-lg-6">
                        <input id="comment-title" type="text" name="comment_title" class="form-control has-validation"
                               placeholder="{{__('Comment title*')}}" data-validation="required"/>
                    </div>
                    <div class="form-group col-lg-6">
                        <div class="review-select-rate">
                            <span>{{__('Your rating')}}</span>
                            <div class="fas-star">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <input type="hidden" name="review_star" value="5" class="review_star">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                    <textarea id="comment-content" name="comment_content" placeholder="{{__('Comment*')}}"
                              class="form-control has-validation" data-validation="required"></textarea>
                    </div>
                </div>
                <div class="form-message"></div>
                <button type="submit" class="btn btn-primary text-uppercase">{{__('Submit Review')}}</button>
            </form>
        </div>
    </div>
@endif
