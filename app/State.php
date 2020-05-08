<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class State extends Eloquent
{

    protected $collection = 'states';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'initials'
    ];

    public function cities()
    {     
        return $this->hasMany('App\City', 'state_uid', 'uid');
    }

}
