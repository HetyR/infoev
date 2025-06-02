<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->realText(50);
        $summary = $this->faker->paragraph(1);
        $paragraphs = $this->faker->paragraphs(rand(3, 8));
        $content = '';
        foreach ($paragraphs as $para) {
            $content .= "<p>{$para}</p>";
        }

        return [
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
            'published' => true
        ];
    }
}
