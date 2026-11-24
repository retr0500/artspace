<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyApiService
{
    private string $baseUrl = 'https://api.frankfurter.app';

    public function convertFromEur(float $amountEur): array
    {
        $rates = Cache::remember('eur_exchange_rates', 3600, function () {
            try {
                $response = Http::timeout(4)
                    ->retry(2, 150)
                    ->get("{$this->baseUrl}/latest", [
                        'from' => 'EUR',
                        'to' => 'USD,GBP',
                    ]);

                if ($response->successful()) {
                    return $response->json('rates', ['USD' => 1.08, 'GBP' => 0.85]);
                }
            } catch (\Throwable $e) {
                Log::warning('Fallback en CurrencyApiService: ' . $e->getMessage());
            }

            return ['USD' => 1.08, 'GBP' => 0.85];
        });

        return [
            'EUR' => round($amountEur, 2),
            'USD' => round($amountEur * ($rates['USD'] ?? 1.08), 2),
            'GBP' => round($amountEur * ($rates['GBP'] ?? 0.85), 2),
        ];
    }
}