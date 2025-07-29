<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $guarded = false;

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
