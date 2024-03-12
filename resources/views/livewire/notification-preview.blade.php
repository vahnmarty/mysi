<div>
    
    <div class="px-8 mx-auto max-w-7xl">
        <div class="text-center">
            <h1 class="text-2xl font-bold">Notification Preview: {{ $notification->title }}</h1>
        </div>
        <div class="p-10 mt-8 bg-white border rounded-lg shadow-lg">

            <header class="flex gap-6">
                <img src="{{ asset('img/logo.png') }}" alt="">
                <div>
                    <p>
                        St. Ignatius College Preparatory<br>
                        2001 37th Avenue<br>
                        San Francisco, California 94116<br>
                        (415) 731-7500<br>
                    </p>
                    <p class="mt-6"> Office of Admissions</p>
                </div>
            </header>

            <article class="mt-16 html-content">
                {!! $content !!}
            </article>

            <form action="" class="mt-8">
                <input type="hidden" name="application_id" value="{{ $app->id }}">
                <input type="hidden" name="pdf" value="true">
                <button type="submit">View PDF</button>
            </form>
        </div>
    </div>
</div>
