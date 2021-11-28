<div class="hh-add-menu-box overflow-hidden">
    <h5 class="title d-flex align-items-center justify-content-between">{{__('Tags')}} <i class="fe-chevron-down"></i>
    </h5>
    <div class="menu-content-wrapper">
        <div class="content">
            <?php
            $all_posts = get_terms('post-tag', true);
            if(count($all_posts) > 0){
            foreach ($all_posts as $k => $item){
            ?>
            <div class="checkbox  checkbox-success mb-2">
                <input type="checkbox"
                       class="hh-add-menu-item"
                       name="menu_item[]"
                       value="{{ $item->term_id }}"
                       data-id="{{ $item->term_id  }}"
                       data-url="{{ url('tag/' . $item->term_name)  }}"
                       data-type="tag"
                       data-name="{{ get_translate($item->term_title) }}"
                       id="menu_item_tag_{{ $item->term_id  }}"/>
                <label for="menu_item_tag_{{ $item->term_id  }}">{{ get_translate($item->term_title) }}</label>
            </div>
            <?php
            }
            }else {
                echo __('No tags found');
            }
            ?>
        </div>
        @if(count( $all_posts ) > 0)
            <a href="#" class="btn btn-success btn-sm mt-2 right hh-btn-add-menu-item">{{__('Add to menu')}}</a>
        @endif
    </div>
</div>
