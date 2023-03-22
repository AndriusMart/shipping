<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ShipmentDiscountCalculator;

class ShippingTest extends TestCase
{
    public function testDiscounts()
    {

        $Data = [
            [
                "date" => "2015-02-01",
                "size" => "S",
                "provider" => "MR",
                "price" => 2,
                "discount" => "-",
                "status" => "OK",
            ],
            [
                "date" => "2016-02-29",
                "size" => "CUSPS",
                "provider" => null,
                "price" => null,
                "discount" => "-",
                "status" => "Ignored",
            ],
            [
                "date" => "2015-02-11",
                "size" => "L",
                "provider" => "LP",
                "price" => 6.90,
                "discount" => "-",
                "status" => "OK",
            ]
        ];
        $shipmentService = new ShipmentDiscountCalculator();
        $result = $shipmentService->calculateShipmentDiscounts($Data);
        $this->assertIsArray($result);

        $this->assertEquals('2015-02-01', $result[0]['date']);
        $this->assertEquals('S', $result[0]['size']);
        $this->assertEquals('MR', $result[0]['provider']);
        $this->assertEquals('1.50 €', $result[0]['price']);
        $this->assertEquals('0.50 €', $result[0]['discount']);
        $this->assertEquals('OK', $result[0]['status']);

        $this->assertEquals('2016-02-29', $result[1]['date']);
        $this->assertEquals('CUSPS', $result[1]['size']);
        $this->assertEquals(null, $result[1]['provider']);
        $this->assertEquals(null, $result[1]['price']);
        $this->assertEquals('-', $result[1]['discount']);
        $this->assertEquals('Ignored', $result[1]['status']);

        $this->assertEquals('2015-02-11', $result[2]['date']);
        $this->assertEquals('L', $result[2]['size']);
        $this->assertEquals('LP', $result[2]['provider']);
        $this->assertEquals('6.90 €', $result[2]['price']);
        $this->assertEquals('-', $result[2]['discount']);
        $this->assertEquals('OK', $result[2]['status']);
    }
}
