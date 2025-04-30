<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['lost', 'found']),
            'status' => 'active',
            'visible' => true,
            'location' => $this->faker->city(),
            'image' => null,
            'user_id' => User::factory(),
            'category_id' => 1,
            'found_date' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'lost_date' => $this->faker->dateTimeBetween('-2 week', '-1 week'),
        ];
    }
}
