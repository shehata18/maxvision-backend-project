<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true) . ' Display';
        $brightnessMin = fake()->numberBetween(500, 7000);

        return [
            'name' => $name,
            'series' => fake()->randomElement(['PTF Series', 'TMAX Series', 'ST Series', 'LED Glass', 'Micro Mesh', 'Poster Series']),
            'category' => fake()->randomElement(['outdoor', 'indoor', 'transparent', 'posters']),
            'pixel_pitch' => fake()->randomFloat(1, 1.5, 10.0),
            'brightness_min' => $brightnessMin,
            'brightness_max' => $brightnessMin + fake()->numberBetween(500, 2000),
            'cabinet_size' => fake()->randomElement(['960×960×90mm', '600×337.5×65mm', '500×500×80mm']),
            'weight' => fake()->numberBetween(8, 30) . 'kg/cabinet',
            'power_consumption' => 'Max ' . fake()->numberBetween(200, 700) . 'W/m², Avg ' . fake()->numberBetween(80, 250) . 'W/m²',
            'protection_rating' => fake()->randomElement(['IP65', 'IP54', 'IP30']),
            'lifespan' => '100,000 hours',
            'operating_temp' => fake()->randomElement(['-30°C to +55°C', '0°C to +45°C', '-20°C to +50°C']),
            'environment' => fake()->randomElement(['Outdoor', 'Indoor', 'Indoor/Outdoor']),
            'price' => fake()->randomElement(['Contact for Quote', '$' . fake()->numberBetween(1000, 5000)]),
            'tagline' => fake()->sentence(),
            'description' => fake()->paragraphs(2, true),
            'slug' => Str::slug($name),
            'is_active' => true,
        ];
    }

    /**
     * Configure the model factory to create features after creating.
     */
    public function withFeatures(int $count = 4): static
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            $icons = ['Sun', 'Shield', 'Zap', 'Wrench', 'Eye', 'Cpu', 'Monitor', 'Thermometer'];
            for ($i = 0; $i < $count; $i++) {
                $product->features()->create([
                    'icon' => $icons[$i % count($icons)],
                    'title' => fake()->words(3, true),
                    'description' => fake()->sentence(),
                ]);
            }
        });
    }

    /**
     * Configure the model factory to create applications after creating.
     */
    public function withApplications(int $count = 4): static
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            $apps = ['Billboards', 'Building Facades', 'Stadiums', 'Transportation Hubs', 'Control Rooms', 'Retail Stores', 'Conference Rooms', 'Airports'];
            for ($i = 0; $i < $count; $i++) {
                $product->applications()->create([
                    'name' => $apps[$i % count($apps)],
                    'order' => $i,
                ]);
            }
        });
    }

    /**
     * Configure the model factory to create specifications after creating.
     */
    public function withSpecifications(int $count = 8): static
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            $specs = [
                ['refreshRate', '3840Hz'],
                ['viewingAngle', '160°/160°'],
                ['contrast', '5000:1'],
                ['grayScale', '16bit'],
                ['processingDepth', '14-bit'],
                ['driveMode', '1/16 Scan'],
                ['inputVoltage', 'AC 110-240V'],
                ['operatingHumidity', '10%-90%'],
            ];
            for ($i = 0; $i < min($count, count($specs)); $i++) {
                $product->specifications()->create([
                    'spec_key' => $specs[$i][0],
                    'spec_value' => $specs[$i][1],
                    'order' => $i,
                ]);
            }
        });
    }
}
