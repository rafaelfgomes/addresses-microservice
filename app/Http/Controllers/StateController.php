<?php

namespace App\Http\Controllers;

use App\City;
use App\State;
use App\Traits\ApiResponser;
use App\Http\Resources\City as CityResources;
use App\Http\Resources\State as StateResources;

class StateController extends Controller
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
     * Show a resource.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     *
     */
    public function show($id = null)
    {
        $states = (is_null($id)) ? StateResources::collection(State::all()) : new StateResources(State::where('_id', $id)->first());
        return $this->successResponse($states);
    }

    /**
     * Show a resource.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     *
     */
    public function getCities($stateId)
    {
        $citiesMongo = City::where('state_id', $stateId)->get();
        $cities = CityResources::collection($citiesMongo);
        return $this->successResponse($cities);
    }

}
