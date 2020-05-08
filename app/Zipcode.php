<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Zipcode extends Eloquent
{

    protected $collection = 'zipcodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'address_id'
    ];

    public function address()
    {
        return $this->belongsTo('App\Address', 'address_id', '_id');
    }

}
