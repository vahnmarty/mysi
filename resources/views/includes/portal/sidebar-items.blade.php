<div class="py-8">
    <ul>
        <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->is('dashboard')">
            <x-slot name="icon">
                <x-heroicon-o-home class="flex-shrink-0 w-4 h-4" />
            </x-slot>
            Home
        </x-sidebar-item>
        <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('parents*')">
            <x-slot name="icon">
                <x-heroicon-o-users class="flex-shrink-0 w-4 h-4" />
            </x-slot>
            Parent/Guardian
        </x-sidebar-item>
        <x-sidebar-item href="{{ url('children') }}" :active="request()->is('children*')">
            <x-slot name="icon">
                <x-heroicon-o-academic-cap class="flex-shrink-0 w-4 h-4" />
            </x-slot>
            Children
        </x-sidebar-item>
        <x-sidebar-item href="{{ url('address') }}" :active="request()->is('address*')">
            <x-slot name="icon">
                <x-heroicon-o-location-marker class="flex-shrink-0 w-4 h-4" />
            </x-slot>
            Address
        </x-sidebar-item>
        @if(Auth::user()->account?->hasEnrolled())
        <x-sidebar-item href="{{ url('legacy') }}" :active="request()->is('test')">
            <x-slot name="icon">
                <x-heroicon-o-archive class="flex-shrink-0 w-4 h-4" />
            </x-slot>
            Legacy
        </x-sidebar-item>
        <x-sidebar-item href="{{ url('healthcare') }}" :active="request()->is('healthcare')">
            <x-slot name="icon">
                <x-heroicon-o-hand class="flex-shrink-0 w-4 h-4" />
            </x-slot>
            Healthcare
        </x-sidebar-item>
        <x-sidebar-item href="{{ url('emergency-contact') }}" :active="request()->is('emergency-contact')">
            <x-slot name="icon">
                <x-heroicon-o-identification class="flex-shrink-0 w-4 h-4" />
            </x-slot>
            Emergency Contact
        </x-sidebar-item>
        @endif
    </ul>
</div>

@if(Auth::user()->account?->current_si_family)
<div>
    <div class="px-8">
        <div class="border-t "></div>
    </div>
    <ul class="font-medium text-gray-700">
        <x-app-menu-item 
            start_date="{{ notification_setting('course_placement_notification_start_date')?->value }}"
            end_date="{{ notification_setting('course_placement_notification_end_date')?->value }}">
            <li class="px-8 py-1 text-sm transition {{ request()->is('family-directory*') ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
                <a href="{{ url('family-directory') }}" class="inline-flex items-start w-full gap-3 text-gray-900 rounded-md text-md">
                    <x-heroicon-o-user-group class="flex-shrink-0 w-5 h-5" />
                    <strong>SI Family Directory</strong>
                </a>
            </li>
        </x-app-menu-item>
        <x-app-menu-item 
            start_date="{{ notification_setting('course_placement_notification_start_date')?->value }}"
            end_date="{{ notification_setting('course_placement_notification_end_date')?->value }}">
            <li class="px-8 py-1 text-sm transition {{ request()->is('communication-preference*') ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
                <a href="{{ url('communication-preference') }}" class="inline-flex items-start w-full gap-3 text-gray-900 rounded-md text-md">
                    <x-heroicon-o-pencil-alt class="flex-shrink-0 w-5 h-5" />
                    <span>Manage Communication Preference</span>
                </a>
            </li>
        </x-app-menu-item>
    </ul>
    <div class="px-8">
        <div class="border-t"></div>
    </div>
</div>
@endif

<div class="py-8">
    <ul class="font-medium text-gray-700">

        @if(Auth::user()->isAcceptedRegistration())
        <x-app-menu-item 
            start_date="{{ notification_setting('registration_start_date')?->value }}"
            end_date="{{ notification_setting('registration_end_date')?->value }}">
            <li class="px-8 py-1 text-sm transition {{ request()->is('course-placement*') ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
                <a href="{{ url('course-placement') }}" class="inline-flex items-start w-full gap-3 text-gray-900 rounded-md text-md">
                    <x-heroicon-o-book-open class="flex-shrink-0 w-5 h-5" />
                    <strong>Frosh Final Course Placement</strong>
                </a>
            </li>
        </x-app-menu-item>
        @endif

        @if(Auth::user()->account?->current_si_family)
        <x-app-menu-item 
            start_date="{{ notification_setting('re_registration_start_date')?->value }}"
            end_date="{{ notification_setting('re_registration_end_date')?->value }}">
            <li class="px-8 py-1 text-sm transition {{ request()->is('reregistration*') ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
                <a href="{{ url('reregistration') }}" class="inline-flex items-start w-full gap-3 text-gray-900 rounded-md text-md">
                    <x-heroicon-o-view-grid-add class="flex-shrink-0 w-5 h-5" />
                    <strong>Re-Registration for Returning SI Students</strong>
                </a>
            </li>
        </x-app-menu-item>
        @endif

        @if(Auth::user()->account?->current_si_family)
            @if(Auth::user()->account?->hasCurrentStudentFinancialAid())
            <x-app-menu-item 
                start_date="{{ notification_setting('re_registration_start_date')?->value }}"
                end_date="{{ notification_setting('re_registration_end_date')?->value }}">
                <x-sidebar-item align="start" href="{{ url('financial-aid-notifications') }}">
                    <x-slot name="icon">
                        <x-heroicon-o-clipboard-list class="flex-shrink-0 w-5 h-5" />
                    </x-slot>
                    Financial Aid Info for Returning SI Students
                </x-sidebar-item>
            </x-app-menu-item>
            @endif
        @endif


        <x-app-menu-item 
            start_date="{{ notification_setting('transfer_application_start_date')?->value }}"
            end_date="{{ notification_setting('transfer_application_end_date')?->value }}">
            <li class="px-8 py-1 text-sm transition {{ request()->is('transfer-applications*') ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
                <a href="{{ url('transfer-applications') }}" class="inline-flex items-start w-full gap-3 text-gray-900 rounded-md text-md">
                    <x-heroicon-o-switch-horizontal class="flex-shrink-0 w-5 h-5" />
                    <strong>Transfer Application</strong>
                </a>
            </li>
        </x-app-menu-item>

        @if(null)   
        @php
            $course_placement_notification_start_date = notification_setting('course_placement_notification_start_date');
            $course_placement_notification_end_date = notification_setting('course_placement_notification_end_date');

            $placement_start_date = $course_placement_notification_start_date->value;
            $placement_end_date = $course_placement_notification_end_date->value;
        @endphp

        @if(now()->gte($placement_start_date) && now()->lt($placement_end_date))
        <li class="px-8 py-1 text-sm transition {{ request()->is('course-placement*') ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
            <a href="{{ url('course-placement') }}" class="inline-flex items-start w-full gap-3 text-gray-900 rounded-md text-md">
                <x-heroicon-o-book-open class="flex-shrink-0 w-5 h-5" />
                <strong>Course Placement</strong>
            </a>
        </li>
        @endif
        @endif

        

        @php
            $registration_start_date = notification_setting('registration_start_date');
            $registration_end_date = notification_setting('registration_end_date');

            $reg_start_date = $registration_start_date->value;
            $reg_end_date = $registration_end_date->value;
        @endphp

        @if(now()->gte($reg_start_date) && now()->lt($reg_end_date) || empty($reg_start_date)  || empty($reg_end_date))
            @if(Auth::user()->isAcceptedRegistration())
            <li class="px-8 py-1 text-sm transition {{ request()->is('registration*') ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
                <a href="{{ url('registration') }}" class="inline-flex items-start w-full gap-3 text-gray-900 rounded-md text-md">
                    <x-heroicon-o-identification class="flex-shrink-0 w-5 h-5" />
                    @if(my_account()->hasRegisteredStudent())
                    <strong>Required Tasks for Frosh Students</strong>
                    @else
                    <strong>Frosh Registration</strong>
                    @endif
                </a>
            </li>
            @endif
        @endif

        @php
            $notification_start_date = notification_setting('notification_start_date');
            $notification_end_date = notification_setting('notification_end_date');

            $notif_start_date = $notification_start_date->value;
            $notif_end_date = $notification_end_date->value;
        @endphp

        @if(now()->gte($notif_start_date) && now()->lt($notif_end_date) || empty($notif_start_date)  || empty($notif_end_date))
        <li class="px-8 py-1 text-sm transition {{ request()->is('notifications*') ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
            <a href="{{ url('notifications') }}" class="inline-flex items-start w-full gap-3 text-gray-900 rounded-md text-md">
                <x-heroicon-o-bell class="flex-shrink-0 w-5 h-5" />
                <strong>Admissions Decision</strong>
            </a>
        </li>

        
        @endif

        <x-app-menu-item 
            start_date="{{ notification_setting('hspt_scores_start_date')?->value }}"
            end_date="{{ notification_setting('hspt_scores_end_date')?->value }}">
            <x-sidebar-item align="start" href="{{ url('hspt-scores') }}">
                <x-slot name="icon">
                    <x-heroicon-o-clipboard-list class="flex-shrink-0 w-5 h-5" />
                </x-slot>
                HSPT Scores
            </x-sidebar-item>
        </x-app-menu-item>


        @php
            $freshmen_application_start_date = notification_setting('freshmen_application_start_date');
            $freshmen_application_end_date = notification_setting('freshmen_application_hard_close_date');

            $app_start_date = $freshmen_application_start_date->value;
            $app_end_date = $freshmen_application_end_date->value;
        @endphp

        @if( inFailedPayments(accountId()) )
        <li class="px-4 py-1 mb-3 text-sm transition">
            <a href="{{ route('application.payment') }}" class="inline-flex items-start w-full gap-3 px-4 py-2 text-red-900 bg-red-100 rounded-md text-md hover:bg-red-200">
                <x-heroicon-o-cash class="flex-shrink-0 w-5 h-5" />
                <strong>Pay Application Fee</strong>
            </a>
        </li>
        @endif
        

        @if(now()->gte($app_start_date) && now()->lt($app_end_date) || empty($app_start_date)  || empty($app_end_date))
        <li class="px-8 py-1 text-sm transition {{ request()->is('admission*') ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
            <a href="{{ url('admission') }}" class="inline-flex items-start w-full gap-3 text-gray-900 rounded-md text-md">
                <x-heroicon-o-color-swatch class="flex-shrink-0 w-5 h-5" />
                <strong>Admissions Application</strong>
            </a>
        </li>
        
        <x-sidebar-item align="start" href="https://www.siprep.org/admissions/timeline" target="_blank">
            <x-slot name="icon">
                <x-heroicon-o-presentation-chart-line class="flex-shrink-0 w-5 h-5" />
            </x-slot>
            Admissions Timeline
        </x-sidebar-item>

        <x-sidebar-item align="start" href="https://www.siprep.org/admissions/visit/the-wildcat-experience" target="_blank">
            <x-slot name="icon">
                <x-heroicon-o-calendar class="flex-shrink-0 w-5 h-5" />
            </x-slot>
            Book a Wildcat Experience
        </x-sidebar-item>

        <x-sidebar-item align="start" href=" https://www.siprep.org/admissions/apply/entrance-exam" target="_blank">
            <x-slot name="icon">
                <x-heroicon-o-clipboard-list class="flex-shrink-0 w-5 h-5" />
            </x-slot>
            Entrance Exam (HSPT)
        </x-sidebar-item>

        <x-sidebar-item align="start" href="https://www.siprep.org/admissions/apply/admissions-video" target="_blank">
            <x-slot name="icon">
                <x-heroicon-o-video-camera class="flex-shrink-0 w-5 h-5" />
            </x-slot>
            Admissions Video
        </x-sidebar-item>

        @else
        <x-sidebar-item align="start" href="{{ url('applications') }}">
            <x-slot name="icon">
                <x-heroicon-o-presentation-chart-line class="flex-shrink-0 w-5 h-5" />
            </x-slot>
            View Application
        </x-sidebar-item>
        @endif

        <x-app-menu-item 
            start_date="{{ notification_setting('upload_accommodation_document_start_date')?->value }}"
            end_date="{{ notification_setting('upload_accommodation_document_end_date')?->value }}">
            <x-sidebar-item align="start" href="{{ route('application.accommodation-documents') }}">
                <x-slot name="icon">
                    <x-heroicon-o-document-add class="flex-shrink-0 w-5 h-5" />
                </x-slot>
                Upload Accommodations Documents
            </x-sidebar-item>
        </x-app-menu-item>

        @php
            $supplemental_recommendation_start_date = notification_setting('supplemental_recommendation_start_date');
            $supplemental_recommendation_end_date = notification_setting('supplemental_recommendation_end_date');

            $sup_start_date = $supplemental_recommendation_start_date->value;
            $sup_end_date = $supplemental_recommendation_end_date->value;
        @endphp

        @if(Auth::user()->hasSubmittedApplications())

            @if(now()->gte($sup_start_date) && now()->lt($sup_end_date) || empty($sup_start_date)  || empty($sup_end_date))
            <x-sidebar-item align="start" href="{{ url('supplemental-recommendation') }}">
                <x-slot name="icon">
                    <x-heroicon-o-gift class="flex-shrink-0 w-5 h-5" />
                </x-slot>
                Supplemental Recommendation
            </x-sidebar-item>
            @endif

        @endif


        @if(now()->gte($notif_start_date) && now()->lt($notif_end_date) || empty($notif_start_date)  || empty($notif_end_date))
        <x-sidebar-item href="{{ url('prep-shop') }}" :active="request()->is('prep-shop')">
            <x-slot name="icon">
                <x-heroicon-o-shopping-cart class="flex-shrink-0 w-5 h-5" />
            </x-slot>
            SI Prep Shop
        </x-sidebar-item>
        @endif
        

        <x-sidebar-item href="{{ url('help') }}" :active="request()->is('help')">
            <x-slot name="icon">
                <x-heroicon-o-question-mark-circle class="flex-shrink-0 w-5 h-5" />
            </x-slot>
            Contact Us
        </x-sidebar-item>
    </ul>
</div>