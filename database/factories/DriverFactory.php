<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
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
            'card_image' => $this->faker->randomElement([["demo/Design_Placeholder.jpg"],["demo/design_placeholder2.png"],["demo/design_placeholder3.png"]
            ,["demo/design_placeholder4.png"],["demo/design_placeholder5.png"],["demo/design_placeholder6.png"],["demo/design_placeholder7.png"]]),
            'brand' => $this->faker->randomElement([
                'ScanSpeak',
                'SEAS',
                'Peerless',
                'Vifa',
                'Dayton Audio',
                'Fountek',
                'Tang Band',
                'Morel',
                'Fostex',
                'Eminence',
                'Audax',
                'AuraSound',
                'SB Acoustics',
                'Wavecor',
                'Tymphany',
                'Davis Acoustics',
                'Audio Technology',
                'HiVi',
                'Accuton',
                'CSS Audio'
            ]),
            'model' => $this->faker->company(),
            'tag' => $this->faker->sentence(4),
            'active' => 1,
            'category' => $this->faker->randomElement(
                ['Subwoofer', 'Woofer', 'Tweeter', 'Compression Driver', 'Exciter', 'Other']),
            'size' => $this->faker->randomElement([1, 2, 3, 4, 5, 5.25, 5.5, 6, 6.5, 7, 8, 10, 12, 15, 18, 21]),
            'impedance' => $this->faker->randomElement([1, 2, 4, 6, 8]),
            'power' => $this->faker->randomNumber(3, true),
            'price' => $this->faker->randomFloat(2, 0.01, 1000),
            'link' => $this->faker->url(),
            'summary' => $this->faker->paragraph(),
            'description' => $this->faker->paragraph(),
            'official' => $this->faker->boolean()
    ];
    }
}
