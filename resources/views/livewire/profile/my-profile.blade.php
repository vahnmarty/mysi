<div>
   <div class="flex justify-between">
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            My Profile
        </h2>
        <div>
            <a href="{{ route('profile.edit') }}" class="btn-primary">Edit</a>
        </div>
   </div>

    <div class="pb-32 mt-8 space-y-8">
        
        <div class="grid grid-cols-2 gap-8">
            <div>
                <legend class="text-sm font-bold">First Name</legend>
                <p class="h-10 px-2 py-2 mt-1 bg-gray-100 border rounded-md">{{ $user->first_name }}</p>
            </div>
            <div>
                <legend class="text-sm font-bold">Last Name</legend>
                <p class="h-10 px-2 py-2 mt-1 bg-gray-100 border rounded-md">{{ $user->last_name }}</p>
            </div>
            <div>
                <legend class="text-sm font-bold">Email</legend>
                <p class="h-10 px-2 py-2 mt-1 bg-gray-100 border rounded-md">{{ $user->email }}</p>
            </div>

            <div>
                <legend class="text-sm font-bold">Phone</legend>
                <p class="h-10 px-2 py-2 mt-1 bg-gray-100 border rounded-md">{{ format_phone($user->phone) }}</p>
            </div>
        </div>

    </div>
    
</div>
