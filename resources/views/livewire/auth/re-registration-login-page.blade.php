<div>

    <h1 class="text-4xl text-center font-base text-primary-blue">Current SI Family Login</h1>
    <div class="max-w-2xl px-4 mx-auto mt-8 text-base text-center lg:px-8">
        <h4 class="text-xl font-semibold lg:text-xl">Welcome to St. Ignatius College Preparatory's MySI Portal</h4>

        <p class="mt-8 text-sm lg:text-base">
            Enter your student's SI email address.  If you have more than one student attending SI, choose just one of the email addresses.
        </p>

        <p class="mt-3">
            <strong>NOTE:</strong> Only use 1 account to MySI per family.
        </p>

    </div>
    
    <div class="max-w-lg px-8 mx-auto">
        <!-- Session Status -->
        <x-auth-session-status class="mt-2 mb-4 text-center" :status="session('status')" />

        <form 
            x-data="{
                redirectLogin(){
                    let countdownElement = document.getElementById('countdown');
                    let countdown = 15;

                    setInterval(function() {
                        countdown--;
                        countdownElement.textContent = countdown;

                        if (countdown <= 0) {
                            window.location.href = `{{ url('login') }}`;
                        }
                    }, 1000);
                },
                redirectRegister(){
                    window.location.href = `{{ url('register') }}`;
                }
            }"
            x-on:redirect-login.window="redirectLogin"
            x-on:redirect-register.window="redirectRegister"
             novalidate
             wire:submit.prevent="next"
             class="mt-8">

            {{ $this->form }}

            

            @if($is_invalid)
            <div class="mt-4">
                <p class="text-primary-red">* This is not a valid SI email. </p>
                <p class="mt-4 text-primary-red">
                    You will be re-directed to the Login page in <span id="countdown">15</span> seconds.
                </p>
            </div>
            @endif

            @if($user)
            <div class="mt-4">
                <p class="text-primary-red">* This SI email is associated with an existing MySI account. Please use your MySI username to login. </p>
                <p class="mt-4 text-primary-red">
                    You will be re-directed to the Login page in <span id="countdown">15</span> seconds.
                </p>
            </div>
            @endif
        
            
            
            <div x-data="{ 
                    invalid: $wire.entangle('is_invalid'), 
                    existing: $wire.entangle('is_existing'), 
                    register: $wire.entangle('for_register')
                }" class="flex justify-center mt-8">

                <button x-show="!invalid && !existing" type="button" wire:click="next" class="btn-primary-fixer">
                    <x-loading-icon wire:target="next"/>
                    Continue
                </button>

                <a x-cloak  x-show="invalid || existing && !register" href="{{ url('login') }}" class="btn-primary-fixer">
                    Go to Login
                </a>

                <button x-show="register" x-cloak type="submit" wire:click="createAccount" class="btn-primary-fixer">
                    <x-loading-icon wire:target="createAccount"/>
                    Create Account
                </button>
            </div>
        </form>
    </div>

</div>