<div class="max-h-screen py-16">
    <div class="max-w-lg mx-auto">
        <div id="login-box" class="bg-white border rounded-lg shadow-lg">
            <div class="flex justify-center p-4 rounded-t-lg bg-primary-red">
                <img src="{{ asset('img/logo-white.png') }}" class="w-auto h-16" alt="">
            </div>
            <form wire:submit.prevent="authenticate" class="p-8 space-y-8">
                {{ $this->form }}
        
                <div class="flex justify-center mt-8">
                    <button  type="submit" class="btn-primary-fixer">Log In</button>
                </div>
            </form>
        </div>
    </div>
    
</div>
