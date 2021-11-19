@include('dashboard.components.header')
<?php
enqueue_style('confirm-css');
enqueue_script('confirm-js');
enqueue_script('nice-select-js');
enqueue_style('nice-select-css');


$menuLocations = Config::get('awebooking.menu_location');
$listMenus = get_list_menus();
$menu_id = request()->get('menu_id', 'none');
$menu_location = '';
$menu_item = [];
if ($menu_id == 'none') {
    if (count($listMenus) > 0) {
        $menu_id = $listMenus[0]->menu_id;
    }
}
if ($menu_id != 'none') {
    $menu_item = get_menu_by_id($menu_id);
    if (!empty($menu_item)) {
        $menu_location = $menu_item->menu_position;
    }
}
$menu_items = get_menu_items_by_menu_id($menu_id);
?>
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            @include('dashboard.components.breadcrumb', ['heading' => __('Menu')])
            <div class="card-box">
                @include('dashboard.screens.administrator.menu.menu-bar')
                <div class="row">
                    <div class="col-lg-3 hh-add-menu-box-wrapper">
                        @include('dashboard.screens.administrator.menu.menu-side')
                    </div>
                    <div class="col-lg-9">
                        @include('dashboard.screens.administrator.menu.menu-content')
                    </div>
                </div>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')
