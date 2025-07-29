<?php

namespace App\Services;

use App\Models\Dish;

class DishService
{
    public static function reindexDisplayNumbers(int $userId, int $typeId): void
    {
        $dishes = Dish::where('user_id', $userId)
            ->where('type_id', $typeId)
            ->orderBy('display_number')
            ->get();

        foreach ($dishes as $index => $dish) {
            $newNumber = $index + 1;
            if($dish->display_number != $newNumber) {
                $dish->display_number = $newNumber;
                $dish->save();
            }
        }
    }
}
