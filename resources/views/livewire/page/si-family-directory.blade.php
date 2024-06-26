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
                columnDefs: [
                    {
                    orderSequence: ['desc', 'asc'],
                    targets: '_all'
                    }
                ]
            });
        } );
        

    </script>
    @endpush


    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-semibold font-heading text-primary-blue">
                SI Family Directory
            </h2>
        </div>
        <p class="self-center hidden text-xs md:block">Last Updated On: {{ $last_updated_at }}</p>
    </div>

    <div class="mt-8" wire:ignore>
        <table id="table" class="cell-border" style="width: 100%">
            <thead class="text-sm bg-gray-200 border md:text-base">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Type</th>
                    <th>Class of</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                @foreach($directory as $item)
                <tr wire:key="dir{{ $item->id }}">
                    <td>{{ $item->first_name }}</td>
                    <td>{{ $item->last_name }}</td>
                    <td>
                        @if($item->contact_type == 'Student')
                        <div class="bg-primary-blue px-0.5 md:px-2 py-0.5 rounded-md text-gray-100 text-xs md:text-sm w-20 text-center">{{ $item->contact_type }}</div>
                        @else
                        <div class="bg-primary-red px-0.5 md:px-2 py-0.5 rounded-md text-gray-100 text-xs md:text-sm w-20 text-center">{{ $item->contact_type }}</div>
                        @endif
                    </td>
                    <td> {{ $item->grad_year }}</td>
                    <td>
                        <x-loading-icon wire:target="open(`{{ $item->id }}`,`{{ $item->account_id }}`)"/>  
                        <button type="button" class="hidden text-link md:block" wire:click="open(`{{ $item->id }}`,`{{ $item->account_id }}`)">View Contact Details</button>
                        <button type="button" class="text-link md:hidden" wire:click="open(`{{ $item->id }}`,`{{ $item->account_id }}`)">View</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    

    <x-modal name="show-details" :show="false"  maxWidth="md">
        <div class="relative modal-box">
            <div class="absolute top-5 right-7">
                <button x-on:click="$dispatch('close-modal', 'show-details')" type="button" class="text-gray-500 hover:text-gray-900">
                    <x-heroicon-s-x class="w-5 h-5"/>
                </button>
            </div>
            <div class="bg-white border rounded-lg shadow-lg p-7">
                <div class="hidden mt-8 ">
                    {{ $this->table }}
                </div>
                <div class="mt-8 space-y-2 md:space-y-4">
                    @foreach($contacts as $contact)
                    <div class="p-3 space-y-1 border border-gray-300 rounded-md md:space-y-2">
                        <div>
                            @if($contact->contact_type == 'Student')
                            <div class="bg-primary-blue px-0.5 md:px-2 py-0.5 rounded-md text-gray-100 text-xs md:text-sm w-20 text-center">{{ $contact->contact_type }}</div>
                            @else
                            <div class="bg-primary-red px-0.5 md:px-2 py-0.5 rounded-md text-gray-100 text-xs md:text-sm w-20 text-center">{{ $contact->contact_type }}</div>
                            @endif
                        </div>
                        <div class="text-sm space-y-1.5">
                            <h5 class="font-bold text-md">{{ $contact->full_name }}</h5>
                            @if($contact->grad_year)
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-academic-cap class="flex-shrink-0 w-4 h-4 text-gray-500"/> 
                                <span>{{ $contact->grad_year }}</span>
                            </div>
                            @endif

                            @if($contact->personal_email)
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-mail class="flex-shrink-0 w-4 h-4 text-gray-500"/> 
                                <span>{{ $contact->personal_email }}</span>
                            </div>
                            @endif

                            @if($contact->mobile_phone)
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-phone class="flex-shrink-0 w-4 h-4 text-gray-500"/> 
                                <span>{{ format_phone($contact->mobile_phone) }}</span>
                            </div>
                            @endif

                            @if($contact->address_street || $contact->address_city)
                            <div class="flex items-start gap-2">
                                <x-heroicon-o-location-marker class="flex-shrink-0 w-4 h-4 text-gray-500"/> 
                                <div>
                                    @if($contact->address_street)
                                    <div>{{ $contact->address_street }}</div>
                                    @endif
                                    <div>{{ $contact->address_city  . ', ' . $contact->address_state . ' ' . $contact->address_zip}}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-modal>



</div>
