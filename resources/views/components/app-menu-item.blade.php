@props(['start_date', 'end_date'])

@if($start_date && $end_date)
@if(now()->gte($start_date) && now()->lt($end_date) || empty($start_date)  || empty($end_date))
{{ $slot }}
@endif
@endif