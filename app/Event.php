<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  protected $fillable = [
    'name',
    'venue',
    'date',
    'college',
    'department',
    'course',
    'evaluation',
    'user_id',
    'semester',
    'school_year'
  ];

  protected $hidden = [
    'user_id', 'created_at', 'updated_at'
  ];
}
