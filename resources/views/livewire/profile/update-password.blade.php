<form wire:submit.prevent="update" class="p-8 bg-gray-100 border rounded-md " novalidate>

    <h3 class="font-bold font-heading">Change Password</h3>

    <div class="mt-4 text-sm">
        <p class="font-bold">Your password must have: </p>
        <ul class="flex flex-col pl-4 text-sm list-disc">
            <li>At least 1 uppercase letter</li>
            <li>At least 1 lowercase letter</li>
            <li>At least 1 number</li>
            <li>At least 1 special character (only use the following characters: ! @ # $ or %)</li>
            <li>Must be between 8 – 16 characters long</li>
        </ul>
    </div>
    
    <div class="mt-8">
        {{ $this->form }}
    </div>

    <div class="flex justify-start mt-8">
        <button type="submit" class="btn-primary">Save Changes</button>
    </div>
</form>