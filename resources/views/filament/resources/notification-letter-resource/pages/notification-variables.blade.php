<x-filament::page>

    <div class="space-y-8">
        @foreach($variables as $config => $array)
        <div class="bg-white border border-gray-300 rounded-md shadow-sm">
            <div class="p-3">
                <pre class="font-bold">{{ $config }}</pre>
            </div>
            <div>
                @if(is_array($array))

                    @foreach(collect($array)->sortKeys() as $column => $field)
                    <div class="grid grid-cols-2 border-t">
                        <div class="p-3">{{ $column }}</div>
                        <div class="p-3">
                            <pre class="text-primary-red">{{ '@{'. $config . '.' . $column . '}' }}</pre>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="grid grid-cols-2 border-t">
                    <div class="p-3">{{ $config }}</div>
                    <div class="p-3">
                        <pre class="text-primary-red">{{ '@{'. $config . '}' }}</pre>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

</x-filament::page>
