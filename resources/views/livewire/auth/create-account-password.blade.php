<div class="pb-16">

    <h1 class="text-4xl text-center font-base text-primary-blue">Set New Password</h1>
    
    <div class="max-w-lg px-8 mx-auto">
        <!-- Session Status -->

        @if(!$expired)
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="submit" class="mt-8" novalidate>
            @csrf

            <div class="text-base">
                <p class="font-bold">Your password must have: </p>
                <ul class="pl-6 list-disc">
                    <li>At least 1 uppercase letter</li>
                    <li>At least 1 lowercase letter</li>
                    <li>At least 1 number</li>
                    <li>At least 1 special character (only use the following characters: ! @ # $ or %)</li>
                    <li>Must be between 8 – 16 characters long</li>
                </ul>
            </div>
            <div class="py-8">
                {{ $this->form }}
            </div>


            <div class="flex justify-center">
                <button type="submit" class="btn-primary-fixer">Set Password</button>
            </div>

        </form>
        @else
        <div class="py-16">
            <p class="text-center text-primary-red">{{ $message }}</p>
        </div>
        @endif
    </div>
</div>