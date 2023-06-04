<div class="max-w-xl px-4 mx-auto lg:px-8">

    <h1 class="text-4xl text-center font-base text-primary-blue">Create Account </h1>
    <div class="mt-8 text-sm">
        <p>
            NOTE: Please use one account per family. Creating multiple accounts will cause data
            inconsistencies and may result in the wrong information being sent to SI.
        </p>
    </div>
    
    <div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="register"
             class="mt-8">

            {{ $this->form }}

            <div class="flex justify-center mt-8">
                <button x-show="next" type="submit" class="btn-primary">Register</button>
            </div>

            <p class="mt-8 text-xs text-center">Have an account? <a href="{{ route('login') }}" class="font-bold text-link">Login</a></p>
        </form>
    </div>
</div>