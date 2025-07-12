<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Category extends Model
{
    use Searchable;
    protected $guarded = false;

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
        ];
    }
}
