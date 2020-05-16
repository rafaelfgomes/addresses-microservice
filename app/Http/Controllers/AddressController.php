<?php

namespace App\Http\Controllers;

use App\Zipcode;
use App\Traits\ApiResponser;
use App\Http\Resources\Zipcode as ZipcodeResources;

class AddressController extends Controller
{

    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get neighborhoods from a city.
     *
     * @param  string $cityId
     * @return Illuminate\Http\JsonResponse
     *
     */
    public function getZipcodes($addressId)
    {
        $zipcodesMongo = Zipcode::where('address_id', $addressId)->get();
        $zipcodes = ZipcodeResources::collection($zipcodesMongo);
        return $this->successResponse($zipcodes);
    }

}
