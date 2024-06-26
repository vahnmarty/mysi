<div class="max-w-xl px-4 mx-auto lg:px-8">

    <h1 class="text-4xl text-center font-base text-primary-blue">Forgot Username</h1>
    <div class="mt-8 text-base">
        <p class="font-bold">To retrieve your username, you must enter:</p>
        <p>1) Your first name and last name</p>
        <p>AND</p>
        <p>2) Either your email address or phone number</p>

        <p class="mt-4"><strong>NOTE: </strong> You will need access to your registered email address to reset the password.</p>
        
    </div>
    
    <div>

        
        @if($sent)
        <div class="py-3 mt-8 text-center text-green-700 border border-green-300 rounded-md bg-green-50">
            <p>Email sent to <strong>{{ $email }}</strong></p>
        </div>
        <div class="mt-8">
            <p class="text-base text-center ">Back to <a href="{{ route('login') }}" class="font-bold text-link">Login</a>.</p>
        </div>
        @elseif($multiple)
        <div class="px-3 py-3 mt-8 text-sm text-center text-yellow-700 border border-yellow-300 rounded-md bg-yellow-50">
            <p>The name and phone number is associated with multiple accounts.  Please contact <a href="mailto:admissions@siprep.org" class="font-bold text-link">admissions@siprep.org</a> for assistance.</p>
        </div>
        
        <div class="mt-8">
            <p class="text-base text-center ">Back to <a href="{{ route('login') }}" class="font-bold text-link">Login</a>.</p>
        </div>
        @else
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="submit"
             class="mt-8" novalidate>

            {{ $this->form }}

            <div class="flex justify-center mt-8">
                <button x-show="next" type="submit" class="btn-primary-fixer">Submit</button>
            </div>

            <p class="mt-8 text-sm text-center">To log in, click  <a href="{{ route('login') }}" class="font-bold text-link">here</a>.</p>
        </form>
        @endif
    </div>
</div>