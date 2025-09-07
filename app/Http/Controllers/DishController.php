<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Product;
use App\Models\Type;
use App\Services\DishService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DishController extends Controller
{
    public function index()
    {
        $types = Type::all();
        $dishesByTypes = $types->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'color' => $type->color,
                'dishes' => Dish::with('products.categories')
                    ->where('user_id', auth()->id())
                    ->where('type_id', $type->id)
                    ->orderBy('display_number') // если ты добавил сортировку по номеру
                    ->get(),
            ];
        });

        return Inertia::render('DishPage', [
            'types' => $types,
            'dishesByTypes' => $dishesByTypes,
            'user_id' => auth()->id()
        ]);
    }

    public function searchProduct(Request $request, SearchService $searchService)
    {
        return response($searchService->searchProductsWithCategory($request->input('q')));
    }

    public function store(Request $request, DishService $dishService)
    {
        $maxNumber = $dishService->getMaxDisplayNumber($request['type_id']);

        $dishData = [
            'name' => $request['name'],
            'user_id' => $request['user_id'],
            'type_id' => $request['type_id'],
            'display_number' => $maxNumber ? $maxNumber + 1 : 1
        ];

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

        foreach ($products as $product) {
            if($product['category_id'] && $product['product_id'])
                $dish->products()->attach($product['product_id'], ['amount' => $product['amount'], 'unit' => $product['unit']]);
            else if(!$product['category_id'] && $product['product_id']){
                $category = Category::firstOrCreate([
                    'name' => $product['categoryName'],
                    'user_id' => auth()->id()
                ]);
                $category->products()->attach($product['product_id']);

                $dish->products()->attach($product['product_id'], ['amount' => $product['amount'], 'unit' => $product['unit']]);
            }
            else{
                $productCreated = Product::firstOrCreate([
                    'name' => $product['name'],
                    'default_unit' => $product['unit'],
                    'user_id' => auth()->id()
                ]);

                if($product['categoryName']){
                    $category = Category::firstOrCreate([
                        'name' => $product['categoryName'],
                        'user_id' => auth()->id()
                    ]);

                    $productCreated->categories()->attach($category->id);
                }

                $dish->products()->attach($productCreated['id'], ['amount' => $product['amount'], 'unit' => $product['unit']]);
            }
        }
    }

    public function update(Request $request, DishService $dishService)
    {
        $dish = Dish::findOrFail($request['id']);

        $maxNumber = $dishService->getMaxDisplayNumber($request['type_id']);

        $dish->update([
            'name' => $request['name'],
            'type_id' => $request['type_id'],
            'display_number' => $maxNumber ? $maxNumber + 1 : 1
        ]);

        $products = collect($request['products'])->mapWithKeys(function ($p) {
            return [
                $p['product_id'] => [
                    'amount' => $p['amount'],
                    'unit'   => $p['unit'],
                ]
            ];
        });

        $dish->products()->sync($products);
    }

    public function delete(Request $request)
    {
        $dish = Dish::findOrFail($request['id']);
        $userId = $dish->user_id;
        $typeId = $dish->type_id;

        $dish->delete();

        DishService::reindexDisplayNumbers($userId, $typeId);
    }
}
