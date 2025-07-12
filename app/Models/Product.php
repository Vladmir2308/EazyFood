<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;
    protected $guarded = false;

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'category_names' => $this->categories->pluck('name')->toArray(),
        ];
    }
}
