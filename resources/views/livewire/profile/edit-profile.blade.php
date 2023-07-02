<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Edit Profile
    </h2>

    <div class="pb-32 mt-8 space-y-8">
        <form wire:submit.prevent="updateProfile" class="p-8 bg-gray-100 border rounded-md" novalidate>

            <h3 class="mb-8 font-bold font-heading">Basic Details</h3>
            {{ $this->profileForm }}

            <div class="flex justify-start mt-8">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>

        <form wire:submit.prevent="updateEmail" class="p-8 bg-gray-100 border rounded-md " novalidate>

            <h3 class="mb-4 font-bold font-heading">Email/Username</h3>
            
            @if($email_request)
            <p class="text-sm text-red-600">*To update your email address, check your inbox and confirm email address.</p>
            @endif
            
            <div class="mt-8">
                {{ $this->emailForm }}
            </div>

            <div class="flex justify-start mt-8">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>

        @livewire('profile.update-password')

    </div>

    
</div>
