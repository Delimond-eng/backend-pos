<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $categories = [
            "Produits frais",
            "Épicerie",
            "Boissons",
            "Produits bio",
            "Produits surgelés",
            "Pâtisserie",
            "Pour animaux"
        ];
        \App\Models\User::updateOrCreate(["name"=>"admin"], [
            "name"=>"admin",
            "password"=>bcrypt("12345"),
            "role"=>"admin",
        ]);
        \App\Models\User::updateOrCreate(["name"=>"Johanna"], [
            "name"=>"Johanna",
            "password"=>bcrypt("12345"),
            "role"=>"vendor",
        ]);
        foreach($categories  as $cat){
            ProductCategory::updateOrCreate( ["name"=>$cat],["name"=>$cat]);
        }

        Product::updateOrCreate(["name"=> "Gateaux new lis"], [
            "name"=>"Gateaux new lis",
            "category_id"=>2,
            "unit_price"=>1500.00,
        ]);
    }
}
