<?php

namespace Mlab\BudetControl\Domain\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'userimage'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /*
        * Get Todo of User
        *
        */
    public function todo()
    {
        return $this->hasMany('Todo');
    }
}
