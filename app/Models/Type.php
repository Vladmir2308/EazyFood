<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $guarded = false;

    public function dishes()
    {
        return $this->belongsToMany(Dish::class)->withPivot([]);
    }
}
