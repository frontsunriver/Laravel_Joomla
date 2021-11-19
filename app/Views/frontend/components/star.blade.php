@if(!empty($rate))
    <?php
    $rate_full = round($rate, 0, PHP_ROUND_HALF_DOWN);
    if ($rate > $rate_full) {
        $rate_half = 1;
    } else {
        $rate_half = 0;
    }
    $rate_none = 5 - ($rate_full + $rate_half);

    $style = (isset($style)) ? $style : 1;
    ?>
    <div class="hh-star-rating style-{{$style}}">
        @if(isset($style) && $style == 2)
            <span class="star-item">{!! get_icon('001_star', null, '16px') !!}</span>
            @if(isset($show_text) && $show_text)
                <span class="star-text">{{$rate}}</span>
            @endif
        @else
            @if (!empty($rate_full))
                @for ($i = 0; $i < $rate_full; $i++)
                    <span class="star-item">{!! get_icon('001_star', null, '16px') !!}</span>
                @endfor
            @endif
            @if (!empty($rate_half))
                @for ($i = 0; $i < $rate_half; $i++)
                    <span class="star-item">{!! get_icon('002_star_haft', null, '16px') !!}</span>
                @endfor
            @endif
            @if (!empty($rate_none))
                @for ($i = 0; $i < $rate_none; $i++)
                    <span class="star-item">{!! get_icon('001_star_empty', null, '16px') !!}</span>
                @endfor
            @endif
            @if(isset($show_text) && $show_text)
                <span class="star-text">{{$rate}}</span>
            @endif
        @endif
    </div>
@endif
