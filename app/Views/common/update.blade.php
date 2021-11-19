@if(isset($messages))
    <div class="alert alert-info">
        @foreach($messages as $message)
            <p class="mb-2">
                {{ $message }}
            </p>
        @endforeach
    </div>
@endif
