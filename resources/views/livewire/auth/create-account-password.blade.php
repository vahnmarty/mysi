<div class="pb-16">

    <h1 class="font-base text-4xl text-center text-primary-blue">Create Account</h1>
    
    <div class="max-w-lg mx-auto px-8">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="submit" class="mt-8">
            @csrf

            <div class="text-xs">
                <p class="font-bold">Your password must have:</p>											
                <ul class="flex flex-col">
                    @foreach($password_validation as $item)
                        @if($item['passed'])
                        <li class="text-success-700 font-bold">{{ $item['description'] }}</li>
                        @else
                        <li class="text-danger-700 font-bold">{{ $item['description'] }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="py-8">
                {{ $this->form }}
            </div>


            <div class=" flex justify-center">
                <button type="submit" class="btn-primary">Submit</button>
            </div>

        </form>
    </div>
</div>