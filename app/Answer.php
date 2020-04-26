<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
  protected $fillable = [
    'event_id',
    'user_id',
    'answers'
  ];
}
