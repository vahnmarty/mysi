<div>
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

        <p>Please answer the following questions to assist us in evaluating our admissions process and to complete your registration at St. Ignatius College Preparatory.</p>
        <p class="mt-8 mb-8 font-bold">
            Please list, in order of preference, the schools to which you applied , the admission decision (accepted/waitlisted/not accepted), and Financial Aid or scholarship information, if applicable:
        </p>
        <form wire:submit.prevent="submit">

            {{ $this->form }}

            <div class="flex justify-center mt-8">
                <button type="submit" class="btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
