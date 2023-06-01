@if ($errors->any())
    <div {{ $attributes }} class="px-2 py-2 mt-4 rounded-md bg-red-50">
        <ul class="text-sm text-red-600 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
