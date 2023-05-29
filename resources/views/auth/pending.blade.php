<x-guest-layout>
    <div>

        <h1 class="font-base text-4xl text-center text-primary-blue">Account Pending</h1>
        <div class="text-center mt-8">
            <p class="font-bold text-sm">Please check your Inbox or Spam folder to to create your account.</p>

            <p class="mt-8">Email sent to <strong class="font-bold  text-primary-red">{{ $account->email }}.</strong></p>

        </div>
        
        
    </div>
</x-guest-layout>
