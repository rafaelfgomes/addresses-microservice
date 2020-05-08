<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Neighborhood extends Eloquent
{

    protected $collection = 'neighborhoods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'city_id'
    ];

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id', '_id');
    }

    public function addresses()
    {
        return $this->hasMany('App\Address', 'neighborhood_id', '_id');
    }

    public function validationRules() {

        $rules = [
            'name' => 'required|string',
            'city_id' => 'required|string',
        ];

        return $rules;

    }

}
