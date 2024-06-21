<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Website;

class WebsiteFactory extends Factory
{
    protected $model = Website::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'url' => $this->faker->url,
        ];
    }
}
