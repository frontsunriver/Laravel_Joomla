{{ frontend_pagination([
    'total' => $total,
    'posts_per_page' => $number,
    'query_string' => $query_string,
    'current_url' => $current_url,
    'page' => $page
]) }}