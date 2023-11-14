@if(Auth::user()->isImpersonated())
<div class="flex justify-center gap-4 py-2 text-xs text-center text-blue-600 bg-blue-100">
    <p>You are currently using <span class="underline">{{ Auth::user()->name }}</span>.</p>
    <a href="#" wire:click.prevent="leave" class="font-bold hover:underline">Leave</a>
</div>
@endif