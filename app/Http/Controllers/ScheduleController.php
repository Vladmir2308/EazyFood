<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\MealSchedule;
use App\Models\Type;
use App\Services\CrudService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index()
    {
        $currentSessionUser = auth()->user()->id;

        $dishes = Dish::where('user_id', $currentSessionUser)
            ->orderBy('type_id')
            ->orderBy('display_number')
            ->get()
            ->map(function ($item) {
                $item->color = $item->type->color ?? null;
                return $item;
            });

        $types = Type::all();

        $scheduleData = MealSchedule::with('dish')
            ->where('user_id', $currentSessionUser)
            ->get()
            ->map(function ($item) {
                $item->display_number = $item->dish->display_number ?? null;
                $item->color = $item->dish->type->color ?? null;
                $item->products = $item->dish->products;
                return $item;
            });

        return Inertia::render('SchedulePage', [
            'dishes' => $dishes,
            'types' => $types,
            'scheduleData' => $scheduleData,
        ]);
    }

    public function store(Request $request, CrudService $crudService)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();

        $crudService->createPosition(MealSchedule::class, $data);
    }

    public function delete(Request $request)
    {
        MealSchedule::where('id', $request['id'])->delete();
    }
}
