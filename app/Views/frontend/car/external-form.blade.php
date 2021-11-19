<?php
    global $post;
    $external_link = $post->use_external_link;
    $text_external_link = get_translate($post->text_external_link);

?>


<a href="{{ $external_link }}" class="form-group btn btn-primary btn-block text-uppercase">
    {{ $text_external_link }}
</a>
