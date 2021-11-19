<?php
use App\Models\Menu;
use App\Models\MenuStructure;

function has_nav_primary(){
	$menu = new Menu();
	return $menu->hasMenuLocation();
}

function get_nav_by_id($menu_id){
    if(!empty($menu_id)){
	    $menuItems = get_menu_items_by_menu_id($menu_id);
        render_normal_menu($menuItems);
    }
}

function get_nav($data){
	$default = [
		'location' => '',
		'walker' => 'normal'
	];

    $data = wp_parse_args($data, $default);

	$menu = new Menu();
	$menuObject = $menu->getMenuByLocation($data['location']);
	if(!empty($menuObject)){
		$menuItems = get_menu_items_by_menu_id($menuObject->menu_id);
		switch ($data['walker']) {
			case 'main':
				render_main_menu($menuItems);
				break;
            case 'main-mobile':
                render_main_mobile_menu($menuItems);
                break;
            default:
				render_normal_menu($menuItems);
				break;
		}
	}else{
		echo 'No menus.';
	}
}

function render_main_mobile_menu($menu_items, $detph = 1, $is_submenu = false){
    if($is_submenu){
        echo '<ul role="menu" class="hh-navigation sub-menu clearfix">';
        echo '<li class="submenu-head"><span class="close-submenu"><i class="ti-angle-left"></i></span>'. __('Back') .'</li>';
    }else{
        echo '<ul id="menu-primary" class="hh-navigation hh-parent mobile-menu list-unstyled">';
        echo '<li class="back-menu"><span class="close-submenu"><i class="ti-angle-left"></i></span>'.__('Back').'</li>';
    }

    $current_url = url()->current();
    $current_url = rtrim($current_url, '/');

    foreach ($menu_items as $k => $v) {
        $item_link = rtrim($v->url, '/');
        $class_current = '';
        if($current_url == $item_link){
            $class_current = ' current-menu-item';
        }

        $class="menu-item menu-item-". $v->item_id. ' ' . $class_current;
        if(isset($v->children)){
            $class="has-submenu menu-item menu-item-has-children" . $class_current;
        }
        ?>
        <li class="<?php echo esc_attr($class); ?>">
            <a href="<?php echo esc_attr($v->url) ?>"><?php echo esc_attr($v->name); ?></a>
            <?php
            if(isset($v->children)){
                echo '<span class="toggle-submenu"><i class="ti-angle-down"></i></span>';
                render_main_mobile_menu($v->children, 2, true);
            }
            ?>
        </li>
        <?php
    }
    echo '</ul>';

}


function render_main_menu($menu_items, $detph = 1, $is_submenu = false){
	if($is_submenu){
		echo '<ul role="menu" class="hh-navigation sub-menu clearfix">';
	}else{
		echo '<ul id="menu-primary-1" class="hh-navigation hh-parent main-menu">';
	}

    $current_url = url()->current();
	$current_url = rtrim($current_url, '/');

	foreach ($menu_items as $k => $v) {
	    $item_link = rtrim($v->url, '/');
	    $class_current = '';
	    if($current_url == $item_link){
	        $class_current = ' current-menu-item';
        }
		$class="menu-item menu-item-". $v->item_id. ' ' . $class_current;
		if(isset($v->children)){
			$class="has-submenu menu-item-has-children" . $class_current;
		}

		$target = '';
		if(isset($v->target_blank) && !empty($v->target_blank)){
			$target = 'target="_blank"';
		}
		?>
		<li class="<?php echo esc_attr($class); ?>">
			<a href="<?php echo esc_attr($v->url); ?>" <?php echo esc_text($target); ?>><?php echo esc_attr($v->name); ?></a>
			<?php
			if(isset($v->children)){
				echo '<span class="toggle-submenu"><i class="ti-angle-down"></i></span>';
				render_main_menu($v->children, 2, true);
			}
			?>
		</li>
		<?php
	}
	echo '</ul>';

}

function render_normal_menu($menu_items, $detph = 1, $is_submenu = false){
	if($is_submenu){
		echo '<ul class="sub-menu">';
	}else{
		echo '<ul class="menu">';
	}
	foreach ($menu_items as $k => $v) {
		$class="";
		if(isset($v->children)){
			$class="has-submenu";
		}

		$target = '';
		if(isset($v->target_blank) && !empty($v->target_blank)){
			$target = 'target="_blank"';
        }
		?>
		<li class="menu-item menu-item-<?php echo esc_attr($v->item_id); ?> <?php echo esc_attr($class); ?>">
			<a href="<?php echo esc_attr($v->url) ?>" <?php echo esc_text($target); ?>><?php echo esc_attr($v->name); ?></a>
			<?php
			if(isset($v->children)){
				render_normal_menu($v->children, 2, true);
			}
			?>
		</li>
		<?php
	}
	echo '</ul>';

}

function render_menu_tree($menu_items, $detph = 1){
	if($detph > 1){
		echo '<ol>';
	}
	foreach ($menu_items as $k => $v) {
		?>
		<li id="hh-mn-<?php echo esc_attr($v->item_id); ?>" data-type="<?php echo esc_attr($v->type); ?>" data-post_id="<?php echo esc_attr($v->post_id); ?>" data-post_title="<?php echo esc_attr($v->post_title); ?>">
			<div class="item type-<?php echo esc_attr($v->type); ?>">
				<div class="item-header d-flex align-items-center justify-content-between">
					<span class="name">
						<?php echo esc_attr($v->name); ?>
					</span>
					<span class="hh-delete-menu-item ml-3">
						<i class="fe-trash-2"></i>
					</span>
				</div>
				<div class="item-content-wrapper">
					<div class="item-content">
						<div class="form-group name">
							<label><?php echo __('Menu name') ?></label>
							<input type="text" class="form-control form-control-sm menu_name" value="<?php echo esc_attr($v->name); ?>">
						</div>
						<div class="form-group url">
							<label><?php echo __('Menu URL') ?></label>
							<input type="text" class="form-control form-control-sm menu_url" value="<?php echo esc_attr($v->url); ?>">
						</div>

                        <div class="form-group target">
                            <div class="checkbox checkbox-success">
                                <input <?php echo (!empty($v->target_blank)) ? 'checked' : ''; ?> type="checkbox" class="menu_target" value="1" id="target-checkbox<?php echo esc_attr($k . $v->item_id); ?>">
                                <label for="target-checkbox<?php echo esc_attr($k . $v->item_id); ?>"><?php echo __('Open link in a new tab') ?></label>
                            </div>
                        </div>

						<div class="menu-info">
							<p class="menu-type"><?php echo __('Type:') ?> <?php echo ucwords(str_replace('_', ' ', __($v->type))); ?></p>
							<p class="menu-origin-link"><?php echo __('Origin:') ?>
								<a href="<?php echo esc_attr($v->url); ?>"><?php echo esc_attr($v->post_title); ?></a>
							</p>
						</div>
					</div>
				</div>
			</div>
			<?php
			if(isset($v->children)){
				render_menu_tree($v->children, 2);
			}
			?>
		</li>
		<?php
	}
	if($detph > 1){
		echo '</ol>';
	}

}

function flatten_menu_data($elements, $parentId = '') {
    $branch = array();

    foreach ($elements as $element) {
        if ($element->parent_id == $parentId && $element->item_id != '') {
            $children = flatten_menu_data($elements, $element->item_id);
            if ($children) {
                $element->children = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

function get_menu_items_by_menu_id($menu_id){
	$menu_structure = new MenuStructure();
	$data = $menu_structure->getByMenuId($menu_id);
	$data = flatten_menu_data($data);
	return $data;
}

function get_list_menus(){
	$menu = new Menu();
	return $menu->getAllMenus();
}

function get_menu_by_id($menu_id){
	$menu = new Menu();
	return $menu->getById($menu_id);
}

function get_navigation()
{
	$listMenus = get_list_menus();
	if (!empty($listMenus) && is_object($listMenus)) {
		$return = [];
		foreach ($listMenus as $menu) {
			$return[$menu->menu_id] = $menu->menu_title;
		}
		return $return;
	} else {
		return [];
	}
}
