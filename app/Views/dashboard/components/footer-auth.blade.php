<footer class="footer footer-alt">
   <span class="copy-right-text">
        &copy; {{ date('Y') }} {{__('by')}} <a href="{{url('/')}}" class="text-white-50">{{get_option('site_name')}}</a>
   </span>
</footer>
<script src="{{asset('js/vendor.min.js')}}"></script>
<script src="{{asset('js/dashboard.js')}}"></script>
</body>
</html>
