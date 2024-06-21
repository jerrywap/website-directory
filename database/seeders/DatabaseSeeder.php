<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Website;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Category::factory(10)->create()->each(function ($category) {
            Website::factory(10)->create()->each(function ($website) use ($category) {
                $website->categories()->attach($category->id);
            });
        });
    }
}
