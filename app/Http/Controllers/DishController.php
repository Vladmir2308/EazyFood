<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Product;
use App\Models\Type;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DishController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return Inertia::render('DishPage', [
            'types' => $types,
            'user_id' => auth()->id()
        ]);
    }

    public function searchProduct(Request $request, SearchService $searchService)
    {
        return response($searchService->searchProductsWithCategory($request->input('q')));
    }

    public function store(Request $request)
    {
        $dishData = $request->all(['name', 'user_id']);
        $dishType = $request->input('type_id');
        $products = $request->input('products');

        $existingDish = Dish::where('name', $dishData['name'])
            ->where('user_id', $dishData['user_id'])
            ->first();

        if ($existingDish) {
            return back()->withErrors([
                'message' => 'Блюдо с таким названием уже существует для этого пользователя.',
            ]);
        }

        $dish = Dish::create($dishData);

        $dish->types()->attach($dishType);

        foreach ($products as $product) {
            if($product['category_id'] && $product['product_id'])
                $dish->products()->attach($product['product_id'], ['amount' => $product['amount'], 'unit' => $product['unit']]);
            else if(!$product['category_id'] && $product['product_id']){
                $category = Category::create([$product['categoryName'], auth()->id()]);
                $category->products()->attach($product['product_id']);
            }
            else{
                $product = Product::create([
                    'name' => $product['name'],
                    'default_unit' => $product['unit'],
                    'user_id' => auth()->id()
                ]);

                $category = Category::create([$product['categoryName'], auth()->id()]);
            }
        }
    }
}
