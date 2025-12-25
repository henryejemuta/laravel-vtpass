<?php

namespace HenryEjemuta\LaravelVTPass\Tests\Unit;

use HenryEjemuta\LaravelVTPass\Classes\VTPassResponse;
use HenryEjemuta\LaravelVTPass\Facades\VTPass;
use HenryEjemuta\LaravelVTPass\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class VTPassTest extends TestCase
{
    /** @test */
    public function it_can_get_service_categories()
    {
        Http::fake([
            '*' => Http::response([
                'response_description' => '000',
                'content' => [
                    ['identifier' => 'airtime', 'name' => 'Airtime Recharge'],
                    ['identifier' => 'data', 'name' => 'Data Bundle'],
                ],
            ], 200),
        ]);

        $response = VTPass::getServicesCategories();

        $this->assertInstanceOf(VTPassResponse::class, $response);
        $this->assertTrue($response->successful());
        $this->assertCount(2, $response->getBody());
    }
}
