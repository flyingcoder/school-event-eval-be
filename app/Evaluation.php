<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
  protected $fillable = [
    'name',
    'user_id'
  ];

  protected $hidden = [
    'user_id', 'created_at', 'updated_at'
  ];
}
