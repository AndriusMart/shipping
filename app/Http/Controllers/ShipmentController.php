<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ShipmentDiscountCalculator;

class ShipmentController extends Controller
{

    protected $myService;

    public function __construct(ShipmentDiscountCalculator $myService)
    {
        $this->myService = $myService;
    }
    public function index(Request $request)
    {

        $result = $this->myService->parseInputFile(Storage::path('input.txt'));
        return view('home', [
            'results' => $result,
        ]);
    }
}
