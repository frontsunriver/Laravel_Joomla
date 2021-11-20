<?php
if (is_null($serviceObject)) {
    return;
}
$options = Config::get('awebooking.' . $key);
$facilities = get_terms('home-facilities');
foreach ($facilities as $key => $field) {
    $item = [
        'id' => strtolower($field['title']),
        // 'id' => 'facilities',
        'label' => awe_lang($field['title']),
        'type' => 'checkbox',
        'choices' => 'terms:home-facilities',
        'field_type' => 'taxonomy',
        'style' => 'col',
        'section' => 'amenities_options',
        'selection_val' => $field['selection_val'],
    ];
    array_push($options['fields'],$item);
} 
$distance = get_terms('home-distance');
foreach ($distance as $key => $field) {
    $item = [
        'id' => 'distance_'.strtolower($field),
        'label' => awe_lang($field),
        'type' => 'text',
        'choices' => 'terms:home-distance',
        'field_type' => 'taxonomy',
        'layout' => 'col-12 col-md-4',
        'section' => 'amenities_options',
    ];
    array_push($options['fields'],$item);
}
$availableField = \ThemeOptions::filterFields($options);
?>
<?php
if (is_multi_language()) {
    show_lang_section('mb-2');
}
?>
<div id="hh-options-wrapper" class="hh-options-wrapper">
    @include('common.loading')
    <div class="hh-options-tab" data-tabs-calculation>
        <div class="nav nav-pills nav-pills-tab @if ($addNew) disabled-link @endif" id="v-pills-tab"
             role="tablist" data-tabs
             aria-orientation="vertical">
            @foreach ($options['sections'] as $key => $section)
                <?php $class = ($key == 0) ? 'active show' : ''; ?>
                <a class="nav-link mb-2 {{ $class }}" data-tabs-item
                   id="v-pills-{{ $section['id'] }}-tab" data-toggle="pill"
                   href="#v-pills-{{ $section['id'] }}"
                   role="tab" aria-controls="v-pills-{{ $section['id'] }}"
                   aria-selected="true">
                    @if (!empty($section['icon']))
                        <i class="tab-icon {{ $section['icon'] }}"></i>
                    @endif
                    {{ __($section['label']) }}
                </a>
            @endforeach
        </div>
    </div>
    <div class="hh-options-tab-content">
        <div class="tab-content">
            <?php
            $totalTab = count($options['sections']);
            ?>
            @foreach ($options['sections'] as $key => $section)
                <?php $class = ($key == 0) ? 'active show' : ''; ?>
                <div class="tab-pane fade {{ $class }}"
                     id="v-pills-{{ $section['id'] }}" role="tabpanel"
                     aria-labelledby="v-pills-{{ $section['id'] }}-tab">
                    <form class="form hh-options-form @if ($key < $totalTab - 1) save-and-next @endif form-translation"
                          action="{{ $action }}"
                          method="post" data-tab="#v-pills-{{ $section['id'] }}-tab">
                        <input type="hidden" name="postID" value="{{ $serviceObject->post_id }}">
                        <input type="hidden" name="postEncrypt"
                               value="{{ hh_encrypt($serviceObject->post_id) }}">
                        <input type="hidden" name="hh_options_available_fields"
                               value="{{ base64_encode(serialize($availableField)) }}">
                        <input type="hidden" name="action" value="hh_options_save_metabox">
                        <input type="hidden" name="current_language_switcher" value="{{ get_current_language() }}">
                        <div class="row">
                            <?php
                            $currentOptions = [];
                            ?>
                            @foreach ($options['fields'] as $_key => $field)
                                @if ($field['section'] === $section['id'])
                                    @if ($field['type'] === 'tab')
                                        <div class="col col-12">
                                            <ul class="nav nav-tabs nav-bordered">
                                                @foreach ($field['tab_title'] as $__key => $title)
                                                    <?php $_class = ''; ?>
                                                    <?php $class = ($__key == 0) ? 'active show' : ''; ?>
                                                    <li class="nav-item">
                                                        <a href="#{{ $title['id'] }}"
                                                           data-toggle="tab"
                                                           aria-expanded="false"
                                                           class="nav-link {{ $_class }}">
                                                            {{ $title['label'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content">
                                                @foreach ($field['tab_title'] as $__key => $title)
                                                    <?php $class = ($__key == 0) ? 'active show' : ''; ?>
                                                    <div class="tab-pane {{ $_class }}"
                                                         id="{{ $title['id'] }}">
                                                        <div class="row">
                                                            @foreach ($field['tab_content'] as $___key => $content)
                                                                @if ($content['section'] == $title['id'])
                                                                    <?php
                                                                    $content = \ThemeOptions::mergeField($content);
                                                                    $content['post_id'] = $serviceObject->post_id;
                                                                    $currentOptions[] = $content;
                                                                    $content = \ThemeOptions::applyData($serviceObject->post_id, $content, $serviceObject);
                                                                    echo \ThemeOptions::loadField($content);
                                                                    ?>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <?php
                                        $field = \ThemeOptions::mergeField($field);
                                        $field['post_id'] = $serviceObject->post_id;
                                        $currentOptions[] = $field;
                                        $field = \ThemeOptions::applyData($serviceObject->post_id, $field, $serviceObject);
                                        echo \ThemeOptions::loadField($field);
                                        ?>
                                    @endif
                                @endif
                            @endforeach
                            <input type="hidden" name="currentOptions"
                                   value="{{ base64_encode(serialize($currentOptions)) }}">
                        </div>
                        <div class="clearfix mt-3">
                            @if ($key > 0)
                                <div class="button-list">
                                    <button class="btn btn-secondary left waves-effect waves-light btn-prev-tab-option"
                                            data-tab="#v-pills-{{ $section['id'] }}-tab"
                                            type="button">
                                        <span class="btn-label"><i class="icon-arrow-left"></i></span>
                                        {{__('Previous')}}
                                    </button>
                                </div>
                            @endif
                            @if ($key < $totalTab - 1)
                                <input type="hidden" name="step" value="next">
                                @if ($addNew)
                                    <div class="button-list">
                                        <button
                                            class="btn btn-success right waves-effect waves-light btn-next-tab-option"
                                            data-tab="#v-pills-{{ $section['id'] }}-tab"
                                            type="button">
                                            {{__('Save & Next')}}
                                            <span class="btn-label-right"><i
                                                    class="icon-arrow-right"></i></span>
                                        </button>
                                    </div>
                                @else
                                    <div class="button-list">
                                        <button
                                            class="btn btn-success right waves-effect waves-light btn-current-tab-option"
                                            data-tab="#v-pills-{{ $section['id'] }}-tab"
                                            type="button">
                                            {{__('Save')}}
                                            <span class="btn-label-right"><i
                                                    class="fe-check"></i></span>
                                        </button>
                                    </div>
                                @endif
                            @else
                                <input type="hidden" name="step" value="finish">
                                @if (!empty($redirectTo))
                                    <input type="hidden" name="redirect" value="{{ $redirectTo }}">
                                @endif
                                <div class="button-list">
                                    <button class="btn btn-success right waves-effect waves-light"
                                            type="submit">
                                        {{__('Save & Finish')}}
                                        <span class="btn-label-right"><i class="mdi mdi-check-all"></i></span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
