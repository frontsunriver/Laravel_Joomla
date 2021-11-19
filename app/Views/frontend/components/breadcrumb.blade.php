<nav aria-label="breadcrumb">
    @if(isset($inContainer))
        <div class="container">
            @endif
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <a href="{{ url('/') }}">{{__('Homepage')}}</a>
                </li>
                <li class="breadcrumb-item">{{ $currentPage }}</li>
            </ol>
            @if(isset($inContainer))
        </div>
    @endif
</nav>
