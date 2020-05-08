<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Address extends Eloquent
{

    protected $collection = 'addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'neighborhood_id'
    ];

    public function neighborhood()
    {
        return $this->belongsTo('App\Neighborhood', 'neighborhood_id', '_id');
    }

    public function zipcode()
    {
        return $this->hasOne('App\Zipcode', 'address_id', '_id');
    }

}
