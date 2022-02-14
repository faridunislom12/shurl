<?php

namespace Database\Factories;

use App\Models\Url;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UrlFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Url::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::all()->random(),
            'short' => $this->faker->url,
            'long' => $this->faker->url,
            'is_active' => $this->faker->boolean,
        ];
    }
}


