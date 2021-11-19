<?php
if (is_null($newPage) || empty($newPage)) {
    return;
}
$serviceObject = get_post($newPage, $service, false);
$options = Config::get('awebooking.' . $key);
?>

<div id="hh-options-wrapper" class="hh-options-wrapper ">
    <div class="hh-options-tab-content">
        <div class="tab-content">
            <div class="row">
                <?php
                foreach ($options['fields'] as $_key => $field) {
                    $field = \ThemeOptions::mergeField($field);

                    $field['post_id'] = $newPage;

                    $field = \ThemeOptions::applyData($newPage, $field, $serviceObject);

                    echo \ThemeOptions::loadField($field);
                }
                ?>
            </div>
        </div>
    </div>
</div>
