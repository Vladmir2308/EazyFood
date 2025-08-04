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
        $dishes = Dish::with('type')
            ->where('user_id', $currentSessionUser)
            ->orderBy('type_id')
            ->orderBy('display_number')
            ->get();

        $types = Type::all();

        $scheduleData = MealSchedule::with('dish', 'mealType')
            ->where('user_id', $currentSessionUser)
            ->get();

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
}
