<?php

if (! function_exists('accountId')) {
    function accountId() {
        return auth()->user()->account_id;
    }
}

if (! function_exists('us_states')) {
    function us_states() {
        $file = public_path('data/states.json');
        $json = file_get_contents($file);
        $data = json_decode($json, true);
        $array = [];

        foreach($data as $state)
        {
            $array[$state] = $state;
        }

        return $array;
    }
}