<?php

namespace Hanoivip\Shenqu\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'wtus';
    
    protected $table = 'user';
    
    public $timestamps = false;
}
