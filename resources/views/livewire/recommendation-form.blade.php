<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Supplemental Recommendation
    </h2>

    @if($done)
    <div class="p-8 rounded-lg">

        <div class="flex justify-center">
            <img src="{{ asset('img/mail.svg') }}" class="w-40 h-40"/>
        </div>
        <div class="mt-8 text-center">
            <h1 class="text-3xl font-bold font-heading">Recommendation <span class="text-primary-blue">Received!</span></h1>
        </div>
    </div>
    @else
    <div class="pb-8 mt-8">
        <p><strong>DUE DATE</strong>: St. Ignatius College Preparatory must receive all supplemental recommendations by the end of the day on Wednesday, January 10, 2024 at 11:59 PT.</p>
    </div>
    

    <div class="pt-8 pb-32 border-t">
        <form wire:submit.prevent="save" class="p-8 bg-gray-100 border rounded-md " novalidate>
            <p class="mb-8 ">
                All required fields are color <span class="font-bold color-red-800">red</span> and have an asterisk (<span class="font-bold color-red-800">*</span>).
            </p>
            
            {{ $this->form }}

            <div class="flex justify-end gap-8 mt-8">
                <button type="submit" class="btn-primary">Save</button>
            </div>
        </form>
    </div>
    @endif

</div>
