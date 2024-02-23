<?php

return [

    'si' => [
        'admissions' => [
            'email' => 'admissions@siprep.org'
        ]
    ],

    'payment' => [
        'application_fee' => env("PAYMENT_APPLICATION_FEE"),
        'tuition_fee' => env('PAYMENT_TUITION_FEE'),
    ],

    'academic_year' => env('ACADEMIC_YEAR'),
    'class_year' => env('CLASS_YEAR'),
    'number_of_applicants' => env('NUMBER_OF_APPLICANTS'),

    'registration' =>[
        'challenge_test_date' => env('CHALLENGE_TEST_DATE')
    ]

];