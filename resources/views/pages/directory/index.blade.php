@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script>
    $(document).ready( function () {
        $('#table').DataTable({
            "pageLength": 50
        });
    } );
</script>
@endpush

@section('content')
<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        SI Family Directory
    </h2>


    <div>
        <table id="table" class="cell-border">
            <thead class="border">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Class of</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($directory as $item)
                <tr>
                    <td>{{ $item->full_name }}</td>
                    <td>{{ $item->contact_type }}</td>
                    <td>{{ $item->graduation_year }}</td>
                    <td>View Contact Details</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection