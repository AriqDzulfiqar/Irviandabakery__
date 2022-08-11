<?php

namespace App\Http\Controllers\API;

use App\City;
use App\Http\Controllers\Controller;
use App\Province;
use App\Product;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function provinces(Request $request)
    {
        return Province::whereIn('province_id',['8','33'])->get();
    }

    public function regencies(Request $request, $provinces_id)
    {
        return City::where('province_id', $provinces_id)->get();
    }

    public function stock($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }
}

