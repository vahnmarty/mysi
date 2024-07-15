<x-mail::message>

Here's {{ $video->child->first_name }}'s Admission Video (URL): 

<a href="{{ $video->video_url }}">{{ $video->video_url }}</a>



</x-mail::message>