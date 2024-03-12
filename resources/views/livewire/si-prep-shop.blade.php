<div>


    <div class="flex justify-between">
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            SI Prep Shop
        </h2>
    </div>

    <section class="mt-8">
        <p>
            Congratulations to the SI Class of {{ app_variable('class_year') }}!  We are excited to welcome our newest Wildcats!  Feel free to stop by campus and grab some SI gear or visit the Prep Shop's website by clicking <a href="https://siprepshop.com" target="_blank" class="text-link">here</a>!  Below are the extended Prep Shop hours for this week:
        </p>
        <div class="mt-8">
            {{ $this->table }}
        </div>

        <p class="mt-8">Go Cats!</p>
    </section>

</div>