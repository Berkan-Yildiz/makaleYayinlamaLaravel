<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name;
        return [
            'category_id' => rand(1, 10),
            'user_id' => rand(1, 10),
            'title' => fake()->text,
            'slug' => Str::slug($name),
            'body' => fake()->paragraph,
            'status' => fake()->boolean,
            'tags' => Str::slug(fake()->address,"'"),
            'seo_description' => fake()->text,
            'seo_keywords' => Str::slug(fake()->address,"'"),
            'view_count' => rand(0, 100),
            'like_count' => rand(0, 100),
            'read_time' => rand(0, 2000),
            'publish_date' => fake()->dateTime,


        ];
    }
}
