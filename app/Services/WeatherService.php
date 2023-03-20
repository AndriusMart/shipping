<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class WeatherService
{


        $input = collect(Storage::get('shipments.txt'))->split('/\r\n|\r|\n/')->map(function ($line) {
        $data = explode(' ', $line);
        return [
            'date' => $data[0],
            'shipment_type' => $data[1],
            'provider' => $data[2],
        ];
    })->toArray();


    public function tempTowords($num)
    {
        $ones = [
            0 => "",
            1 => "vienas",
            2 => "du",
            3 => "trys",
            4 => "keturi",
            5 => "penki",
            6 => "šeši",
            7 => "septyni",
            8 => "aštuoni",
            9 => "devyni",
            10 => "dešimt",
            11 => "vienuoika",
            12 => "dvylika",
            13 => "trylika",
            14 => "keturiolika",
            15 => "penkiolika",
            16 => "šešiolika",
            17 => "septyniolika",
            18 => "aštuoniolika",
            19 => "devyniolika"
        ];
        $tens = array(
            1 => "dešimt",
            2 => "dvidešimt",
            3 => "trisdešimt",
            4 => "keturiasdešimt",
            5 => "penkiasdešimt",
            6 => "šešiasdešimt",
            7 => "septyniasdešimt",
            8 => "aštuoniasdešimt",
            9 => "devyniasdešimt"
        );
        // if number is negative we add 'minus' to result and remove minus from number
        $text = "";
        if ($num < 0) {
            $text .= "minus ";
            $num = abs($num);
        } else { // else we just add 'plius' to result
            $text .= "plius ";
        }
        if($num == 0){
            return "nulis °C";
        }
        //formating number so we could seperate it 
        $num = number_format($num, 1);
        //seperating decimal form number
        $num_arr = explode(".", $num);
        $wholenum = [$num_arr[0]];
        $decnum = $num_arr[1];

        //adding Lithuaninan meanings to numbers 
        foreach ($wholenum as  $number) {
            if ($number < 20) { //checking if number is smaller than 20
                $text .= $ones[$number]; //attaching its meaning from array "ones"
            } elseif ($number < 100) { //checking if number is smaller than 100
                $text .= $tens[substr($number, 0, 1)]; //attaching to first number its meaning from array "tens"
                $text .= " " . $ones[substr($number, 1, 1)]; //attaching to second number its meaning from array "ones"
            }
        }
        if ($decnum > 0) { //if ther is a deciaml
            $text .= " kablelis "; //then add a text 'kablelis'
            if ($decnum < 10) { //checking if decimal is smaller than 10
                $text .= $ones[$decnum]; //attaching its meaning from array "ones"
            }
        }
        $text .= " °C"; //attaching measurment units
        return $text;
    }
}
