<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/products_russian_with_units_and_categories.csv');

        $file = fopen($path, 'r');

        $header = fgetcsv($file);

        while($row = fgetcsv($file)) {
            $data = array_combine($header, $row);

            $product = Product::create([
                'name' => trim($data['name']),
                'default_unit' => trim($data['default_unit']),
            ]);

            $category = Category::firstOrCreate([
                'name' => $data['categories'],
            ]);

            $product->categories()->attach($category->id);
        }

        fclose($file);
    }
}
