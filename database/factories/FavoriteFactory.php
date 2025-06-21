<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'product_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}
