<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Solution;
use Illuminate\Database\Seeder;

class SolutionSeeder extends Seeder
{
    /**
     * Seed the solutions table with real data from the frontend.
     */
    public function run(): void
    {
        $solutions = $this->getSolutionsData();

        foreach ($solutions as $solutionData) {
            $benefits = $solutionData['benefits'] ?? [];
            $specs = $solutionData['specs'] ?? [];
            $recommendedProducts = $solutionData['recommendedProducts'] ?? [];

            unset($solutionData['benefits'], $solutionData['specs'], $solutionData['recommendedProducts']);

            $solution = Solution::create($solutionData);

            // Create benefits
            foreach ($benefits as $index => $benefitText) {
                $solution->benefits()->create([
                    'benefit_text' => $benefitText,
                    'order' => $index,
                ]);
            }

            // Create specs
            foreach ($specs as $index => $spec) {
                $solution->specs()->create([
                    'label' => $spec['label'],
                    'value' => $spec['value'],
                    'order' => $index,
                ]);
            }

            // Attach recommended products with pivot data
            $attachedProductIds = [];
            foreach ($recommendedProducts as $index => $recProduct) {
                // Try to find a matching product by name
                $product = Product::where('name', 'like', '%' . $recProduct['name'] . '%')->first();

                // Fallback: try to match by series
                if (! $product) {
                    $product = Product::where('series', 'like', '%' . explode(' ', $recProduct['series'])[0] . '%')->first();
                }

                // Fallback: use any product not yet attached
                if (! $product || in_array($product->id, $attachedProductIds)) {
                    $product = Product::whereNotIn('id', $attachedProductIds)->first();
                }

                if ($product && ! in_array($product->id, $attachedProductIds)) {
                    $solution->recommendedProducts()->attach($product->id, [
                        'display_name' => $recProduct['name'],
                        'series' => $recProduct['series'],
                        'pitch' => $recProduct['pitch'],
                        'brightness' => $recProduct['brightness'],
                        'order' => $index,
                    ]);
                    $attachedProductIds[] = $product->id;
                }
            }
        }
    }

    /**
     * Get all solution data for seeding.
     */
    private function getSolutionsData(): array
    {
        return [
            // Solution 1: Retail Storefronts
            [
                'slug' => 'retail',
                'title' => 'Retail Storefronts',
                'tagline' => 'Captivate Shoppers & Drive Foot Traffic',
                'description' => 'Transform your storefront into a dynamic, attention-grabbing display that draws customers in. Our LED solutions deliver vivid visuals visible in all lighting conditions—from bright daylight to evening ambiance—maximizing your window real estate without blocking natural light.',
                'category' => 'retail',
                'is_active' => true,
                'benefits' => [
                    'Increase foot traffic by up to 30% with dynamic window displays',
                    'Maintain natural light with transparent LED panels',
                    'Remote content management for multi-location rollouts',
                    'Energy-efficient operation with low heat output',
                ],
                'specs' => [
                    ['label' => 'Pixel Pitch', 'value' => '2.5 – 6mm'],
                    ['label' => 'Brightness', 'value' => '3,000 – 6,000 nits'],
                    ['label' => 'Transparency', 'value' => 'Up to 80%'],
                    ['label' => 'Viewing Distance', 'value' => '1 – 15m'],
                ],
                'recommendedProducts' => [
                    ['name' => 'Transparent LED Glass', 'series' => 'TG Series', 'pitch' => '3.9mm', 'brightness' => '5,500 nits'],
                    ['name' => 'LED Poster Display', 'series' => 'LP Series', 'pitch' => '2.5mm', 'brightness' => '3,000 nits'],
                    ['name' => 'Indoor Fine-Pitch LED', 'series' => 'ST Series', 'pitch' => '1.8mm', 'brightness' => '1,200 nits'],
                ],
            ],

            // Solution 2: Outdoor Advertising
            [
                'slug' => 'outdoor',
                'title' => 'Outdoor Advertising',
                'tagline' => 'Maximum Visibility in Direct Sunlight',
                'description' => "Dominate the outdoor advertising landscape with ultra-high-brightness LED displays engineered for 24/7 operation. Our outdoor solutions withstand extreme Canadian weather—from -40°C winters to scorching summers—while delivering crystal-clear content that commands attention from hundreds of meters away.",
                'category' => 'outdoor',
                'is_active' => true,
                'benefits' => [
                    'Ultra-high brightness up to 9,000 nits for full sunlight readability',
                    'IP65-rated cabinets for all-weather durability',
                    'Front-access maintenance reduces downtime to minutes',
                    'Power & signal redundancy for uninterrupted operation',
                ],
                'specs' => [
                    ['label' => 'Pixel Pitch', 'value' => '4 – 16mm'],
                    ['label' => 'Brightness', 'value' => '5,000 – 9,000 nits'],
                    ['label' => 'IP Rating', 'value' => 'IP65 / IP67'],
                    ['label' => 'Viewing Distance', 'value' => '10 – 500m'],
                ],
                'recommendedProducts' => [
                    ['name' => 'Outdoor Fixed LED', 'series' => 'PTF Series', 'pitch' => '6mm – 10mm', 'brightness' => '8,000 nits'],
                    ['name' => 'Outdoor High-Brightness', 'series' => 'PTF-HB Series', 'pitch' => '4mm', 'brightness' => '9,000 nits'],
                    ['name' => 'Outdoor Rental LED', 'series' => 'PTR Series', 'pitch' => '4.8mm', 'brightness' => '6,500 nits'],
                ],
            ],

            // Solution 3: Corporate & Control Rooms
            [
                'slug' => 'corporate',
                'title' => 'Corporate & Control Rooms',
                'tagline' => 'Seamless Video Walls for Mission-Critical Environments',
                'description' => 'Equip your boardroom, lobby, or control center with seamless LED video walls that deliver unmatched clarity and reliability. Our fine-pitch displays provide edge-to-edge uniformity with zero bezels, ensuring every pixel of data, presentation, or surveillance feed is visible with precision.',
                'category' => 'corporate',
                'is_active' => true,
                'benefits' => [
                    'Zero-bezel design for truly seamless video wall experiences',
                    '24/7 operation capability with extended lifespan LEDs',
                    'Low power consumption reduces long-term operational costs',
                    'HDR & wide color gamut for accurate data visualization',
                ],
                'specs' => [
                    ['label' => 'Pixel Pitch', 'value' => '0.9 – 2.5mm'],
                    ['label' => 'Brightness', 'value' => '600 – 1,500 nits'],
                    ['label' => 'Color Accuracy', 'value' => '≥95% NTSC'],
                    ['label' => 'Lifespan', 'value' => '100,000+ hours'],
                ],
                'recommendedProducts' => [
                    ['name' => 'Ultra Fine-Pitch LED', 'series' => 'TMAX Series', 'pitch' => '0.9mm – 1.5mm', 'brightness' => '800 nits'],
                    ['name' => 'Indoor Video Wall', 'series' => 'ST Series', 'pitch' => '1.8mm – 2.5mm', 'brightness' => '1,200 nits'],
                    ['name' => 'Conference LED', 'series' => 'AIO Series', 'pitch' => '1.2mm', 'brightness' => '600 nits'],
                ],
            ],

            // Solution 4: Events & Stages
            [
                'slug' => 'events',
                'title' => 'Events & Stages',
                'tagline' => 'Dynamic Backdrops That Elevate Every Performance',
                'description' => 'Create breathtaking stage designs and immersive event experiences with our rental-grade LED displays. Engineered for rapid setup and teardown, our modular panels deliver stunning visuals that transform concerts, conferences, trade shows, and broadcasts into unforgettable moments.',
                'category' => 'events',
                'is_active' => true,
                'benefits' => [
                    'Quick-lock cabinet design for setup in under 2 hours',
                    'Lightweight panels (under 8 kg/panel) for easy rigging',
                    'Wide 160° viewing angle for large audience coverage',
                    'Curved & creative configurations for unique stage designs',
                ],
                'specs' => [
                    ['label' => 'Pixel Pitch', 'value' => '2.6 – 6mm'],
                    ['label' => 'Brightness', 'value' => '3,500 – 5,500 nits'],
                    ['label' => 'Panel Weight', 'value' => '< 8 kg'],
                    ['label' => 'Refresh Rate', 'value' => '3,840 Hz'],
                ],
                'recommendedProducts' => [
                    ['name' => 'Rental Indoor LED', 'series' => 'RT Series', 'pitch' => '2.6mm – 3.9mm', 'brightness' => '4,500 nits'],
                    ['name' => 'Outdoor Stage LED', 'series' => 'PTR Series', 'pitch' => '4.8mm', 'brightness' => '5,500 nits'],
                    ['name' => 'Creative Curved LED', 'series' => 'FLEX Series', 'pitch' => '3.9mm', 'brightness' => '3,500 nits'],
                ],
            ],

            // Solution 5: Architectural Facades
            [
                'slug' => 'architecture',
                'title' => 'Architectural Facades',
                'tagline' => 'Transform Buildings Into Dynamic Media Canvases',
                'description' => "Turn any building façade into a landmark with our architectural LED solutions. Whether integrated into glass curtain walls or mounted on structural surfaces, our displays blend seamlessly with modern architecture while delivering high-impact media content that redefines urban landscapes.",
                'category' => 'architecture',
                'is_active' => true,
                'benefits' => [
                    'Up to 85% transparency preserves building aesthetics & daylight',
                    'Custom sizes and shapes for any architectural vision',
                    'Wind-resistant design rated for high-rise installations',
                    'Anti-corrosion treatment for coastal and harsh environments',
                ],
                'specs' => [
                    ['label' => 'Pixel Pitch', 'value' => '7.8 – 31mm'],
                    ['label' => 'Brightness', 'value' => '5,000 – 7,500 nits'],
                    ['label' => 'Transparency', 'value' => '60 – 85%'],
                    ['label' => 'Wind Load', 'value' => 'Up to 120 km/h'],
                ],
                'recommendedProducts' => [
                    ['name' => 'Mesh LED Screen', 'series' => 'MicroMesh Series', 'pitch' => '15.6mm – 31mm', 'brightness' => '7,500 nits'],
                    ['name' => 'Glass LED Display', 'series' => 'TG Series', 'pitch' => '7.8mm – 10mm', 'brightness' => '5,500 nits'],
                    ['name' => 'Curtain Wall LED', 'series' => 'CW Series', 'pitch' => '10mm', 'brightness' => '6,000 nits'],
                ],
            ],
        ];
    }
}
