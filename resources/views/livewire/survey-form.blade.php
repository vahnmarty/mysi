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
            Welcome to the SI Class of {{ app_variable('class_year') }}! Please answer the following questions to assist us in evaluating our admissions process.
        </p>
        @else
        <p>
            We truly appreciated your application to St. Ignatius College Preparatory. Please answer the following questions about our admissions process and the reasons that led you to decline your acceptance to SI.
        </p>
        @endif
        <form class="mt-8" wire:submit.prevent="submit" novalidate>

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
