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
    }

    public function createAccepted()
    {
        $the_letter = NotificationLetter::where('reference', NotificationStatusType::Accepted)->exists();

        if(!$the_letter)
        {
            $letter = new NotificationLetter;
            $letter->reference = NotificationStatusType::Accepted;
            $letter->title = 'Accepted';
            $letter->content ='<p>@{timeline.notification_date}<br><br>@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parents_name_salutation}:<br><br><strong>Congratulations!</strong>&nbsp; @{student.first_name} @{student.last_name} has been <strong>Accepted</strong> to St. Ignatius College Preparatory. &nbsp; Welcome to our school community!&nbsp; We congratulate @{student.first_name} for the academic diligence that has made this success possible.&nbsp; The entire SI community pledges itself to your child’s intellectual, spiritual and social development over the next four years.&nbsp; We look forward to your participation and cooperation in this endeavor.<br><br><strong>To reserve a place in the Class of @{system.class_year}</strong>, please click on the <strong>Enroll at SI</strong> button below and make a deposit of <strong>$@{application_status.deposit_amount}.</strong>&nbsp; As a courtesy to those students on our waitlist, we ask that those who do not intend to register at SI indicate their intention by clicking on the Decline button below.&nbsp; <strong>You must accept enrollment by @{timeline.acceptance_deadline_date} or the acceptance will be forfeited.</strong><br><br>@{student.first_name}’s <strong>Acceptance</strong> is based on @{student.pronoun_possessive} academic achievements and the gifts @{student.pronoun_subject} will bring to the SI community.&nbsp; The online registration system will be available beginning on @{timeline.registration_start_date}, with additional information, important dates and course information.&nbsp; To access the online registration system, visit <a href="'.$this->site_url.'" as_button="false" button_theme=""><u>'.$this->site_url.'</u></a>, using the username and password you used to apply.&nbsp; Registration must be completed by @{timeline.registration_end_date}.<br><br>Tuition for the @{system.academic_year} academic year is $@{system.payment.tuition_fee}.&nbsp; The Business Office will have information on tuition payment plans and schedules in the online registration system.&nbsp; For families who applied for financial assistance, the Business Office has posted the Financial Assistance Committee’s decision below. The ability to enroll or decline your decision will appear after you acknowledge your financial assistance details.<br><br>We had over @{system.number_of_applicants} applicants apply to St. Ignatius College Preparatory for the Class of @{system.class_year}.&nbsp; The Admissions Committee was fortunate to have so many qualified applicants to select from in this highly competitive applicant pool.&nbsp; We are excited to have @{student.first_name} as a member of our talented Freshman class.&nbsp; @{student.first_name} acceptance is contingent upon @{student.pronoun_possessive} continued academic performance, good citizenship and successful completion of eighth grade at @{student.official_school}. It is our intention to see that your student has the academic challenge and individual attention that have been a hallmark of Jesuit education.&nbsp; To this end, we are looking forward to working closely with you and @{student.first_name} over the next four years.&nbsp; Once again, <strong>Congratulations!</strong><br></p><p><br></p><p>Sincerely,</p><p><a href="'.$this->kristy_signature.'" as_button="false" button_theme=""><img src="'.$this->kristy_signature.'" width="196" height="100"></a></p><p><br>Ms. Kristy Cahill Jacobson ‘98<br>Director of Admissions</p>';
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
            $letter->title = 'Wait listed';
            $letter->content = '<p>@{timeline.notification_date}<br><br>@{parents_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parents_name_salutation}:<br><br>The Admissions Committee wants to thank you and @{student.first_name} for submitting a very thoughtful application.&nbsp;&nbsp;The Committee was very impressed with @{student.first_name}’s many fine qualities.<br><br>After careful review, the Admissions Committee has placed @{student.first_name} on the <strong>Waitlist</strong> for the Class of @{system.class_year}.&nbsp;&nbsp;The Waitlisted applicants were extremely competitive candidates in the applicant pool.&nbsp;&nbsp;We are aware that @{student.first_name} likely has other admission offers from which to choose.&nbsp;&nbsp;Being placed on the St. Ignatius College Preparatory Waitlist is evidence of the strong positive impression @{student.first_name} made throughout our review process.&nbsp;&nbsp;Waitlisted applicants were carefully selected by the Admissions Committee as students who they would like as members of the incoming Freshman class.<br><br>Click below for important Waitlist Information from the Admissions Team.&nbsp;&nbsp;Please read it carefully as it answers the most frequently asked questions and details all pertinent information available.<br><br>We had over @{system.number_of_applicants} applicants apply to St. Ignatius College Preparatory for 375 places in the Class of @{system.class_year}.&nbsp;&nbsp;There were many qualified applicants in this large and talented applicant pool that we were unable to accept.&nbsp;&nbsp;We appreciate your patience and understanding while awaiting our final decision. Thank you for your interest in St. Ignatius College Preparatory and for entrusting us with @{student.first_name}’s application this year.<br><br>Sincerely,</p><p><a href="'.$this->kristy_signature.'" as_button="false" button_theme=""><img src="'.$this->kristy_signature.'" width="196" height="100"></a></p><p><br>Ms. Kristy Cahill Jacobson ‘98<br>Director of Admissions</p>';

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
            $letter->content = '<p>December 25, 2023<br><br>@{parent.first_name} @{parent.last_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parent.salutation} @{parent.last_name}:<br><br>The Admissions Committee wants to thank you and Ramil for submitting a very thoughtful application.&nbsp;&nbsp;We were fortunate to have so many qualified applicants to select from in this highly competitive applicant pool.&nbsp;&nbsp;The Committee was very impressed with Ramil’s many fine qualities.<br><br>We had over 1,290 applicants apply to St. Ignatius College Preparatory for the Class of 2027.&nbsp;&nbsp;We regret that we will not be able to offer Ramil a place in SI’s Freshman class.&nbsp;&nbsp;There were many qualified applicants in this large and talented pool that we were unable to accept.&nbsp;&nbsp;Ramil is to be congratulated for all he has already accomplished.<br><br>We sincerely wish Ramil continued success in high school.&nbsp;&nbsp;The high school he attends will be fortunate to have him as a student.&nbsp;&nbsp;Thank you for entrusting us with Ramil’s application.&nbsp;&nbsp;We appreciate your interest in St. Ignatius College Preparatory and your understanding of how difficult our selection process was this year with so many qualified applicants.<br><br>Respectfully,</p><p><a href="'.$this->kristy_signature.'" as_button="false" button_theme=""><img src="'.$this->kristy_signature.'" width="196" height="100"></a></p><p><br>Ms. Kristy Cahill Jacobson ‘98<br>Director of Admissions</p>';

            $letter->save();
        }
        
    }

    public function createFinancialAid()
    {
        $the_letter = NotificationLetter::where('title', 'FA Letter A')->exists();

        if(!$the_letter)
        {
            $letter = new NotificationLetter;
            $letter->title = 'Financial Aid';
            $letter->content = '<p>December 25, 2023<br><br>@{parent.first_name} @{parent.last_name}<br>@{address.address}<br>@{address.city}, @{address.state} @{address.zip_code}<br><br>Dear @{parent.salutation} @{parent.last_name}:<br><br>---<strong>FINANCIAL AID CONTENTS---</strong><br><br>Sincerely,</p><p><img src="'.$this->ken_signature.'" width="181" height="80"></p><p><br>Ms. Kristy Cahill Jacobson ‘98<br>Director of Admissions</p>';

            $letter->save();
        }
        
    }

    
}
