<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <?php
                $currentScreen = Request::route()->getName();
                $current_params = \Illuminate\Support\Facades\Route::current()->parameters();
                foreach ($current_params as $key => $param) {
                    if ($key !== 'page' && $key !== 'id') {
                        $currentScreen .= '/' . $param;
                    }
                }
                $prefix = Config::get('awebooking.prefix_dashboard');
                $menuItems = get_menu_dashboard();
                ?>
                @if ($menuItems)
                    @foreach ($menuItems as $menu)
                        <?php
                        if (empty($menu)) {
                            continue;
                        }
                        if (isset($menu['service'])) {
                            if (!is_enable_service($menu['service'])) {
                                continue;
                            }
                        }
                        if (isset($menu['services'])) {
                            $total_service = count($menu['services']);
                            $count_service = 0;
                            foreach ($menu['services'] as $service_item) {
                                if (!is_enable_service($service_item)) {
                                    $count_service += 1;
                                }
                            }
                            if ($total_service == $count_service) {
                                continue;
                            }
                        }
                        ?>
                        @if ($menu['type'] == 'heading')
                            <li class="menu-title">{{ __($menu['label']) }}</li>
                            <?php
                            $menu_id = isset($menu['id']) ? $menu['id'] : '';
                            do_action('awebooking_dashboard_after_menu_' . $menu_id, $menu);
                            ?>
                        @endif
                        @if ($menu['type'] === 'item' || $menu['type'] == 'hidden')
                            <?php
                            $url = 'javascript:void(0);';
                            $icon = '';
                            $active = ($currentScreen == $menu['screen']) ? 'active' : '';
                            if (!empty($menu['icon'])) {
                                $icon = get_icon($menu['icon'], '#555', '20px');
                            }
                            if (!empty($menu['screen'])) {
                                $screen = ($menu['screen'] == 'dashboard') ? '' : $menu['screen'];
                                $url = url($prefix . '/' . $screen);
                            }
                            do_action('awebooking_dashboard_menu_item_' . $menu['screen'] . '_before');
                            ?>
                            <li><a href="{{ $url }}" class="{{ $active }}">{!! $icon !!}
                                    <span>{{ __($menu['label']) }}</span></a>
                            </li>
                            <?php do_action('awebooking_dashboard_menu_item_' . $menu['screen'] . '_after'); ?>
                        @endif
                        @if ($menu['type'] === 'parent')
                            <?php

                            $menu_id = isset($menu['id']) ? $menu['id'] : '';

                            $icon = '';
                            if (!empty($menu['icon'])) {
                                $icon = get_icon($menu['icon'], '#555', '20px');
                            }
                            ?>
                            <li class="@if(in_array($currentScreen, $menu['screen'])) active @endif"><a
                                    class="@if(in_array($currentScreen, $menu['screen'])) active @endif"
                                    aria-expanded="<?php if (in_array($currentScreen, $menu['screen'])) echo 'true'; ?>"
                                    href="javascript: void(0);">{!! $icon !!}<span>{{ __($menu['label']) }}</span>
                                    <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    @foreach ($menu['child'] as $child)
                                        <?php if (empty($child)) continue; ?>
                                        @if ($child['type'] === 'item')
                                            <?php
                                            $url = 'javascript:void(0);';
                                            $icon = '';

                                            $active = ($currentScreen == $child['screen']) ? 'active' : '';
                                            if (!empty($child['icon'])) {
                                                $icon = '<i class="' . $child['icon'] . '"></i>';
                                            }
                                            if (!empty($child['screen'])) {
                                                $url = url($prefix . '/' . $child['screen']);
                                            }
                                            ?>
                                            <li class="{{ $active }}"><a href="{{ $url }}"
                                                                         class="{{ $active }}">{{{ $icon }}}
                                                    <span>{{ __($child['label']) }}</span></a></li>
                                            <?php do_action('awebooking_dashboard_menu_item_' . $child['screen'] . '_after'); ?>
                                        @endif
                                    @endforeach
                                    <?php do_action('awebooking_dashboard_menu_' . $menu_id, $menu); ?>
                                </ul>
                            </li>
                                <?php do_action('awebooking_dashboard_menu_item_' . $menu_id . '_after'); ?>
                        @endif
                    @endforeach
                @endif
            </ul>

        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->
