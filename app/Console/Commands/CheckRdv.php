<?php

namespace App\Console\Commands;

use App\Models\RdvAvailability;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CheckRdv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-rdv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check platform API for rdv availabilities and notify an end user by email if an availability is found';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->checkRdvAvailability();
    }

    private function checkRdvAvailability(): void
    {
        $availabilityCount = $this->getApiResponse()->json('availabilityCount', 0);
        $lastAvailability = RdvAvailability::latest()->first()->availbility_count;

        try {
            if ($availabilityCount !== $lastAvailability) {
                $this->registerAvailability(
                    config('services.rdv_services.rdv_service_1.name'),
                    $availabilityCount
                );

                if ($availabilityCount > 0) {
                    $this->generateEmail($availabilityCount, config('services.rdv_services.rdv_service_1.link'));
                }
            }

            $this->registerAvailability(
                config('services.rdv_services.rdv_service_1.name'),
                $availabilityCount
            );
        } catch (\Exception $e) {
            $this->registerError(
                config('services.rdv_services.rdv_service_1.name'),
                $e->getMessage()
            );
        }
    }

    private function getApiResponse(): Response
    {
        return Http::get(config('services.rdv_services.rdv_service_1.api_endpoint'), [
            'centerId' => config('services.rdv_services.rdv_service_1.center_id'),
            'from' => $this->generateTimestamps(),
            'limit' => 200,
            'page' => 0,
            'specialityId' => config('services.rdv_services.rdv_service_1.speciality_id'),
        ]);
    }

    private function generateTimestamps(): string
    {
        return Carbon::now()->format('Y-m-d\TH:i:s.v\Z');
    }

    private function registerAvailability($seviceName, $availabilityCount): void
    {
        RdvAvailability::create([
            'rdv_service' => $seviceName,
            'availbility_count' => $availabilityCount,
        ]);
    }

    private function registerError($seviceName, $error): void
    {
        RdvAvailability::create([
            'rdv_service' => $seviceName,
            'error' => $error,
        ]);
    }

    private function generateEmail($availabilityCount, $link): void
    {
        Mail::send('emails.emailHtml', [
            'availabilityCount' => $availabilityCount,
            'link' => $link,
        ], function ($message) {
            $message->to(config('mail.mailers.username'))
                ->subject('RDV is now available!');
        });
    }
}
