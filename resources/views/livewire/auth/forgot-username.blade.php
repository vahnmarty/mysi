<div class="max-w-xl px-4 mx-auto lg:px-8">

    <h1 class="text-4xl text-center font-base text-primary-blue">Forgot Username</h1>
    <div class="mt-8 text-sm">
        <p>
            Enter your email address and click “Submit” to get your username. Please note, your email may be
            associated with your spouse’s/partner’s email address/username. If so, you will need access to the
            email account to reset the password.
        </p>
    </div>
    
    <div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="submit"
             class="mt-8">

            {{ $this->form }}

            <div class="flex justify-center mt-8">
                <button x-show="next" type="submit" class="btn-primary">Submit</button>
            </div>

            <p class="mt-8 text-xs text-center">Back to login <a href="{{ route('login') }}" class="font-bold text-link">Login</a></p>
        </form>
    </div>
</div>