<form action="update-menu" method="POST" class="hh-menu-form form-action" data-reload-time="1000"
      data-validation-id="form-update-menu">
    <input type="hidden" name="menu_structure" value=""/>
    <div class="d-flex justify-content-between mt-3 mt-lg-0">
        <h4 class="header-title mb-0">{{__('Menu structure')}} </h4>
        <button class="btn btn-success btn-sm right">{{__('Save menu')}}</button>
    </div>
    <?php
    if(empty($menu_item) && !empty($menu_id)){
        echo '<p class="mt-3">' . __('No menu selected.') . '</p>';
    }else{
    ?>
    <input type="hidden" value="<?php echo esc_attr($menu_id); ?>" name="menu_id"/>
    <div class="hh-menu-form-edit mt-3 mb-4">
        <div class="form-inline">
            <div class="form-group">
                <?php
                $menu_name = '';
                if (!empty($menu_item)) {
                    $menu_name = $menu_item->menu_title;
                }
                ?>
                <label class="mr-2">{{__('Menu name:')}}</label>
                <input type="text" class="form-control form-control-sm has-validation"
                       value="<?php echo esc_attr($menu_name); ?>"
                       name="menu_name" data-validation="required" id="hh-menu-title"/>
            </div>
        </div>
        <hr/>
    </div>
    <div class="hh-list-menu-box"
         data-menu-name="{{__('Menu name')}}"
         data-menu-url="{{__('Menu URL')}}"
         data-menu-type="{{__('Type:')}}"
         data-menu-origin="{{__('Origin:')}}"
         data-menu-target="{{__('Open link in a new tab')}}"
    >
        <ol class="sortable">
            <?php render_menu_tree($menu_items); ?>
        </ol>
    </div>
    <?php
    }
    ?>
    <div class="hh-menu-position mt-4">
        <hr/>
        <div class="">
            <span class="mb-2 d-block"><b>{{__('Display location')}}</b></span>
            <div class="radio radio-success mb-2">
                <input type="radio" name="menu_location" class="" value=""
                       id="menu-location-none" <?php echo empty($menu_location) ? 'checked' : ''; ?>>
                <label for="menu-location-none">{{__('None')}}</label>
            </div>
            <?php
            if(!empty($menuLocations)){

            foreach ($menuLocations as $k => $v) {
            $checked = '';
            if ($k == $menu_location) {
                $checked = 'checked';
            }
            ?>
            <div class="radio radio-success mb-2">
                <input type="radio" name="menu_location" class="" value="{{ $k }}"
                       id="menu-location-{{ $k }}" {{ $checked }}>
                <label for="menu-location-{{ $k }}">{{ __($v) }}</label>
            </div>
            <?php
            }
            }
            ?>
        </div>
    </div>
    <?php
    if(!empty($menu_item)){
    $data = [
        'menuID' => $menu_id,
        'menuEncrypt' => hh_encrypt($menu_id)
    ];
    ?>
    <div class="lh-menu-action d-flex align-items-center justify-content-between mt-3">
        <a class="hh-link-action hh-delete-menu text-danger"
           data-action="{{ dashboard_url('delete-menu') }}"
           data-parent=""
           data-params="{{ base64_encode(json_encode($data)) }}"
           data-confirm="yes"
           data-is-delete="true"
           data-confirm-title="{{__('Confirm Delete')}}"
           data-confirm-question="{{__('Are you sure want to delete this menu?')}}"
           data-confirm-button="{{__('Delete it!')}}"
           href="javascript: void(0)">
            <i class="fe-trash-2"></i> {{__('Delete this menu')}}
        </a>
        <button class="btn btn-success btn-sm right">{{__('Save menu')}}</button>
    </div>
    <?php
    }
    ?>
</form>
