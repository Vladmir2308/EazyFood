<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DishType;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MealTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/default_dish_types.csv');

        $file = fopen($path, 'r');

        $header = fgetcsv($file);

        while($row = fgetcsv($file)) {
            $data = array_combine($header, $row);

            Type::create([
                'name' => trim($data['name']),
            ]);

        }

        fclose($file);
    }
}
