<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
  protected $table = 'applications';

  protected $fillable = [
    'name',
    'url',
    'url_login',
    'url_logout',
    'token'
  ];
}
