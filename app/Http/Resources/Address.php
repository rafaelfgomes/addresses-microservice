<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Address extends Resource 
{

    /**
     * Transform one resource into an array
     * 
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [

            'id' => $this->_id,
            'zipcode' => $this->number,
            'address' => $this->address->name,
            'complement' => $this->complement,
            'neighborhood' => $this->address->neighborhood->name,
            'city' => $this->address->neighborhood->city->name,
            'state' => $this->address->neighborhood->city->state->name

        ];
    }

}