<?php

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
    function format_phone($phone, $pattern = '/(\d{3})(\d{3})(\d{4})/',  $replacement = '($1) $2-$3') {
        return preg_replace($pattern, $replacement, $phone);
    }
}