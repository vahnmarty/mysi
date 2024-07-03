<div class="max-w-xl px-4 mx-auto lg:px-8">

    <h1 class="text-4xl text-center font-base text-primary-blue">Create Account </h1>
    <div class="mt-8 text-base">
        <p>
            NOTE: Please use one account per family. Creating multiple accounts will cause data
            inconsistencies and may result in the wrong information being sent to SI.
        </p>
        <div class="mt-4">
            <p class="font-bold">Your password must have: </p>
            <ul class="pl-6 list-disc">
                <li>At least 1 uppercase letter</li>
                <li>At least 1 lowercase letter</li>
                <li>At least 1 number</li>
                <li>At least 1 special character (only use the following characters: ! @ # $ or %)</li>
                <li>Must be between 8 – 16 characters long</li>
            </ul>
        </div>
    </div>
    
    <div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form novalidate wire:submit.prevent="register"
             class="mt-8">

            {{ $this->form }}

            
            <div class="flex justify-center mt-8">
                <button x-show="next" type="submit" class="btn-primary-fixer">Register</button>
            </div>
            

            <p class="mt-8 text-sm text-center">Have an account? <a href="{{ route('login') }}" class="font-bold text-link">Login</a></p>
        </form>
    </div>
</div>