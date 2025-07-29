<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index()
    {
        $dishes = Dish::with('type')
            ->where('user_id', auth()->id())
            ->orderBy('type_id')
            ->orderBy('display_number')
            ->get();

        return Inertia::render('SchedulePage', [
            'dishes' => $dishes
        ]);
    }
}
