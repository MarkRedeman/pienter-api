<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'firstname',
        'insertion',
        'surname',
        'birthdate',
        'group',
    ];
}
