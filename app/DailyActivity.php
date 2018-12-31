<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyActivity extends Model
{
    public function activities()
    {
        return $this->hasMany(Activity::class, 'id', 'activityid');
    }
}
