<div class="pb-16">
    <section>
        <div>
            <div class="flex justify-center">
                <img src="{{ asset('img/mail.svg') }}" class="w-24 h-24"/>
            </div>
            <div class="mt-8 text-center">
                <h1 class="text-3xl font-bold font-heading">Registration <span class="text-primary-blue">Completed</span></h1>
                <p class="mt-6">
                    Thank you for completing registration for the 2023-2024 School Year.  
                    We are excited to welcome your family to SI
                </p>
            </div>
        </div>

        <div class="pt-8 mt-8 border-t border-dashed">
            <h4 class="font-bold">A message for student:</h4>
            <p class="mt-3">
                Login today to your new <a  href="https://mail.google.com" target="_blank" class="text-link"><u>SI Google Account</u></a> and access your email using the credentials below. Make sure to use <b>@siprep.org</b>, not @gmail.com.
            </p>
            
            <div class="p-6 mt-5 border rounded-md shadow-md">
                <h3 class="font-bold text-primary-red">{{ $registration->student->getFullName() }}</h3>
                <div class="grid grid-cols-2 gap-8 mt-3">
                    <div class="p-4 bg-gray-200 border">
                        Your Email Address
                    </div>
                    <div class="p-4 bg-gray-200 border">
                        Your Password
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-sm">
                        You will receive important information via your SI email account throughout the summer, including messages about upcoming activities and your first assignment in June. SI students are expected to check their email inbox daily.
                    </p>
                    <p class="mt-4 text-sm">
                        If you would like to change your password, you must change it via the SI website by going to <a class="text-link" href="https://www.siprep.org/pw" target="_blank"><u>https://www.siprep.org/pw</u></a>.
                Do not use the “Forgot my password” link in the Google login box.
                    </p>
                    <p class="mt-4 text-sm">
                        If you have any difficulties with your email account, please contact <a class="text-link" href="mailto:SI_librarians@siprep.org?subject=Problems with Email Account" target="_blank"><u>SI_librarians@siprep.org</u></a>.
                    </p>
                </div>
            </div>

            <div class="mt-8">
                <p>
                    If you are interested in exclusive rising 9th grade programs this summer before the school year begins, please click on these two SI Summer programs links:
                </p>
                <div class="mt-4 space-y-4">
                    <div>
                        <p class="font-bold">Cat Camp (available Tuesday, March 21, 2023 at 10:00 am PT)</p>
                        <a class="text-link" href="https://www.siprograms.com/camps/cat-camp" target = "_blank">https://www.siprograms.com/camps/cat-camp</a>
                    </div>
                    <div>
                        <p class="font-bold">Rising 9th Grade Summer Classes (available Tuesday, March 21, 2023 at 10:00 am PT)</p>
                        <a class="text-link" href="https://www.siprograms.com/camps/cat-camp" target = "_blank">
                            https://www.siprograms.com/academics/9th-grade
                        </a>
                    </div>
                </div>
            </div>

            <div class="my-8 border border-dashed border-primary-red"></div>

            <div>
                <h4 class="text-2xl font-bold uppercase text-primary-blue font-heading ">Next Steps</h4>
                <p class="mt-3">Please review the Next Steps below to complete the registration process.</p>
            </div>

            <div class="mt-8">
                <p class="font-bold">Upload SFUSD Freshman Health Form: Due by July 1, 2023</p>

                <p class="mt-3">
                    Please download: <a class="text-link" href="{{ asset('files/SIFROSHHealthForm202324.pdf') }}" target = "_blank"><u>SFUSD Freshman Health Form</u></a>. Note that this form requires a doctor’s signature.
                </p>

                <form action="" class="px-8 py-12 mt-8 bg-red-100 border border-red-300 rounded-md">
                    {{ $this->healthForm }}
                </form>
            </div>

            <div class="my-8 border border-dashed border-primary-red"></div>

            <div class="p-4 bg-white border rounded-md shadow-md">
                <h3 class="text-xl font-bold text-primary-red">SI Athletics Ticket to Play Medical Clearance Form</h3>
                <p class="mt-1 text-sm font-bold text-gray-500">Due: June 1, 2023 – August 1, 2023</p>
                <div class="mt-3 text-sm">
                    To participate in SI Athletics, including tryouts, you must register to play through SI’s <a 
                    class="text-link" href="https://www.familyid.com/st-ignatius-college-preparatory/2023-24-athletic-registration" target="blank"><u>FamilyID system</u></a>.&nbsp;&nbsp;As a part of this process, you will be required to upload SI’s Ticket 
                    to Play Medical Clearance Form.&nbsp;&nbsp;The ticket to play requires a physical exam with a doctor.&nbsp;&nbsp;We strongly encourage you to schedule this exam between June 1, 2023 and August 1, 2023 to maintain athletic 
                    eligibility for the entire school year.&nbsp;&nbsp;The FamilyID website will open for registration on June 1.&nbsp;&nbsp;Please do not register until you are ready to upload your ticket to play.
                </div>
                <p class="mt-3 text-sm">
                    Download SI’s Ticket to Play Medical Clearance Form <a class="text-link" href="https://resources.finalsite.net/images/v1674767044/siprep/t6goeoxvhp5mj2nzsgcu/MedicalClearanceFormTemplate.pdf" target="_blank"><u>here</u></a>.
                </p>
            </div>

            <div class="p-4 mt-4 bg-white border rounded-md shadow-md">
                <h3 class="text-xl font-bold text-primary-red">
                    Visit the Wildcat Welcome Portal Now!
                </h3>
                <div class="mt-3 text-sm">
                    We will be in touch throughout the summer.&nbsp;&nbsp;Look for our Wildcats Welcome Newsletter every other Thursday in your inbox.&nbsp;&nbsp;Stay informed all summer by visiting our <a style="color: #0086e7; cursor: pointer;" href="http://www.siprep.org/welcome" target="_blank"><u>Wildcat Welcome Portal</u></a>.&nbsp;&nbsp;
                We will be updating this site throughout the summer.&nbsp;&nbsp;Answers to any questions that may arise over the summer can usually be found on the Welcome Portal.
                </div>
            </div>

            <div class="mt-8">
                <p>For questions about a specific topic, please feel free to email the individuals below.</p>

                <div class="grid grid-cols-4 gap-4 mt-4">
                    @foreach($departments as $dept)
                    <div class="p-3 bg-gray-100 border rounded-md">
                        <h4 class="font-bold">{{ $dept['department'] }}</h4>
                        <p>{{ $dept['person'] }}</p>
                        <a href="mailto:{{ $dept['email']}}" class="text-sm text-link">{{ $dept['email'] }}</a>
                    </div>
                    @endforeach
                </div>

            </div>
            
        </div>

    </section>


</div>
