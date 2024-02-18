<div>

    @if(!$completed)


    <div class="flex justify-between">
        @if($type == 'Accepted')
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            Enroll at St. Ignatius College Preparatory Survey
        </h2>
        @else
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            Decline Acceptance at St. Ignatius College Preparatory Survey
        </h2>
        @endif
    </div>


    <div class="pb-32 mt-8">

        @if($type == 'Accepted')
        <p>
            Please answer the following questions to assist us in evaluating our admissions process and to complete your registration at St. Ignatius College Preparatory.
        </p>
        @else
        <p>
            We truly appreciated your application to St. Ignatius College Preparatory this year. Please answer the following questions to assist us in evaluating our admissions process and to decline your acceptance at St. Ignatius College Preparatory.
        </p>
        @endif
        <form class="mt-8" wire:submit.prevent="submit">

            {{ $this->form }}

            <div class="flex justify-center mt-8">
                <button type="submit" class="btn-primary">Submit</button>
            </div>
        </form>
    </div>

    @else
    <div class="mt-16">
        <p class="text-2xl font-bold text-center text-primary-blue">Thank you for taking our Survey.</p>
    </div>
    @endif
</div>
