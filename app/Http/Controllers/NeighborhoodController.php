<?php

namespace App\Http\Controllers;

use App\Address;
use App\Traits\ApiResponser;
use App\Http\Resources\Address as AddressResources;
use App\Http\Resources\Zipcode as ZipcodeResources;

class NeighborhoodController
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
     * Get addresses from a neighborhood.
     *
     * @param  string $neighborhoodId
     * @return Illuminate\Http\JsonResponse
     *
     */
    public function getAddresses($neighborhoodId)
    {
        $addressesMongo = Address::where('neighborhood_id', $neighborhoodId)->get();
        $addresses = AddressResources::collection($addressesMongo);
        return $this->successResponse($addresses);
    }

    /**
     * Get addresses from a neighborhood.
     *
     * @param  string $neighborhoodId
     * @return Illuminate\Http\JsonResponse
     *
     */
    public function getZipcodes($neighborhoodId)
    {
        $addressesMongo = Address::where('neighborhood_id', $neighborhoodId)->get();
        $addresses = ZipcodeResources::collection($addressesMongo);
        return $this->successResponse($addresses);
    }

}