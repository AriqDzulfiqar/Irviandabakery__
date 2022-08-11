<?php

namespace App\Http\Controllers\API;

use App\City;
use App\Http\Controllers\Controller;
use App\Province;
use App\Product;
use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class LocationController extends Controller
{
    public function provinces(Request $request)
    {
        return Province::whereIn('province_id', ['8', '33'])->get();
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

    public function checkOngkir(Request $request)
    {

        $cost =  RajaOngkir::ongkosKirim([
            'origin' => 50, //Default dari batang hari jambi
            'destination' => $request->regencies_id, //Id Kota //kabupaten tujuan
            'weight' => 100, // berat barang dalam gram sample 100
            'courier' => "jne" // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
        ])->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Cost All Courir: JNE',
            'data'    => $cost
        ]);
    }
}
