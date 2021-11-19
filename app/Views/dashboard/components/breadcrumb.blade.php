<?php
$heading = isset($heading) ? $heading : '';
?>
<div class="page-title-box">
    <div class="page-breadcrumb">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $heading }}
                </li>
            </ol>
        </nav>
    </div>
    <div class="page-title-right">
        <?php do_action('hh_dashboard_breadcrumb'); ?>
    </div>
</div>
