<x-filament::page>


<form wire:submit.prevent="save">
    {{ $this->form }}

    <div style="margin-top: 32px;">
        <x-filament::button
        type="submit"
        class="filament-page-modal-button-action"
    >
            Login
        </x-filament::button>
    </div>
</form>

</x-filament::page>
