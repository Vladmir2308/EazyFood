<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealSchedule extends Model
{
    protected $guarded = false;

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function mealType()
    {
        return $this->belongsTo(Type::class);
    }
}
