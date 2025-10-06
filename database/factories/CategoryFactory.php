<?php

namespace Database\Factories;

use App\Enums\AdminCategoriesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'status' => AdminCategoriesEnum::ACTIVE->value,
        ];
    }
}
