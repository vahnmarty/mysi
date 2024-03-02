<div class="p-4 bg-white border rounded-md shadow-md">
    @if($completed)
    <h3 class="text-xl font-bold text-primary-red">SI Deans’ Office Required Form (Completed)</h3>
    @else
    <h3 class="text-xl font-bold text-primary-red">SI Deans’ Office Required Form</h3>
    @endif
    <p class="mt-1 text-sm font-bold text-gray-500">
        Upload SFUSD Freshman Health Form: Due by {{ app_variable('health_form_due_date', 'display_value') }}
    </p>
    <p class="mt-3">
        Please download: <a class="text-link" href="{{ asset('files/SIFreshmanHealthForm.pdf') }}" target = "_blank"><u>SFUSD Freshman Health Form</u></a>. Note that this form requires a doctor’s signature.
    </p>
    <div class="mt-3 text-sm">
        <form wire:submit.prevent="uploadHealthForm">
            {{ $this->form }}
        </form>
    </div>
</div>