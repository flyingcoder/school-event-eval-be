<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
  protected $fillable = [
    'value',
    'eval_id'
  ];
}
