<x-filament::page>

    <div>

        <section class="col-span-3">
            <form wire:submit.prevent="update">

                {{ $this->form }}
                

                <div style="margin-top: 32px;">
                    <x-filament::button
                    type="submit"
                    class="filament-page-modal-button-action"
                >
                        Update Password
                    </x-filament::button>
                </div>

               </div>
            </form>
        </section>

    </div>
    
</x-filament::page>
