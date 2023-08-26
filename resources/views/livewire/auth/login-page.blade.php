<div>

    <h1 class="text-4xl text-center font-base text-primary-blue">Login</h1>
    <div class="max-w-2xl px-4 mx-auto mt-8 text-base text-center lg:px-8">
        <h4 class="text-xl font-semibold lg:text-xl">Welcome to St. Ignatius College Preparatory’s MySI Portal</h4>

        <p class="mt-8 text-sm lg:text-base">
            Enter your email address and click "Continue."  If you previously applied to SI, the system will try to find your email.  If it finds it, you will enter your password or set up a new one (if logging in to MySI for the first time).  If it does not find your email, you will need to create an account.
        </p>

    </div>
    
    <div class="max-w-lg px-8 mx-auto">
        <!-- Session Status -->
        <x-auth-session-status class="mt-2 mb-4 text-center" :status="session('status')" />

        <form 
             novalidate
             wire:submit.prevent="login"
             class="mt-8">

            {{ $this->form }}
            
            <!-- Remember Me -->
            <!-- <div x-show="next" class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div> -->

            <div class="flex items-center justify-center mt-4 space-x-2 lg:space-x-8 ">
                <a class="text-sm rounded-md text-link dark:text-gray-400 hover:text-link hover:underline dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('forgot-username') }}">
                    {{ __('Forgot your username?') }}
                </a>

                <div class="text-gray-400">|</div>

                <a class="text-sm rounded-md text-link dark:text-gray-400 hover:text-link hover:underline dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                
            </div>

            
            
            <div x-data="{ show: $wire.entangle('show_password') }" class="flex justify-center mt-8">
                <button x-show="!show" type="button" wire:click="next" class="btn-primary-fixer">Continue</button>
                <button x-show="show" x-cloak type="submit" class="btn-primary-fixer">Log In</button>
            </div>

            <p class="mt-8 text-sm text-center">Don't have an account? <a href="{{ route('register') }}" class="font-bold text-link hover:underline">Create account</a></p>
        </form>
    </div>
</div>