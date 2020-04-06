<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Search\Searchable;

class User extends Model
{
    use SoftDeletes;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'birthday', 'phone_number', 'email', 'password',
    ];
}
