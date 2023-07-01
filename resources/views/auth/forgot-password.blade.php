<x-guest-layout>

    <div class="max-w-xl px-4 mx-auto lg:px-8">

        <h1 class="text-4xl text-center font-base text-primary-blue">Forgot Password </h1>
        <div class="mt-8 text-sm font-bold">
            <p>Enter the email address associated with your username to reset the password.</p>
        </div>
        
        <!-- <x-auth-session-status class="mb-4" :status="session('status')" />
         -->
        @if(session('status'))
        <div class="mt-8">
            <p>Check your email for instructions on how to reset your password.</p>
            <p class="mt-8 text-base text-center ">Back to <a href="{{ route('login') }}" class="font-bold text-link">Log in</a>.</p>
        </div>
        @else
        <form method="POST" action="{{ route('password.email') }}" class="mt-8" novalidate>
            @csrf
    
            <!-- Email Address -->
            <div>
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus placeholder="Email Address" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
    
            <div class="flex items-center justify-center mt-8">
                <button type="submit" class="btn-primary">Submit</button>
            </div>
        </form>
        @endif
    </div>

   
</x-guest-layout>
