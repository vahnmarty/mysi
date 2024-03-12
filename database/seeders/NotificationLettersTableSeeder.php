<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationLetter;
use App\Enums\NotificationStatusType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotificationLettersTableSeeder extends Seeder
{
    public $kristy_signature, $ken_signature, $site_url;

    public function __construct()
    {
        $this->kristy_signature = asset('img/kristy_signature.png');
        $this->ken_signature = asset('img/ken_signature.png');
        $this->site_url = url('/');
    }

    public function run(): void
    {
        $this->createAccepted();
        $this->createWaitlisted();
        $this->createNotAccepted();
        $this->createFinancialAid();
        $this->createAcceptedWithHonors();
    }

    public function createAccepted()
    {
        $the_letter = NotificationLetter::where('reference', NotificationStatusType::Accepted)->exists();

        if(!$the_letter)
        {
            $letter = new NotificationLetter;
            $letter->reference = NotificationStatusType::Accepted;
            $letter->title = 'Accepted';
            $letter->content =`<p>@{app.notification_date}<br><br>@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parents_name_salutation}:<br><br><strong>Congratulations!</strong> &nbsp;@{student.first_name} @{student.last_name} has been <strong>Accepted</strong> to St. Ignatius College Preparatory. &nbsp;Welcome to our school community! &nbsp;We congratulate @{student.first_name} for the academic diligence that has made this success possible. &nbsp;The entire SI community pledges itself to your child's intellectual, spiritual, and social development over the next four years. &nbsp;We look forward to your participation and cooperation in this endeavor.<br><br><strong>To reserve a place in the Class of @{app.class_year}</strong>, please click on the <strong>Enroll at SI</strong> button below and make a deposit of <strong>$@{application_status.deposit_amount}.</strong> &nbsp;As a courtesy to students on our waitlist, we ask that those who do not intend to register at SI indicate their intention by clicking on the Decline button below. &nbsp;<strong><span style="color: red">You must accept enrollment by @{app.acceptance_deadline_date} or else your acceptance will be forfeited.</span></strong><br><br>@{student.first_name}'s <strong>Acceptance</strong> is based on @{student.pronoun_possessive} academic achievements and the gifts @{student.pronoun_subject} will bring to the SI community. &nbsp;The online registration system will be available beginning on @{app.registration_start_date}, with additional information, important dates, and course information. &nbsp;To access the online registration system, visit <a href="https://mytest.siprep.org" as_button="false" button_theme=""><span style="color: blue">https://mytest.siprep.org</span></a>, using the username and password you used to apply. &nbsp;<br><br>Tuition for the @{app.academic_year}} academic year is $@{system.payment.tuition_fee}. &nbsp;The Business Office will have information on tuition payment plans and schedules in the online registration system. &nbsp;For families who applied for financial assistance, the Business Office has posted the Financial Assistance Committee's decision below. &nbsp;The ability to enroll or decline your acceptance will appear after you acknowledge your financial assistance details.<br><br>We had over @{app.number_of_applicants} applicants apply to St. Ignatius College Preparatory for the Class of @{app.class_year}. &nbsp;The Admissions Committee was fortunate to have so many qualified applicants to select from in this highly competitive applicant pool. &nbsp;We are excited to have @{student.first_name} as a member of our talented Frosh class. &nbsp;@{student.first_name}'s acceptance is contingent upon @{student.pronoun_possessive} continued academic achievement, good citizenship, and successful completion of eighth grade at @{student.official_school}. &nbsp;It is our intention that your student is academically challenged and receives individual attention, which are hallmarks of a Jesuit education. We are looking forward to working closely with you and @{student.first_name} over the next four years. &nbsp;Once again, <strong>congratulations!</strong></p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">Sincerely,</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p><a href="https://mytest.siprep.org/img/kristy_signature.png" as_button="false" button_theme=""><img src="https://mytest.siprep.org/img/kristy_signature.png" width="196" height="100"></a></p><p><br>Ms. Kristy Cahill Jacobson '98<br>Director of Admissions</p>`;
            $letter->save();
        }
        
    }

    public function createWaitlisted()
    {
        $the_letter = NotificationLetter::where('reference', NotificationStatusType::WaitListed)->exists();

        if(!$the_letter)
        {
            $letter = new NotificationLetter;
            $letter->reference = NotificationStatusType::WaitListed;
            $letter->title = NotificationStatusType::WaitListed;
            $letter->content = `<p>@{app.notification_date}<br><br>@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parents_name_salutation}:<br><br>The Admissions Committee wants to thank you and @{student.first_name} for submitting a very thoughtful application. &nbsp;The Committee was very impressed with @{student.first_name}'s many fine qualities.<br><br>After careful review, the Admissions Committee has placed @{student.first_name} on the <strong>Waitlist</strong> for the Class of @{app.class_year}. &nbsp;The Waitlisted applicants were extremely competitive candidates in the applicant pool. &nbsp;We are aware that @{student.first_name} likely has other admission offers from which to choose. &nbsp;Being placed on the St. Ignatius College Preparatory Waitlist is evidence of the strong positive impression @{student.first_name} made throughout our review process. &nbsp;Waitlisted applicants were carefully selected by the Admissions Committee as students who they would like as members of the incoming Freshman class.<br><br>Click below for important Waitlist Information from the Admissions Team. &nbsp;Please read it carefully as it answers the most frequently asked questions, and details all pertinent information available.<br><br>We had over @{app.number_of_applicants} applicants apply to St. Ignatius College Preparatory for 375 places in the Class of @{app.class_year}. &nbsp;There were many qualified applicants in this large and talented applicant pool that we were unable to accept. &nbsp;We appreciate your patience and understanding while awaiting our final decision. &nbsp;Thank you for your interest in St. Ignatius College Preparatory and for entrusting us with @{student.first_name}'s application this year.</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">Sincerely,</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p><a href="https://mytest.siprep.org/img/kristy_signature.png" as_button="false" button_theme=""><img src="https://mytest.siprep.org/img/kristy_signature.png" width="196" height="100"></a></p><p><br>Ms. Kristy Cahill Jacobson '98<br>Director of Admissions</p>`;

            $letter->save();
        }

        $faq_letter = NotificationLetter::where('reference', 'Waitlist FAQ')->exists();

        if(!$faq_letter)
        {
            $letter = new NotificationLetter;
            $letter->title = 'Waitlist FAQ';
            $letter->content = `<h2 style="text-align: center; text-align: center; text-align: center"><strong><u>Information About the Waitlist</u></strong></h2><p><br>We have prepared the following responses to some common questions about the <strong>Waitlist</strong> at St. Ignatius College Preparatory and hope you will find this information helpful.<br><br><strong>What is my position on the Waitlist?</strong><br><br><span style="color: rgb(0, 0, 0)">Only students who complete the </span><a href="https://forms.gle/pHEuuDrDS9Ru7whr8" as_button="false" button_theme=""><u><span style="color: rgb(0, 0, 255)">“Remain on Waitlist” form</span></u></a><span style="color: rgb(0, 0, 0)"> will be considered for admission. The waitlist is </span><strong><span style="color: rgb(0, 0, 0)">not ranked</span></strong><span style="color: rgb(0, 0, 0)">. It represents a pool of qualified and interested applicants that the Admissions Committee will select from to fill openings and balance the class. The Admissions Committee will continue to evaluate the qualifications of each candidate before making selections from the waitlist.&nbsp;</span><br><br><strong>I wish/do not wish to remain on the waitlist - what should I do?</strong><br><br>If you wish to remain on the waitlist and under consideration for admission, please fill out the <a href="https://forms.gle/pHEuuDrDS9Ru7whr8" as_button="false" button_theme=""><u><span style="color: rgb(0, 0, 255)">“Remain on Waitlist” form</span></u></a><u><span style="color: rgb(0, 0, 255)">.</span></u><span style="color: rgb(0, 0, 255)"> </span>Only applicants who fill out this form will be considered for admission. We appreciate your continued interest in SI!</p><p>If you <strong><u>do not</u></strong> wish to remain on the waitlist, please send an email to admissions@siprep.org to let us know.<br><br><strong>What are my chances of getting in, and when will I hear?</strong><br><br>We have admitted the number of applicants predicted to fill our entering Frosh class and will offer admission to applicant(s) from the waitlist if the total of those who accept our initial admission offers drops below the number of spaces available in the class. The Admissions Committee will continue to make decisions as spaces become available. If you would like to remain on the waitlist, please fill out the <a href="https://forms.gle/pHEuuDrDS9Ru7whr8" as_button="false" button_theme=""><u><span style="color: rgb(0, 0, 255)">“Remain on Waitlist” form</span></u></a><u><span style="color: rgb(0, 0, 255)">.</span></u><span style="color: rgb(0, 0, 255)"> </span>Only applicants who fill out this form will be considered for admission.</p><p><br>After our March 22nd registration deadline, we will be in a better position to determine how many spaces will be available for those students on our waitlist. All applicants who fill out the <a href="https://forms.gle/pHEuuDrDS9Ru7whr8" as_button="false" button_theme=""><u><span style="color: rgb(0, 0, 255)">“Remain on Waitlist” form</span></u></a><span style="color: rgb(0, 0, 255)"> </span>will be notified (via email) as soon as possible, but no later than March 26th, of your final status.</p><p><br><strong>What if I have applied for Financial Assistance?</strong><br><br>At this time, we have distributed all the tuition assistance available to accepted students. We predict that minimal or no financial assistance will be available to students accepted off the waitlist.<br><br><strong>May I submit additional information?</strong><br><br>Information currently in the applicant’s file will be considered, as the Admissions Committee would not be able to review every waitlisted applicant’s additional information during this period of time.<br></p>`;

            $letter->save();
        }
        
    }

    public function createNotAccepted()
    {
        $the_letter = NotificationLetter::where('reference', NotificationStatusType::NotAccepted)->exists();

        if(!$the_letter)
        {
            $letter = new NotificationLetter;
            $letter->reference = NotificationStatusType::NotAccepted;
            $letter->title = 'Not Accepted';
            $letter->content = `<p>@{app.notification_date}</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">Dear @{parents_name_salutation}:</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">The Admissions Committee wants to thank you and @{student.first_name} for submitting a very thoughtful application. &nbsp;We were fortunate to have so many qualified applicants to select from in this highly competitive applicant pool. &nbsp;The Committee was very impressed with @{student.first_name}'s many fine qualities.</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">We had over @{app.number_of_applicants} applicants apply to St. Ignatius College Preparatory for the Class of @{app.class_year}. &nbsp;We regret that we will not be able to offer @{student.first_name} a place in SI's Freshman class. &nbsp;There were many qualified applicants in this large and talented pool that we were unable to accept. &nbsp;@{student.first_name} is to be congratulated for all @{student.pronoun_subject} has already accomplished.</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">We sincerely wish @{student.first_name} continued success in high school. &nbsp;The high school @{student.pronoun_subject} attends will be fortunate to have @{student.pronoun_personal} as a student. &nbsp;Thank you for entrusting us with @{student.first_name}'s application. &nbsp;We appreciate your interest in St. Ignatius College Preparatory and your understanding of how difficult our selection process was this year with so many qualified applicants.</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">Respectfully,</p><p class="MsoNormal">&nbsp;</p><p><a href="https://mytest.siprep.org/img/kristy_signature.png" as_button="false" button_theme=""><img src="https://mytest.siprep.org/img/kristy_signature.png" width="196" height="100"></a></p><p><br>Ms. Kristy Cahill Jacobson '98<br>Director of Admissions</p>`;

            $letter->save();
        }
        
    }

    public function createFinancialAid()
    {
        $letterA = NotificationLetter::where('title', 'FA Letter A')->exists();

        if(!$letterA)
        {
            $letter = new NotificationLetter;
            $letter->title = 'FA Letter A';
            $letter->content = `<p class="MsoNormal">@{app.notification_date}<br><br>@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parents_name_salutation}:<br><br>My sincere congratulations to @{student.first_name} upon acceptance to the St. Ignatius College Preparatory Class of @{app.class_year}!<br><br>I am happy to inform you that @{student.first_name} has been awarded a Financial Assistance Scholarship in honor of Jon Sobrino, SJ.&nbsp; This award for the @{app.academic_year}} academic year is $@{application_status.annual_financial_aid_amount}. &nbsp;Provided your financial circumstances do not change significantly, your total anticipated amount of tuition assistance over 4 years would be $@{application_status.total_financial_aid_amount}. &nbsp;Your registration fee for Freshman year will be $@{application_status.deposit_amount}.&nbsp; Please note that we will still verify your 2023 tax returns and you are required to re-apply for financial assistance through TADS every year your student is enrolled at Sl.<br><br>In addition to the financial assistance amount, @{student.first_name} will also receive benefits through the Arrupe Assistance Program, which provides some non-tuition related financial support including a subsidy for books and academic materials, fee discounts and waivers for AP and SAT tests, discounts for co-curricular and athletic program fees, and more. &nbsp;On average, these non-tuition related benefits save our families $3,500 over four years. &nbsp;You will receive more specific information about this in the coming month.<br><br>We look forward to partnering with you as we provide an exceptional Jesuit education in the next four years.<br><br></p><p class="MsoNormal"><br>Sincerely,</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p><img src="https://mytest.siprep.org/img/ken_signature.png" width="181" height="80"></p><p><br>Ken Stupi<br>Vice President of Finance &amp; Administration</p>`;

           

            $letter->save();
        }

        $letterB = NotificationLetter::where('title', 'FA Letter B')->exists();

        if(!$letterB)
        {
            $letter = new NotificationLetter;
            $letter->title = 'FA Letter B';
            $letter->content = `<p class="MsoNormal">@{app.notification_date}<br><br>@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parents_name_salutation}:<br><br>My sincere congratulations to @{student.first_name} upon acceptance to the St. Ignatius College Preparatory Class of @{app.class_year}!<br><br>I am happy to inform you that @{student.first_name} has been awarded a Financial Assistance Scholarship in honor of Jon Sobrino, SJ. &nbsp;This award for the @{app.academic_year}} academic year is $@{application_status.annual_financial_aid_amount}. &nbsp;Provided your financial circumstances do not change significantly, your total anticipated amount of tuition assistance over 4 years would be $@{application_status.total_financial_aid_amount}. &nbsp;Your registration fee for Freshman year will be $@{application_status.deposit_amount}.&nbsp; Please note that we will still verify your 2023 tax returns and you are required to re-apply for financial assistance through TADS every year your student is enrolled at Sl.<br><br>In addition to the financial assistance amount, @{student.first_name} will also receive benefits through the Arrupe Assistance Program, which provides some non-tuition related financial support including a subsidy for books and academic materials, fee discounts and waivers for AP and SAT tests, discounts for co-curricular and athletic program fees, and more. &nbsp;On average, these non-tuition related benefits save our families $3,500 over four years. &nbsp;You will receive more specific information about this in the coming month.<br><br>We have made every effort to fairly evaluate your family's demonstrated need. &nbsp;We cannot support any appeals for additional aid unless there have been significant changes in your financial circumstances that occurred after your application for aid was filed. &nbsp;If this is the case, please detail and document these changes by noon on Monday, March 20. &nbsp;All required documents, including your 2023 taxes, must be on file in your TADS application to be considered for an appeal.<br><br>Click <a href="https://docs.google.com/forms/d/e/1FAIpQLSf_KibnGVPgGl40zRTlWbWapgloxWmHGaCq8Wzcsj6M5rcZsg/viewform?pli=1" target="_blank" as_button="false" button_theme=""><span style="color: blue">here</span></a> to submit an appeal.<br><br>We look forward to partnering with you as we provide an exceptional Jesuit education in the next four years.<br><br></p><p class="MsoNormal"><br>Sincerely,</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p><img src="https://mytest.siprep.org/img/ken_signature.png" width="181" height="80"></p><p><br>Ken Stupi<br>Vice President of Finance &amp; Administration</p>`;

            $letter->save();
        }

        $letterC = NotificationLetter::where('title', 'FA Letter C')->exists();

        if(!$letterC)
        {
            $letter = new NotificationLetter;
            $letter->title = 'FA Letter C';
            $letter->content = `<p class="MsoNormal">@{app.notification_date}<br><br>@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parents_name_salutation}:<br><br>My sincere congratulations to @{student.first_name} upon acceptance to the St. Ignatius College Preparatory Class of @{app.class_year}!<br><br>I am happy to inform you that @{student.first_name} has been awarded a Financial Assistance Scholarship in honor of Matteo Ricci, SJ. &nbsp;This award for the @{app.academic_year}} academic year is $@{application_status.annual_financial_aid_amount}. &nbsp;Provided your financial circumstances do not change significantly, your total anticipated amount of tuition assistance over 4 years would be $@{application_status.total_financial_aid_amount}. &nbsp;Your registration fee for Freshman year will be $@{application_status.deposit_amount}.&nbsp; Please note that we will still verify your 2023 tax returns and you are required to re-apply for financial assistance through TADS every year your student is enrolled at Sl.<br><br>In addition to the financial assistance amount, @{student.first_name} will also receive benefits through the Arrupe Assistance Program, which provides some non-tuition related financial support including a subsidy for books and academic materials, fee discounts and waivers for AP and SAT tests, discounts for co-curricular and athletic program fees, and more. &nbsp;On average, these non-tuition related benefits save our families $2,000 over four years. &nbsp;You will receive more specific information about this in the coming month.<br><br>We have made every effort to fairly evaluate your family's demonstrated need. &nbsp;We cannot support any appeals for additional aid unless there have been significant changes in your financial circumstances that occurred after your application for aid was filed. &nbsp;If this is the case, please detail and document these changes by noon on Monday, March 20. &nbsp;All required documents, including your 2023 taxes, must be on file in your TADS application to be considered for an appeal.<br><br>Click <a href="https://docs.google.com/forms/d/e/1FAIpQLSf_KibnGVPgGl40zRTlWbWapgloxWmHGaCq8Wzcsj6M5rcZsg/viewform?pli=1" target="_blank" as_button="false" button_theme=""><span style="color: blue">here</span></a> to submit an appeal.<br><br>We look forward to partnering with you as we provide an exceptional Jesuit education in the next four years.<br><br></p><p class="MsoNormal"><br>Sincerely,</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;&nbsp;</p><p><img src="https://mytest.siprep.org/img/ken_signature.png" width="181" height="80"></p><p><br>Ken Stupi<br>Vice President of Finance &amp; Administration</p>`;

            $letter->save();
        }

        $letterD = NotificationLetter::where('title', 'FA Letter D')->exists();

        if(!$letterD)
        {
            $letter = new NotificationLetter;
            $letter->title = 'FA Letter D';
            $letter->content = `<p class="MsoNormal">@{app.notification_date}<br><br>@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parents_name_salutation}:<br><br>My sincere congratulations to @{student.first_name} upon acceptance to the St. Ignatius College Preparatory Class of @{app.class_year}!<br><br>I am happy to inform you that @{student.first_name} has been awarded a Financial Assistance Scholarship of $@{application_status.annual_financial_aid_amount} for the @{app.academic_year}} academic year. &nbsp;Provided your financial circumstances do not change significantly, your total anticipated amount of tuition assistance over 4 years would be $@{application_status.total_financial_aid_amount}. &nbsp;Your registration fee for Freshman year will be $@{application_status.deposit_amount}. &nbsp;Please note that we will still verify your 2023 tax returns and you are required to re-apply for financial assistance through TADS every year your student is enrolled at Sl.<br><br>We have made every effort to fairly evaluate your family's demonstrated need.&nbsp; We cannot support any appeals for additional aid unless there have been significant changes in your financial circumstances that occurred after your application for aid was filed. &nbsp;If this is the case, please detail and document these changes by noon on Monday, March 20. &nbsp;All required documents, including your 2023 taxes, must be on file in your TADS application to be considered for an appeal.<br><br>Click <a href="https://docs.google.com/forms/d/e/1FAIpQLSf_KibnGVPgGl40zRTlWbWapgloxWmHGaCq8Wzcsj6M5rcZsg/viewform?pli=1" target="_blank" as_button="false" button_theme=""><span style="color: blue">here</span></a> to submit an appeal.<br><br>We look forward to partnering with you as we provide an exceptional Jesuit education in the next four years.<br><br></p><p class="MsoNormal"><br>Sincerely,</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p><img src="https://mytest.siprep.org/img/ken_signature.png" width="181" height="80"></p><p><br>Ken Stupi<br>Vice President of Finance &amp; Administration</p>`;

            $letter->save();
        }

        $letterE = NotificationLetter::where('title', 'FA Letter E')->exists();

        if(!$letterE)
        {
            $letter = new NotificationLetter;
            $letter->title = 'FA Letter E';
            $letter->content = `<p class="MsoNormal"><span>@{app.notification_date}<br><br>@{parents_name}<br>@{address.address}<br></span>@{address.city}, @{address.state} @{address.zip_code}<span><br><br>Dear @{parents_name_salutation}:<br><br>My sincere congratulations to @{student.first_name} upon acceptance to the St. Ignatius College Preparatory Class of @{app.class_year}!<br><br>I am writing to convey the decision of the Financial Assistance Committee. &nbsp;We regret to inform you that we are unable to provide financial assistance for the @{app.academic_year}} school year.<br><br>Our financial assistance funds are limited, and we have made every effort to evaluate your family's demonstrated need. &nbsp;We cannot support any appeals unless there have been significant changes in your financial circumstances that occurred after your application for aid was filed. &nbsp;Examples of significant changes include:</span></p><p class="MsoNormal"><span>&nbsp;</span></p><ul><li><p><span>Loss of income (waves, benefits, etc.) due to unemployment</span></p></li><li><p><span>New major medical issue or family death</span></p></li></ul><p class="MsoNormal"><span><br>If you are moving forward with an appeal, please detail and document these changes by noon on Monday, March 20. &nbsp;All required documents, including your 2023 taxes, must be on file in your TADS application to be considered for an appeal. &nbsp;Click </span><a href="https://docs.google.com/forms/d/e/1FAIpQLSf_KibnGVPgGl40zRTlWbWapgloxWmHGaCq8Wzcsj6M5rcZsg/viewform?pli=1" target="_blank" as_button="false" button_theme=""><span style="color: blue">here</span></a> to submit an appeal.<br><br>It is our intention to make a St. Ignatius education possible for all families and you are most welcome to apply for financial assistance in future years. &nbsp;Information about next year's assistance process will be available on the SI website in October 2024.<br><br>Once again, the Financial Assistance Committee regrets that we were not able to meet your request. &nbsp;We look forward to partnering with you as we provide an exceptional Jesuit education in these next four years.</p><p class="MsoNormal"><span>&nbsp;</span></p><p class="MsoNormal"><span><br>Sincerely,</span></p><p class="MsoNormal"><span>&nbsp;</span></p><p class="MsoNormal"><span>&nbsp;</span></p><p><img src="https://mytest.siprep.org/img/ken_signature.png" width="181" height="80"><br>Ken Stupi<br>Vice President of Finance &amp; Administration</p>`;

            $letter->save();
        }
        
    }

    public function createAcceptedWithHonors()
    {
        $the_letter = NotificationLetter::where('reference', 'Accepted with Honors')->exists();

        if(!$the_letter)
        {
            $letter = new NotificationLetter;
            $letter->reference = 'Accepted with Honors';
            $letter->title = 'Accepted with Honors';
            $letter->content = `<p>@{app.notification_date}<br><br>@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parents_name_salutation}:<br><br><strong>Congratulations!</strong> &nbsp;@{student.first_name} @{student.last_name} has been <strong>Accepted with Honors</strong> to St. Ignatius College Preparatory. &nbsp;Welcome to our school community! @{student.first_name}'s <strong>Acceptance with Honors </strong>is a distinction reserved for the top 10% of applicants.&nbsp;We congratulate @{student.first_name} for the academic diligence that has made this success possible. &nbsp;The entire SI community pledges itself to your child's intellectual, spiritual, and social development over the next four years. &nbsp;We look forward to your participation and cooperation in this endeavor.</p><p class="MsoNormal"><br>Based on @{student.first_name}'s superior academic records, test scores and because of @{student.pronoun_possessive} academic achievements, @{student.pronoun_subject} has been accepted as <strong>@{application.scholarship_type} scholar</strong>.&nbsp; @{student.pronoun_subject_capital} will automatically be placed in the following Honors @{application.course_label}:</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">@{class_list}</p><p class="MsoNormal"><strong>&nbsp;</strong></p><p class="MsoNormal"><strong>To reserve a place in the Class of @{app.class_year}</strong>, please click on the <strong>Enroll at SI</strong> button below and make a deposit of <strong>$@{application_status.deposit_amount}.</strong> &nbsp;As a courtesy to students on our waitlist, we ask that those who do not intend to register at SI indicate their intention by clicking on the Decline button below. &nbsp;<strong><span style="color: red">You must accept enrollment by @{app.acceptance_deadline_date} or else your acceptance will be forfeited.</span></strong><br><br>The online registration system will be available beginning on @{app.registration_start_date}, with additional information, important dates, and course information. &nbsp;To access the online registration system, visit <a href="https://mytest.siprep.org" as_button="false" button_theme=""><span style="color: blue">https://mytest.siprep.org</span></a>, using the username and password you used to apply.&nbsp;<br><br>Tuition for the @{app.academic_year}} academic year is $@{system.payment.tuition_fee}. &nbsp;The Business Office will have information on tuition payment plans and schedules in the online registration system. &nbsp;For families who applied for financial assistance, the Business Office has posted the Financial Assistance Committee's decision below. &nbsp;The ability to enroll or decline your acceptance will appear after you acknowledge your financial assistance details.<br><br>We had over @{app.number_of_applicants} applicants apply to St. Ignatius College Preparatory for the Class of @{app.class_year}. &nbsp;The Admissions Committee was fortunate to have so many qualified applicants to select from in this highly competitive applicant pool. &nbsp;We are excited to have @{student.first_name} as a member of our talented Frosh class. &nbsp;@{student.first_name}'s acceptance is contingent upon @{student.pronoun_possessive} continued academic achievement, good citizenship, and successful completion of eighth grade at @{student.official_school}. &nbsp;It is our intention that your student is academically challenged and receives individual attention, which are hallmarks of a Jesuit education.&nbsp;We are looking forward to working closely with you and @{student.first_name} over the next four years. &nbsp;Once again, <strong>congratulations!</strong></p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">Sincerely,</p><p class="MsoNormal">&nbsp;</p><p class="MsoNormal">&nbsp;</p><p><a href="https://mytest.siprep.org/img/kristy_signature.png" as_button="false" button_theme=""><img src="https://mytest.siprep.org/img/kristy_signature.png" width="196" height="100"></a></p><p><br>Ms. Kristy Cahill Jacobson '98<br>Director of Admissions</p>`;

            $letter->save();
        }
        
    }

    
}
