<?php

namespace App\Http\Controllers;

use App\City;
use App\Traits\ApiResponser;
use App\Http\Resources\City as CityResources;
use App\Http\Resources\Neighborhood as NeighborhoodResources;
use App\Neighborhood;

class CityController extends Controller
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
    public function getNeighborhoods($cityId)
    {
        $neighborhoodsMongo = Neighborhood::where('city_id', $cityId)->get();
        $neighborhoods = NeighborhoodResources::collection($neighborhoodsMongo);
        return $this->successResponse($neighborhoods);

    }

}
