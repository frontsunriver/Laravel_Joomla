<?php do_action('init'); ?>
<?php do_action('frontend_init'); ?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <?php
    $favicon = get_option('favicon');
    $favicon_url = get_attachment_url($favicon);
    ?>
    <link rel="shortcut icon" type="image/png" href="{{ $favicon_url }}"/>

    <title>{{__('We are coming soon')}}</title>

    <link href="{{asset('css/vendor.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/frontend.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/coming-soon.min.css')}}" rel="stylesheet" type="text/css">
    <?php do_action('header'); ?>
    <?php do_action('init_header'); ?>
    <?php do_action('init_frontend_header'); ?>
</head>
<body class="awe-booking coming-soon-page">
<?php
$coming_soon_background_id = get_option('coming_soon_background');
$coming_soon_background_url = get_attachment_url($coming_soon_background_id);
$coming_soon_date = get_option('coming_soon_date', date('Y-m-d', strtotime('+1 day')));
?>
<div class="page-wrapper" style="background-image: url('{{ $coming_soon_background_url }}')">
    <a href="{{ url('/') }}" id="logo">
        <?php
        $logo = get_option('logo');
        $logo_url = get_attachment_url($logo);
        ?>
        <img src="{{ $logo_url }}" alt="img-logo" class="img-logo">
    </a>
    <div class="coming-soon-main-content">
        <h1 class="coming-soon-heading">{{__('We are coming soon !!!')}}</h1>
        <p class="coming-soon-description">{{__('Stay tuned for something amazing')}}</p>
        <div class="coming-soon-times" data-date="{{$coming_soon_date}}">
            <div class="item day"><span class="number"></span><span class="unit">days</span></div>
            <div class="item hour"><span class="number"></span><span class="unit">hrs</span></div>
            <div class="item min"><span class="number"></span><span class="unit">min</span></div>
            <div class="item sec"><span class="number"></span><span class="unit">sec</span></div>
        </div>
    </div>
    <footer class="footer-coming">
        {!! balanceTags(get_option('copy_right')) !!}
    </footer>
</div>

<script src="{{asset('js/vendor.min.js')}}"></script>
<script src="{{asset('vendor/countdown.min.js')}}"></script>
<script>
    (function ($) {
        $('.coming-soon-times').each(function () {
            let date = $(this).data('date');
            $(this).countdown(date, function (event) {
                $('.day .number', this).html(event.strftime('%D'));
                $('.hour .number', this).html(event.strftime('%H'));
                $('.min .number', this).html(event.strftime('%M'));
                $('.sec .number', this).html(event.strftime('%S'));
            });
        });
    })(jQuery)
</script>
<?php do_action('footer'); ?>

<?php do_action('init_footer'); ?>
<?php do_action('init_frontend_footer'); ?>
</body>
</html>
