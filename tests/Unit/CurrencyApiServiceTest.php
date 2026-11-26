<?php

namespace Tests\Unit;

use App\Services\CurrencyApiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CurrencyApiServiceTest extends TestCase
{
    public function test_service_returns_fallback_rates_when_api_fails(): void
    {
        Http::fake([
            'api.frankfurter.app/*' => Http::response([], 500),
        ]);

        $service = new CurrencyApiService();
        $result = $service->convertFromEur(100);

        $this->assertEquals(100.0, $result['EUR']);
        $this->assertArrayHasKey('USD', $result);
        $this->assertArrayHasKey('GBP', $result);
    }
}