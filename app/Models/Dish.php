<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Dish extends Model
{
    use Searchable;
    protected $guarded = false;

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('amount', 'unit');
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
        ];
    }
}
