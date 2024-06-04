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


    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        SI Family Directory
    </h2>

    <div class="mt-8" wire:ignore>
        <table id="table" class="cell-border">
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
        <div class="p-10 bg-white border rounded-lg shadow-lg">
            {{ $this->table }}
        </div>
    </x-modal>


</div>
