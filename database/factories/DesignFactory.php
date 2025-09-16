<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Design>
 */
class DesignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'name' => $this->faker->company(),
            'tag' => $this->faker->sentence(3),
            'active' => 1,
            'card_image' => $this->faker->randomElement([["demo/Design_Placeholder.jpg"],["demo/design_placeholder2.png"],["demo/design_placeholder3.png"]
            ,["demo/design_placeholder4.png"],["demo/design_placeholder5.png"],["demo/design_placeholder6.png"],["demo/design_placeholder7.png"]]),
            'category' => $this->faker->randomElement(
                ['Subwoofer', 'Full-Range', 'Two-Way', 'Three-Way','Four-Way+','Portable', 'Esoteric', 'System']),
            'price' => $this->faker->randomFloat(2, 0.01, 1000),
            'build_cost' => $this->faker->randomFloat(2, 0.01, 1000),
            'impedance' => $this->faker->randomElement(['2','4','8']),
            'power' => $this->faker->randomNumber(),
            'summary' => $this->faker->paragraph(3),
            'description' => $this->faker->paragraph(6),
            'official' => $this->faker->boolean()

        ];

    }
}
