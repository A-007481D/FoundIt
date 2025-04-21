<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices such as phones, laptops, tablets, etc.'
            ],
            [
                'name' => 'Clothing',
                'description' => 'Clothing items such as jackets, hats, scarves, etc.'
            ],
            [
                'name' => 'Accessories',
                'description' => 'Accessories such as watches, jewelry, glasses, etc.'
            ],
            [
                'name' => 'Documents',
                'description' => 'Important documents such as IDs, passports, licenses, etc.'
            ],
            [
                'name' => 'Keys',
                'description' => 'Keys for homes, vehicles, lockers, etc.'
            ],
            [
                'name' => 'Bags',
                'description' => 'Bags, backpacks, purses, wallets, etc.'
            ],
            [
                'name' => 'Pets',
                'description' => 'Lost or found pets'
            ],
            [
                'name' => 'Other',
                'description' => 'Other items that do not fit into the above categories'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
