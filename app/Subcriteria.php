<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcriteria extends Model
{
  protected $fillable = [
    'value',
    'criteria_id'
  ];
}
