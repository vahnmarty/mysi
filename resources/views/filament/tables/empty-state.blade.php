<div class="flex items-center justify-center p-4">
    <x-tables::empty-state
        :icon="$this->getTableEmptyStateIcon()"
        :actions="$this->getTableEmptyStateActions()"
    >
        <x-slot name="heading">
            {{ $this->getTableEmptyStateHeading() }}
        </x-slot>

        <x-slot name="description">
            The application will appear once an 8th grade student is added.  
            Only 8th grade students may apply.  <a href="{{ url('children') }}" class=" text-link">Click here</a> to create a child record.
        </x-slot>
    </x-tables::empty-state>
</div>