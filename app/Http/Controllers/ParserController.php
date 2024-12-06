<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Http;

class ParserController extends Controller
{
    public function index()
    {

        $currentDate = new DateTime('now', new DateTimeZone('UTC'));
        $formattedDate = $currentDate->format('Y-m-d\TH:i:s.v\Z');

        $response = $response = Http::get(config('services.rdv_service_1.api_endpoint'), [
            'centerId' => 'services.rdv_service_1.center_id',
            'from' => $formattedDate,
            'limit' => 200,
            'page' => 0,
            'specialityId' => 'services.rdv_service_1.speciality_id',
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        dd($data);
        // https://www.maiia.com/api/pat-public/availability-closests?centerId=6423dd75bd4fbf790a115fc5&from=2024-12-06T08%3A43%3A00.000Z&limit=200&page=0&specialityId=5e185ddfb5346d1863161b38

        return view('welcome',
            // ['elements' => $elements]
        );
    }
}

// $result = $crawler->matches('h2.MuiTypography-root MuiTypography-body1 mui-xulfr0v');
