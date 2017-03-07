<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
  public static function daysdiff($date) { 
    return (\Carbon\Carbon::parse($date)->diff(\Carbon\Carbon::now())->days < 1) ? 'today' 
    : \Carbon\Carbon::parse($date)->startOfDay()->diffForHumans(\Carbon\Carbon::now()->startOfDay());
  }
}
