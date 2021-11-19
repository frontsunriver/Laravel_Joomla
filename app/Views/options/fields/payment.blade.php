<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
$idName = str_replace(['[', ']'], '_', $id);
?>
<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-operator="{{ $operation }}"
     class="form-group mb-3 col {{ $layout }} field-{{ $type }}">
    <?php
    $tab_title = get_payment_options('title');
    $tab_content = get_payment_options('content');
    ?>
    <div class="col col-12">
        <ul class="nav nav-tabs nav-bordered">
            @foreach ($tab_title as $__key => $title)
                <?php $class = ($__key == 0) ? 'active show' : ''; ?>
                <li class="nav-item">
                    <a href="#{{ $title['id'] }}"
                       data-toggle="tab"
                       aria-expanded="false"
                       class="nav-link {{ $class }}">
                        {{ $title['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach ($tab_title as $__key => $title)
                <?php $class = ($__key == 0) ? 'active show' : ''; ?>
                <div class="tab-pane {{ $class }}"
                     id="{{ $title['id'] }}">
                    <div class="row">
                        @foreach ($tab_content as $___key => $content)
                            <?php
                            if ($content['section'] == $title['id']) {
                                $currentOptions[] = $content;
                                $content['value'] = \ThemeOptions::getOption($content['id'], '', true);
                                $content = \ThemeOptions::mergeField($content);
                                echo \ThemeOptions::loadField($content);
                            }
                            ?>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif
