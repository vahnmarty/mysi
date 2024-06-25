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
            "AL" => "Alabama",
            "AK" => "Alaska",
            "AZ" => "Arizona",
            "AR" => "Arkansas",
            "CA" => "California",
            "CO" => "Colorado",
            "CT" => "Connecticut",
            "DE" => "Delaware",
            "DC" => "District of Columbia",
            "FL" => "Florida",
            "GA" => "Georgia",
            "HI" => "Hawaii",
            "ID" => "Idaho",
            "IL" => "Illinois",
            "IN" => "Indiana",
            "IA" => "Iowa",
            "KS" => "Kansas",
            "KY" => "Kentucky",
            "LA" => "Louisiana",
            "ME" => "Maine",
            "MD" => "Maryland",
            "MA" => "Massachusetts",
            "MI" => "Michigan",
            "MN" => "Minnesota",
            "MS" => "Mississippi",
            "MO" => "Missouri",
            "MT" => "Montana",
            "NE" => "Nebraska",
            "NV" => "Nevada",
            "NH" => "New Hampshire",
            "NJ" => "New Jersey",
            "NM" => "New Mexico",
            "NY" => "New York",
            "NC" => "North Carolina",
            "ND" => "North Dakota",
            "OH" => "Ohio",
            "OK" => "Oklahoma",
            "OR" => "Oregon",
            "PA" => "Pennsylvania",
            "RI" => "Rhode Island",
            "SC" => "South Carolina",
            "SD" => "South Dakota",
            "TN" => "Tennessee",
            "TX" => "Texas",
            "UT" => "Utah",
            "VT" => "Vermont",
            "VA" => "Virginia",
            "WA" => "Washington",
            "WV" => "West Virginia",
            "WI" => "Wisconsin",
            "WY" => "Wyoming"
        ];

        return $array;

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
            
            return $account?->hasRegisteredStudent();
        }
    }
}

if (! function_exists('has_enrolled')) {
    function has_enrolled() {
        if(auth()->check()){
            $account = \Auth::user()->account;
            
            return $account?->hasEnrolledStudent();
        }
    }
}

if (! function_exists('env_variable')) {
    function env_variable($name) {
        return [
            'variable' => $name,
            'value' => env($name)
        ];
    }
}


if (! function_exists('app_variable')) {
    function app_variable($config, $column = 'value') {
        $variable = \App\Models\AppVariable::where('config', $config)->first();
        
        return $variable?->$column;
    }
}

if (! function_exists('my_account')) {
    function my_account() {
        if(auth()->check()){
            return \Auth::user()->account;
        }
    }
}