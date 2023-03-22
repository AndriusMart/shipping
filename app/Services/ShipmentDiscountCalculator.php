<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ShipmentDiscountCalculator
{
    public function parseInputFile($inputFilePath)
    {
        $lines = file($inputFilePath, FILE_IGNORE_NEW_LINES);

        $parsedData = [];
        $shipmentData = config("shipment");
        foreach ($lines as $line) {
            $parts = explode(' ', $line);
            if (count($parts) < 3) {
                if (count($parts) == 1 ) {
                    $parsedData[] = [
                        'date' => $parts[0],
                        'size' => null,
                        'provider' => null,
                        'price' => null,
                        'discount' => '-',
                        'status' => 'Ignored',
                    ];
                    continue;
                }
                if (count($parts) == 2 ) {
                    $parsedData[] = [
                        'date' => $parts[0],
                        'size' => $parts[1],
                        'provider' => null,
                        'price' => null,
                        'discount' => '-',
                        'status' => 'Ignored',
                    ];
                    continue;
                }
            }
            if (!in_array($parts[1], $shipmentData['sizes']) || !in_array($parts[2], $shipmentData['providers'])) {
                $parsedData[] = [
                    'date' => $parts[0],
                    'size' => $parts[1],
                    'provider' => $parts[2],
                    'price' => null,
                    'discount' => '-',
                    'status' => 'Ignored',
                ];
                continue;
            }

            $date = $parts[0];
            $size = strtoupper($parts[1]);
            $provider = strtoupper($parts[2]);
            $price = config("shipment.prices.$provider.$size");
            $parsedData[] = [
                'date' => $date,
                'size' => $size,
                'provider' => $provider,
                'price' => $price,
                'discount' => '-',
                'status' => 'OK',
            ];
        }
        $result = $this->calculateShipmentDiscounts($parsedData);
        $this->storeToFile($result);
        return $result;
    }

    public function calculateShipmentDiscounts($parsedData)
    {
        $discount = 10;
        $lpLShipmentsThisMonth = 0;
        $PricesOfS = array_column(config('shipment.prices'), 'S');
        $lowestPrice = min($PricesOfS);
        $currentMonth = null;
        $currentYear = null;
        foreach ($parsedData as &$data) {
            if ($data['status'] == 'Ignored') {
                continue;
            }
            $month = date('m', strtotime($data['date']));
            $year =date('Y', strtotime($data['date']));
            $size = $data['size'];
            $provider = $data['provider'];
            $price = $data['price'];

            if ($currentMonth !== $month || $currentYear !==$year) {
                $discount = 10;
                $lpLShipmentsThisMonth = 0;
                $currentMonth = $month;
                $currentYear = $year;
            }

            switch ($size) {
                case 'S':
                    if ($price > $lowestPrice && $discount > $price - $lowestPrice) {
                        $discount -= $price - $lowestPrice;
                        $data['discount'] = number_format($price - $lowestPrice, 2) . ' €';
                        $price = $lowestPrice;
                        $data['price'] = $price;
                    } else if ($price > $lowestPrice && $discount < $price - $lowestPrice) {
                        $data['discount'] = number_format($discount, 2) . ' €';
                        $price = $price - $discount;
                        $discount = 0;
                    }
                    break;
                case 'L':
                    if ($provider == 'LP') {
                        $lpLShipmentsThisMonth += 1;
                        if ($lpLShipmentsThisMonth == 3 && $discount > $price) {
                            $discount -= $price;
                            $data['discount'] = number_format($price, 2) . ' €';
                            $price = 0;
                        } else if ($lpLShipmentsThisMonth == 3 && $discount < $price) {
                            $data['discount'] = number_format($discount, 2) . ' €';
                            $price = $price - $discount;
                            $discount = 0;
                        }
                    }
                    break;
            }


            $data['price'] = number_format($price, 2) . ' €';
        }
        return $parsedData;
    }
    public function storeToFile($result){
        $StringArray ='';
        foreach ($result as $key => $value) {
            $toLine = implode(' ',$value);
            $StringArray  .= $toLine."\r\n";
        }
        Storage::put('results.txt', $StringArray);
    }
}
