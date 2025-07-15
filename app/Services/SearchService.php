<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;

class SearchService
{
    public function searchProductsAndCategories(string $searchQuery)
    {
        $categories = Category::search($searchQuery)->get();

        if ($categories->isNotEmpty()) {
            // Загружаем продукты для найденных категорий
            $categories->load(['products' => fn($q) => $q->orderBy('name')]);

            // Группируем: название категории => продукты
            $groupedCategories = $categories->sortBy('name')->mapWithKeys(function ($category) {
                return [
                    $category->name => [
                        'user_id' => $category->user_id,
                        'products' => $category->products->sortBy('name')->values(),
                    ]
                ];
            });

            return $groupedCategories;
        }

        else{
            $products = Product::search($searchQuery)->get()->load('categories');

            // Группируем найденные продукты по их категориям
            $groupedCategories = $products->groupBy(function ($product) {
                return $product->categories->pluck('name')->first() ?? 'Без категории';
            })->map(function ($items) {
                return $items->sortBy('name')->values();
            })->sortKeys();
        }

        if($groupedCategories->isEmpty()){
            $groupedCategories = 'Ничего не найдено';
        }

        return $groupedCategories;
    }

    public function searchCategories(string $searchQuery)
    {
        $categories = Category::search($searchQuery)->get()->sortBy('name');

        return $categories->values();
    }

    public function searchProducts(string $productName)
    {
        $products = Product::search($productName)->get();

        return $products;
    }
}
