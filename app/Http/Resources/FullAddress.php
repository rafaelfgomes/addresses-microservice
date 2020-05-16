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

            'type' => 'full_address',
            'id' => $this->_id,
            'attributes' => [
                'zipcode' => $zipcodeFormatted,
                'complement' => $this->complement
            ],
            'relationships' => [
                'address' => [
                    'data' =>  [
                        'type' => 'addresses',
                        'id' => $this->address->_id,
                        'attributes' => [
                            'name' => $this->address->name
                        ]
                    ]
                ],
                'neighborhood' => [
                    'data' =>  [
                        'type' => 'neighborhoods',
                        'id' => $this->address->neighborhood->_id,
                        'attributes' => [
                            'name' => $this->address->neighborhood->name
                        ]
                    ]
                ],
                'city' => [
                    'data' =>  [
                        'type' => 'cities',
                        'id' => $this->address->neighborhood->city->_id,
                        'attributes' => [
                            'name' => $this->address->neighborhood->city->name
                        ]
                    ]
                ],
                'state' => [
                    'data' =>  [
                        'type' => 'states',
                        'id' => $this->address->neighborhood->city->state->_id,
                        'attributes' => [
                            'name' => $this->address->neighborhood->city->state->name
                        ]
                    ]
                ]
            ]

        ];
    }

}