<div>
    <div class="flex justify-between">
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            Supplemental Recommendation
        </h2>
    </div>



    <div class="pb-32 mt-8">

        <p><strong>DUE DATE</strong>: St. Ignatius College Preparatory must receive all supplemental recommendations by the end of the day on Thursday, December 15, 2022.</p>
        <p class="mt-8"> <strong>PLEASE NOTE</strong>: Once you submit your Supplemental Recommendation request, we cannot cancel it. Please make sure you are entering the correct information for your recommender.
        </p>
        <form wire:submit.prevent="submit" novalidate class="mt-8">

            {{ $this->form }}

            <div class="flex justify-center mt-8">
                <button type="button" wire:click="submit" class="btn-primary">Send</button>
            </div>
        </form>
    </div>
</div>
