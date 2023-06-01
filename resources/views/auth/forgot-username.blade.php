<x-guest-layout>

    <div class="max-w-xl px-4 mx-auto lg:px-8">

        <h1 class="text-4xl text-center font-base text-primary-blue">Forgot Username </h1>
        <div class="mt-8 text-sm">
            <p>
                Enter your email address and click “Submit” to get your username. Please note, your email may be
                associated with your spouse’s/partner’s email address/username. If so, you will need access to the
                email account to reset the password.
            </p>
        </div>
        
        <x-auth-session-status class="mb-4" :status="session('status')" />
    
        <form method="POST" action="{{ route('username.send') }}" class="mt-8">
            @csrf
    
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus placeholder="Email Address" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
    
            <div class="flex items-center justify-center mt-8">
                <button type="submit" class="btn-primary">Submit</button>
            </div>
        </form>
    </div>

   
</x-guest-layout>
