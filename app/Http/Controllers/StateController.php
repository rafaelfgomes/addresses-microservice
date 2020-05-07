<?php

namespace App\Http\Controllers;

use App\Http\Resources\State as StateResources;
use App\State;
use App\Traits\ApiResponser;

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

}
