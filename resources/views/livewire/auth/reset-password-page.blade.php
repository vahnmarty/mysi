<div class="pb-16">

    <h1 class="text-4xl text-center font-base text-primary-blue">Reset Password</h1>
    
    <div class="max-w-lg px-8 mx-auto">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="submit" class="mt-8" novalidate>
            @csrf

            <div class="text-base">
                <p class="font-bold">Your password must have:</p>											
                <ul class="flex flex-col pl-4 text-sm list-disc">
                    @foreach($password_validation as $item)
                        @if($item['passed'])
                        <li class="text-success-700">{{ $item['description'] }}</li>
                        @elseif($item['passed'] == false)
                        <li class="text-danger-700">{{ $item['description'] }}</li>
                        @else
                        <li class="">{{ $item['description'] }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="py-8">
                {{ $this->form }}
            </div>


            <div class="flex justify-center">
                <button type="submit" class="btn-primary">Submit</button>
            </div>

        </form>
    </div>
</div>