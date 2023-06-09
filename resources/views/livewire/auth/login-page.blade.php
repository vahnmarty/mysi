<div>

    <h1 class="text-4xl text-center font-base text-primary-blue">Login</h1>
    <div class="mt-8 text-base text-center">
        <p>Welcome to St. Ignatius College Preparatory’s MySI portal.</p>
        <p>Log in or create an account to interact with SI.</p>
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
            <div x-show="next" class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-center mt-4 space-x-8 ">
                <a class="text-sm rounded-md text-link dark:text-gray-400 hover:text-link hover:underline dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('forgot-username') }}">
                    {{ __('Forgot your username?') }}
                </a>

                <div class="text-gray-400">|</div>

                <a class="text-sm rounded-md text-link dark:text-gray-400 hover:text-link hover:underline dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                
            </div>

            
            
            <div class="flex justify-center mt-8">
                <button  type="submit" class="btn-primary">Log In</button>
            </div>

            <p class="mt-8 text-sm text-center">Don't have an account? <a href="{{ route('register') }}" class="font-bold text-link hover:underline">Create account</a></p>
        </form>
    </div>
</div>