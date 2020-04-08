<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Search\Searchable;

class User extends Model
{
    use SoftDeletes;
    use Searchable;
}
