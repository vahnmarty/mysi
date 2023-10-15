@extends('layouts.pdf')

@section('title', 'MySI Notification')
@section('content')
<div>
    <table border="0" width="100%" style="font-family: sans-serif; font-size: 12px;" cellspacing="0" cellpadding="0">
        <tr>
            <td width="13%"><a href="#" target="_blank"><img src="{{ asset('img/logo.png') }}" width="76" height="100" alt="Logo" align="center" border="0"></a></td>
               <td width="87%">
                St. Ignatius College Preparatory<br>
                2001 37th Avenue<br>
                San Francisco, California 94116<br>
                (415) 731-7500<br><br>
                Office of Admissions
            </td>
        </tr>
        </table>
    
    <article style="font-family: sans-serif; font-size: 12px; margin-top: 32px">
        {!! $content !!}
    </article>
</div>
@endsection