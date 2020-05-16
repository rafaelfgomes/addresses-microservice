<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FullAddress extends Resource 
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

        $zipcodeFirstPart = substr($this->number, 0, 5);
        $zipcodeSecondPart = substr($this->number, 5, 3);
        $zipcodeFormatted = $zipcodeFirstPart . '-' . $zipcodeSecondPart;

        return [

            'id' => $this->_id,
            'zipcode' => $zipcodeFormatted,
            'address' => $this->address->name,
            'complement' => $this->complement,
            'neighborhood' => $this->address->neighborhood->name,
            'city' => $this->address->neighborhood->city->name,
            'state' => $this->address->neighborhood->city->state->name

        ];
    }

}