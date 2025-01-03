<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'rdv_services' => [
        'rdv_service_1' => [
            'name' => env('RDV_SERVICE_1'),
            'api_endpoint' => env('RDV_SERVICE_1_API'),
            'center_id' => env('RDV_SERVICE_1_CENTERID'),
            'speciality_id' => env('RDV_SERVICE_1_SPECIALITYID'),
            'link' => env('RDV_SERVICE_1_LINK'),
        ],
    ],
];
