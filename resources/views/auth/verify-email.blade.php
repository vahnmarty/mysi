<x-guest-layout>
    <div class="max-w-xl px-4 mx-auto lg:px-8">

        <h1 class="text-4xl text-center font-base text-primary-blue">Verify Email </h1>
        <div class="mt-8 text-sm">
            <p>Thank you for creating a MySI account.  A verification email has been sent to <strong>{{ $email }}</strong>. Please click on the link in the email to confirm your account.</p>
        </div>
        
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif
    
        <div class="flex items-center justify-between mt-8">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
    
                <div>
                    <x-primary-button>
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </div>
            </form>
    
            <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <button type="submit" class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
