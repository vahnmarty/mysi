<?php

if (! function_exists('accountId')) {
    function accountId() {
        return auth()->user()->account_id;
    }
}