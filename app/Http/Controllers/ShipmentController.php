<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $LP = [
            'S' => 1.50,
            'M' => 4.90,
            'L'=> 6.90        
        ];
        $MR = [
            'S' => 2,
            'M' => 3,
            'L'=> 4        
        ];

        $shipments = collect(Storage::get('input.txt'));
        $shipments1 = explode('["', $shipments);
        $shipments1 = explode('"]', $shipments1[1]);
        $shipments1 = explode('\r\n', $shipments1[0]);
        $data = [];
        foreach ($shipments1 as $key => $value) {
            $ship = explode(' ', $value);
            if(!isset($ship[2])){
                array_push($ship, 'Ignore');
            }
            if($ship[2] === "LP"){
                if($ship[1] === "S"){
                    array_push($ship, $LP['S']>$MR['S']?$MR['S']:$LP['S']);
                    array_push($ship, $LP['S']>$MR['S']?$LP['S']-$MR['S']: '-');
                }
                if($ship[1] === "M"){
                    array_push($ship, $LP['M']);
                    array_push($ship, '-');
                }
                if($ship[1] === "L"){
                    array_push($ship, $LP['L']);
                    array_push($ship, '-');
                }
            }
            if($ship[2] === "MR"){
                if($ship[1] === "S"){
                    array_push($ship, $MR['S']>$LP['S']?$LP['S']:$MR['S']);
                    array_push($ship, $MR['S']>$LP['S']?$MR['S']-$LP['S']: '-');
                }
                if($ship[1] === "M"){
                    array_push($ship, $MR['M']);
                    array_push($ship, '-');
                }
                if($ship[1] === "L"){
                    array_push($ship, $MR['L']);
                    array_push($ship, '-');
                }
            }
           $ship = implode(" ",$ship);
            // dump($ship);
        $data[] = $ship;
        
        }
        // foreach ($data as $key => $value) {
        //     // dump($value[1] === "S");
        //     if($value[1] === "S"){
        //         array_push($value, 'ban');
        //     }
        //     // dump($value[1] == 'S');
        
        // }
        dump($data);

        return view('home', []);
    }
}
