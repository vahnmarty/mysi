<x-mail::message>

<style>
    table {
        border-collapse: collapse;
    }

    th, td {
        border: none;
        padding: 6px 12px;
    }
</style>


# New Application has been submitted

| Student  | Date Submitted | Account |
|:--------------------------------------|:---------------------------------------------------|:------------------------------|
|  {{ $app->student->getFullName() }}   |   {{ $app->appStatus->application_submit_date }}   |   {{ $app->account->name }}   |

<!-- <x-mail::button :url="''">
Button Text
</x-mail::button> -->

</x-mail::message>
