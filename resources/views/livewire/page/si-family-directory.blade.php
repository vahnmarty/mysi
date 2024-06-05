<div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    <style>
        #dt-length-0{
            width: 80px;
        }

        .dt-type-numeric{
            text-align: left !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#table').DataTable({
                "pageLength": 100,
            });
        } );
        

    </script>
    @endpush


    <div class="flex justify-between">
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            SI Family Directory
        </h2>
        <p class="self-center text-xs">Last Updated On: {{ $last_updated_at }}</p>
    </div>

    <div class="mt-8" wire:ignore>
        <table id="table" class="cell-border" style="width: 100%">
            <thead class="bg-gray-200 border">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Class of</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                @foreach($directory as $item)
                <tr wire:key="dir{{ $item->id }}">
                    <td>{{ $item->full_name }}</td>
                    <td>
                        @if($item->contact_type == 'Student')
                        <span class="bg-primary-blue px-2 py-0.5 rounded-md text-gray-100 text-sm ">{{ $item->contact_type }}</span>
                        @else
                        <span class="bg-primary-red px-2 py-0.5 rounded-md text-gray-100 text-sm ">{{ $item->contact_type }}</span>
                        @endif
                    </td>
                    <td> {{ $item->graduation_year }}</td>
                    <td>
                        <x-loading-icon wire:target="open(`{{ $item->id }}`,`{{ $item->account_id }}`)"/>  
                        <button type="button" class="text-link" wire:click="open(`{{ $item->id }}`,`{{ $item->account_id }}`)">View Contact Details</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    

    <x-modal name="show-details" :show="false"  maxWidth="6xl">
        <div class="relative">
            <div class="absolute top-5 right-7">
                <button x-on:click="$dispatch('close-modal', 'show-details')" type="button" class="text-gray-500 hover:text-gray-900">
                    <x-heroicon-s-x class="w-5 h-5"/>
                </button>
            </div>
            <div class="bg-white border rounded-lg shadow-lg p-7">
                <h2 class="text-xl font-bold text-center text-primary-blue">{{ $account_family }}</h2>
                <div class="mt-8">
                    {{ $this->table }}
                </div>
            </div>
        </div>
    </x-modal>


</div>
