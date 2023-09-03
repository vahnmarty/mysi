<x-filament::page>

    <div class="grid grid-cols-5 gap-8">

        <section class="col-span-2">
            <h4 class="text-lg font-bold">* Security Reminder</h4>
            <p>Make sure you have access to your email account before updating your administrator's password</p>
            <p class="mt-4">After the password has been updated, you are require to Login back with the new password.</p>
        </section>

        <section class="col-span-3">
            <form wire:submit.prevent="update">

                {{ $this->form }}
        
        
               <div class="py-8 mt-8">
                    <button type="submit">Update Password</button>
               </div>
            </form>
        </section>

    </div>
    
</x-filament::page>
