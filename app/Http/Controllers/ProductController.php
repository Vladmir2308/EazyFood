<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\CrudService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request, SearchService $searchService)
    {
        $searchQuery = $request->mainInputField;

        if($searchQuery == ''){
            return Inertia::render('DishPage');
        }

        else{
            $categoriesAndProducts = $searchService->searchProductsAndCategories($searchQuery);

            return Inertia::render('ProductsPage', [
                'categoriesAndProducts' => $categoriesAndProducts,
                'user_id' => auth()->id(),
            ]);
        }
    }

    public function storePosition(Request $request, CrudService $crudService)
    {

        if ($request->input('type') === 'Category') {
            $data = $crudService->validateData(CategoryStoreRequest::class, $request);

            $crudService->createPosition(Category::class, $data);
        }
        else if($request->input('type') === 'Product') {
            $data = $crudService->validateData(ProductStoreRequest::class, $request);

            $product = $crudService->createPosition(Product::class, [
                'name' => $data['name'],
                'default_unit' => $data['default_unit'],
                'user_id' => auth()->id(),
            ]);

            if ($data['category_id'])
                $product->categories()->attach($data['category_id']);
            else if($request->input('category_name')){
                $category = $crudService->createPosition(Category::class, ['name' => $data['category_name'], 'user_id' => $data['user_id']]);
                $product->categories()->attach($category->id);
            }
        }
    }

    public function deletePosition(Request $request, CrudService $crudService)
    {
        if($request['type'] == 'Product')
            $crudService->deletePosition(Product::class, $request['id'], 'id');
        else
            $crudService->deletePosition(Category::class, $request['name'], 'name');

    }

    public function searchCategory(Request $request, SearchService $searchService, string $query)
    {
        $categories = $searchService->searchCategories($query);
        return response()->json($categories);
    }
}
