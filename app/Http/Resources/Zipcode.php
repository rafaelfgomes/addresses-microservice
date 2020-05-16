<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Zipcode extends Resource 
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

        $zipNumber = $this->zipcode->number;

        $zipcodeFirstPart = substr($zipNumber, 0, 5);
        $zipcodeSecondPart = substr($zipNumber, 5, 3);
        $zipcodeFormatted = $zipcodeFirstPart . '-' . $zipcodeSecondPart;

        return [

            'id' => $this->zipcode->_id,
            'complement' => $this->zipcode->complement,
            'number' => $zipcodeFormatted

        ];
    }

}