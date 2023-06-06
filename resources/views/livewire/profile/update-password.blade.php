<form wire:submit.prevent="update" class="p-8 bg-gray-100 border rounded-md ">

    <h3 class="font-bold font-heading">New Password</h3>
    
    <div class="mt-8">
        {{ $this->form }}
    </div>

    <div class="flex justify-start mt-8">
        <button type="submit" class="btn-primary">Save Changes</button>
    </div>
</form>