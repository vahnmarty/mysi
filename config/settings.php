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

    'max_devices' => env("MAX_DEVICES", 1),

    'registration' =>[
    ]

];