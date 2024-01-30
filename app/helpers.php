<?php

if (! function_exists('inFailedPayments')) {
    function inFailedPayments($accountId){
       return \App\Models\UnsettledApplication::where('account_id', $accountId)->first();
    }
}

if (! function_exists('clean_string')) {
    function clean_string($string){
        // Remove special characters and replace spaces with underscores
        $newString = preg_replace("/[^a-zA-Z0-9.]/", "_", $string);
        // Trim any leading or trailing underscores
        $newString = trim($newString, '_');

        return $newString;
    }
}

if (! function_exists('removeQuotes')) {
    function removeQuotes($string){
        return str_replace('"', '', $string);
    }
}

if (! function_exists('accountId')) {
    function accountId() {
        return auth()->user()->account_id;
    }
}

if (! function_exists('us_states')) {
    function us_states() {

        $array = [
            "Alabama",
            "Alaska",
            "Arizona",
            "Arkansas",
            "California",
            "Colorado",
            "Connecticut",
            "Delaware",
            "District of Columbia",
            "Florida",
            "Georgia",
            "Hawaii",
            "Idaho",
            "Illinois",
            "Indiana",
            "Iowa",
            "Kansas",
            "Kentucky",
            "Louisiana",
            "Maine",
            "Maryland",
            "Massachusetts",
            "Michigan",
            "Minnesota",
            "Mississippi",
            "Missouri",
            "Montana",
            "Nebraska",
            "Nevada",
            "New Hampshire",
            "New Jersey",
            "New Mexico",
            "New York",
            "North Carolina",
            "North Dakota",
            "Ohio",
            "Oklahoma",
            "Oregon",
            "Pennsylvania",
            "Rhode Island",
            "South Carolina",
            "South Dakota",
            "Tennessee",
            "Texas",
            "Utah",
            "Vermont",
            "Virginia",
            "Washington",
            "West Virginia",
            "Wisconsin",
            "Wyoming",
            "American Samoa",
            "Federated States of Micronesia",
            "Guam",
            "Marshall Islands",
            "Northern Mariana Islands",
            "Palau",
            "Puerto Rico",
            "U.S. Minor Outlying Islands",
            "U.S. Virgin Islands"
        ];

        return array_combine($array, $array);
        // $file = public_path('data/states.json');
        // $json = file_get_contents($file);
        // $data = json_decode($json, true);
        // $array = [];

        // foreach($data as $state)
        // {
        //     $array[$state] = $state;
        // }

        // return $array;
    }
}

if (! function_exists('settings')) {
    function settings($config) {
        return \App\Models\Setting::where('config', $config)->first()?->value;
    }
}

if (! function_exists('format_phone')) {
    function format_phone($phone = null, $pattern = '/(\d{3})(\d{3})(\d{4})/',  $replacement = '($1) $2-$3') {
        if($phone)
        return preg_replace($pattern, $replacement, $phone);
    }
}


if (! function_exists('notification_setting')) {
    function notification_setting($config) {
        return \App\Models\NotificationSetting::where('config', $config)->first();
    }
}


if (! function_exists('is_date')) {
    function is_date($input) {
        $date = \DateTime::createFromFormat('Y-m-d', $input);
        
        // Check if $input is a valid date string in the 'Y-m-d' format
        return $date && $date->format('Y-m-d') === $input;
    }
}

if (! function_exists('has_registered')) {
    function has_registered() {
        if(auth()->check()){
            $account = \Auth::user()->account;
            
            return $account->hasRegisteredStudent();
        }
    }
}
