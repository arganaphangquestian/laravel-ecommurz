<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::query()->delete();
        $faker = \Faker\Factory::create();

        for ($i=0; $i < 10; $i++) { 
            Product::create([
                'name' => $faker->sentence,
                'description' => $faker->paragraph,
                'image_url' => $faker->imageUrl($width = 640, $height = 480),
                'price' => $faker->numberBetween($min = 1000, $max = 100000),
                'seller_id' => User::where('name', 'Seller')->first()->id,
            ]);
        }
    }
}
