<?php

namespace App\Http\Controllers;

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
        return response($searchService->searchProducts($request->input('q')));
    }
}
