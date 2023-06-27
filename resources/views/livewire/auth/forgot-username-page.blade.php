<div class="max-w-xl px-4 mx-auto lg:px-8">

    <h1 class="text-4xl text-center font-base text-primary-blue">Forgot Username</h1>
    <div class="mt-8 text-sm">
        <p class="font-bold">
            Enter your first and last name and either your email address or your phone number and click "Submit" to get your username. Please note, your email may be
            associated with your spouse’s/partner’s email address. If so, you will need access to the email account to reset the password.
        </p>
    </div>
    
    <div>

        @if($sent)
        <div class="py-3 mt-8 text-center text-green-700 border border-green-300 rounded-md bg-green-50">
            <p>Email sent to <strong>{{ $email }}</strong></p>
        </div>
        @else
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="submit"
             class="mt-8">

            {{ $this->form }}

            <div class="flex justify-center mt-8">
                <button x-show="next" type="submit" class="btn-primary">Submit</button>
            </div>

            <p class="mt-8 text-base text-center">To log in, click  <a href="{{ route('login') }}" class="font-bold text-link">here</a>.</p>
        </form>
        @endif
    </div>
</div>