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

            'type' => 'addresses',
            'id' => $this->_id,
            'attributes' => [
                'name' => $this->name
            ]

        ];
    }

}