<?php
    global $post;

    $this_post = $post;
    if (is_null($this_post)) {
        $this_post = get_post($post_id, 'experience');
    }
    $custom_price = isset($custom_price) ? $custom_price : [];
    $booking_type = isset($booking_type) ? $booking_type : 'date_time';
?>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            @if($booking_type == 'date_time')
                <th scope="col">{{__('Time')}}</th>
            @else
                <th scope="col">{{__('Date')}}</th>
            @endif
            <th scope="col">{{__('Price')}}</th>
            @if($booking_type != 'package')
                <th scope="col">{{__('Max. People')}}</th>
            @endif
            <th scope="col" width="100">{{__('Action')}}</th>
        </tr>
        </thead>
        @if (!empty($custom_price['total']))
            <tbody>
            @foreach ($custom_price['results'] as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    @if($booking_type == 'date_time')
                        <td>{{ date('h:i A, Y-m-d', $item->start_time) }}</td>
                    @else
                        <td>{{ date('Y-m-d', $item->start_date) }}</td>
                    @endif
                    @if($booking_type != 'package')
                        <td>
                            <p class="mb-0">{{__('Adult')}}: {{ convert_price($item->adult_price) }}</p>
                            <p class="mb-0">{{__('Child')}}: {{ convert_price($item->child_price) }}</p>
                            <p class="mb-0">{{__('Infant')}}: {{ convert_price($item->infant_price) }}</p>
                        </td>
                        <td>
                            {{__('Max.')}} {{$item->max_people}}
                        </td>
                    @else
                        <td><p class="mb-0">{{ convert_price($this_post->base_price) }}</p></td>
                    @endif
                    <td>
                        <a href="javascript: void(0)" class="btn btn-danger btn-sm delete-price"
                           data-title="{{__('Delete this item?')}}"
                           data-content="{{__('Are you sure to delete this item?')}}"
                           data-post-type="experience"
                           data-post-id="{{ $post_id }}"
                           data-price-id="{{ $item->ID }}">{{__('Delete')}}</a>
                    </td>
                </tr>
            @endforeach
            <tbody>
        @endif
    </table>
</div>
