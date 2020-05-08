<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class City extends Eloquent
{

    protected $collection = 'cities';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'ibge_code',
        'state_id'
    ];

    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', '_id');
    }

    public function neighborhoods()
    {
        return $this->hasMany('App\Neighborhood', 'city_id', '_id');
    }

    public function validationRules() {

        $rules = [
            'name' => 'required|string',
            'ibge_code' => 'required|string',
            'state_id' => 'required|string',
        ];

        return $rules;

    }

}
