
@extends('layouts.guest') 

@section('content')
<div class="flex flex-col items-center justify-center">

    <h1 class="text-4xl font-extrabold">Changelog</h1>

    <div>{!! $changelog !!}</div>

</div>
@endsection