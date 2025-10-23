<?php

namespace App\Http\Controllers;

use App\Models\MealSchedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BasketController extends Controller
{
    public function index()
    {
        $productsByCategory = MealSchedule::with('dish.products.categories')
            ->where('user_id', auth()->id())
            ->get()
            ->flatMap(fn($schedule) => $schedule->dish->products)
            ->flatMap(function ($product) {
                return $product->categories->map(function ($category) use ($product) {
                    return [
                        'category' => $category->name,
                        'name'     => $product->name,
                        'id'       => $product->id,
                        'unit'     => $product->pivot->unit,
                        'amount'   => $product->pivot->amount,
                    ];
                });
            })
            ->groupBy('category')
            ->map(function ($items) {
                return collect($items)
                    ->groupBy(fn($p) => $p['name'].'_'.$p['unit']) // соль "гр" ≠ соль "мл"
                    ->map(function ($same) {
                        $first = $same->first();
                        return [
                            'id' => $first['id'],
                            'name'  => $first['name'],
                            'unit'  => $first['unit'],
                            'total' => $same->sum('amount'),
                        ];
                    })
                    ->sortBy([
                        fn ($a, $b) => strcmp($a['name'], $b['name']),   // сначала по имени
                        fn ($a, $b) => strcmp($a['unit'], $b['unit']),   // потом по единице
                    ])
                    ->values();
            })->sortKeys();

        return Inertia::render('BasketPage', [
            'basket' => $productsByCategory,
            'user' => auth()->user(),
        ]);
    }
}
