<div>

    <h1 class="text-4xl text-center font-base text-primary-blue">Login</h1>
    <div class="mt-8 text-sm font-bold text-center">
        <p>Welcome to the St. Ignatius College Preparatory Community Portal.</p>
        <p>Log in or create an account to interact with SI.</p>
    </div>
    
    <div class="max-w-lg px-8 mx-auto">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form x-data="{ 
                next: false,
             }" 
             x-on:showpassword.window="next = true"
             wire:submit.prevent="login"
             class="mt-8">

            {{ $this->form }}
            <!-- Remember Me -->
            <div x-show="next" class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-center mt-4 space-x-8 ">
                <a class="text-sm text-red-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your username?') }}
                </a>

                <a class="text-sm text-red-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                
            </div>


            <div class="flex justify-center mt-8">
                <button x-show="!next" wire:click="next" type="button" class="btn-primary">Continue</button>
                <button x-show="next" type="submit" class="btn-primary">Log In</button>
            </div>

            <p class="mt-8 text-xs text-center">Don't have an account? <a href="{{ route('register') }}" class="font-bold text-primary-red">Create account</a></p>
        </form>
    </div>
</div>