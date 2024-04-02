<div>
    <div class="max-w-6xl px-8 mx-auto">
        <div class="grid gap-8 border-gray-100 md:border md:rounded-md md:shadow-md md:p-8 md:grid-cols-2">
            <div>
                <h4 class="text-xl font-semibold text-center lg:text-xl md:text-left">St. Ignatius College Preparatory's MySI Portal</h4>

                <ul class="pl-4 mt-8 space-y-4 text-sm list-disc lg:text-base">
                    <li class="text-justify">If you have used MySI in the past, please login as usual.</li>
                    <li class="text-justify">
                        If you have <strong>never used MySI</strong>, are a current SI family AND are looking to
                        re-register your child(ren) for the upcoming school year AND/OR have a non-SI child who
                        wants to transfer to SI, click <A class="text-link" HREF="{{ route('login.reregistration') }}">here</A>.
                    </li>
                    <li class="text-justify">
                        If you have <strong>never used MySI, are not a current SI family</strong> AND are looking to
                        fill out a transfer application, you will need to create an account. Enter your email
                        address and click “Continue.” If you previously applied to SI, the system will try to find
                        your information.

                    </li>
                </ul>
            </div>
            <div class="mb-16 md:pl-8 md:border-l md:mb-0">
                <h1 class="text-3xl font-bold text-primary-blue">Login</h1>
                <p class="mt-2 text-sm">Don't have an account? <a href="{{ route('register') }}"
                    class="font-bold text-link hover:underline">Create account</a>.</p>
                
                <x-auth-session-status class="mt-2 mb-4 text-center" :status="session('status')" />

                <form novalidate wire:submit.prevent="login" class="mt-8">

                    {{ $this->form }}

                    <div class="flex items-center justify-center mt-4 space-x-2 lg:space-x-8">
                        <a class="text-sm rounded-md text-link hover:text-link hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                            href="{{ route('forgot-username') }}">
                            {{ __('Forgot your username?') }}
                        </a>

                        <div class="text-gray-400">|</div>

                        <a class="text-sm rounded-md text-link hover:text-link hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>

                    </div>



                    <div x-data="{ show: $wire.entangle('show_password') }" class="flex justify-center mt-8">
                        <button x-show="!show" type="button" wire:click="next"
                            class="justify-center w-full text-center btn-primary-fixer">Continue</button>
                        <button x-show="show" x-cloak type="submit" class="btn-primary-fixer">Log In</button>
                    </div>

                    
                </form>
            </div>
        </div>
    </div>
</div>
