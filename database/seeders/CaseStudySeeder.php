<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CaseStudySeeder extends Seeder
{
    /**
     * Seed the case_studies table with real data from the frontend.
     */
    public function run(): void
    {
        $caseStudies = $this->getCaseStudiesData();

        foreach ($caseStudies as $caseStudyData) {
            $metrics = $caseStudyData['metrics'] ?? [];
            $specs = $caseStudyData['specs'] ?? [];
            $products = $caseStudyData['products'] ?? [];

            unset($caseStudyData['metrics'], $caseStudyData['specs'], $caseStudyData['products']);

            $caseStudy = CaseStudy::create($caseStudyData);

            // Create metrics
            foreach ($metrics as $index => $metric) {
                $caseStudy->metrics()->create([
                    'label' => $metric['label'],
                    'value' => $metric['value'],
                    'icon' => $metric['icon'],
                    'order' => $index,
                ]);
            }

            // Create specs
            foreach ($specs as $index => $spec) {
                $caseStudy->specs()->create([
                    'label' => $spec['label'],
                    'value' => $spec['value'],
                    'order' => $index,
                ]);
            }

            // Attach products with pivot data
            $attachedProductIds = [];
            foreach ($products as $productName) {
                // Try to find a matching product
                $product = Product::where('name', 'like', '%' . explode('–', $productName)[0] . '%')
                    ->whereNotIn('id', $attachedProductIds)
                    ->first();

                // Fallback: use any product not yet attached
                if (! $product) {
                    $product = Product::whereNotIn('id', $attachedProductIds)->first();
                }

                if ($product && ! in_array($product->id, $attachedProductIds)) {
                    $caseStudy->products()->attach($product->id, [
                        'product_name' => $productName,
                    ]);
                    $attachedProductIds[] = $product->id;
                }
            }
        }
    }

    /**
     * Get all case study data for seeding.
     */
    private function getCaseStudiesData(): array
    {
        return [
            // Case Study 1: Luxury Mall Toronto
            [
                'slug' => 'luxury-mall-toronto',
                'title' => 'Luxury Mall Digital Façade',
                'client' => 'Yorkdale Shopping Centre',
                'industry' => 'retail',
                'location' => 'Toronto, ON',
                'date' => '2025',
                'description' => "Transformed the main entrance of one of Canada's premier shopping destinations with a 42m² curved transparent LED display, creating an immersive visual gateway visible from over 200m away.",
                'challenge' => "The client needed a display that wouldn't obstruct natural light while delivering vivid visuals for premium brand advertising in a high-traffic mall entrance.",
                'solution' => "Deployed Maxvision's MicroMesh transparent LED panels with 75% light transmission, custom-curved to follow the architectural glass façade and integrated with a CMS for real-time ad scheduling.",
                'is_featured' => true,
                'is_active' => true,
                'metrics' => [
                    ['label' => 'Foot Traffic Increase', 'value' => '+34%', 'icon' => 'TrendingUp'],
                    ['label' => 'Ad Revenue Uplift', 'value' => '+$1.2M/yr', 'icon' => 'DollarSign'],
                    ['label' => 'Brand Recall Rate', 'value' => '89%', 'icon' => 'Eye'],
                    ['label' => 'Energy Savings', 'value' => '40%', 'icon' => 'BarChart3'],
                ],
                'specs' => [
                    ['label' => 'Display Size', 'value' => '42 m²'],
                    ['label' => 'Pixel Pitch', 'value' => 'P3.9'],
                    ['label' => 'Brightness', 'value' => '5,500 nits'],
                    ['label' => 'Transparency', 'value' => '75%'],
                ],
                'products' => ['Transparent LED – MicroMesh Series', 'Maxvision CMS Controller'],
            ],

            // Case Study 2: Highway Billboard Vancouver
            [
                'slug' => 'highway-billboard-vancouver',
                'title' => 'Highway Digital Billboard Network',
                'client' => 'Pacific Outdoor Media',
                'industry' => 'outdoor',
                'location' => 'Vancouver, BC',
                'date' => '2024',
                'description' => "Delivered a network of 6 high-brightness outdoor LED billboards along major highway corridors, achieving maximum visibility in all weather conditions including Vancouver's frequent rain and fog.",
                'challenge' => "Extreme weather resistance was critical — displays needed to perform flawlessly in heavy rain, coastal fog, and direct sunlight while maintaining 24/7 uptime for premium advertisers.",
                'solution' => 'Installed Maxvision PTF-Series outdoor cabinets with IP67 rating, anti-corrosion treatment, and dual-redundant power/signal systems. Each unit delivers 9,000 nits for daylight readability.',
                'is_featured' => false,
                'is_active' => true,
                'metrics' => [
                    ['label' => 'Daily Impressions', 'value' => '2.4M', 'icon' => 'Eye'],
                    ['label' => 'Uptime Achieved', 'value' => '99.97%', 'icon' => 'BarChart3'],
                    ['label' => 'Revenue per Board', 'value' => '+62%', 'icon' => 'DollarSign'],
                    ['label' => 'Advertiser Retention', 'value' => '95%', 'icon' => 'TrendingUp'],
                ],
                'specs' => [
                    ['label' => 'Total Display Area', 'value' => '288 m²'],
                    ['label' => 'Pixel Pitch', 'value' => 'P8'],
                    ['label' => 'Brightness', 'value' => '9,000 nits'],
                    ['label' => 'IP Rating', 'value' => 'IP67'],
                ],
                'products' => ['Outdoor LED – PTF Series', 'Redundant Power Module'],
            ],

            // Case Study 3: Command Center Ottawa
            [
                'slug' => 'command-center-ottawa',
                'title' => 'National Operations Command Center',
                'client' => 'Federal Government Agency',
                'industry' => 'corporate',
                'location' => 'Ottawa, ON',
                'date' => '2025',
                'description' => 'Designed and installed a 180° curved LED video wall for a mission-critical national operations center, enabling real-time data visualization across multiple feeds with zero latency.',
                'challenge' => 'The operations center required seamless, bezel-free visuals across a 22m wide curved wall with 24/7 operation, pixel-perfect clarity for data dashboards, and strict security compliance.',
                'solution' => 'Deployed Maxvision TMAX fine-pitch LED panels (P1.2) in a custom steel subframe, with hot-swappable modules for zero-downtime maintenance and a redundant controller architecture.',
                'is_featured' => false,
                'is_active' => true,
                'metrics' => [
                    ['label' => 'Response Time Improvement', 'value' => '-28%', 'icon' => 'TrendingUp'],
                    ['label' => 'Operational Uptime', 'value' => '99.99%', 'icon' => 'BarChart3'],
                    ['label' => 'Maintenance Downtime', 'value' => '-80%', 'icon' => 'DollarSign'],
                    ['label' => 'Operator Satisfaction', 'value' => '96%', 'icon' => 'Eye'],
                ],
                'specs' => [
                    ['label' => 'Display Size', 'value' => '22m × 3.5m'],
                    ['label' => 'Pixel Pitch', 'value' => 'P1.2'],
                    ['label' => 'Brightness', 'value' => '800 nits'],
                    ['label' => 'Curvature', 'value' => '180°'],
                ],
                'products' => ['Indoor LED – TMAX Series', 'Hot-Swap Controller', 'Custom Steel Subframe'],
            ],

            // Case Study 4: Concert Stage Montreal
            [
                'slug' => 'concert-stage-montreal',
                'title' => 'International Music Festival Stage',
                'client' => 'Osheaga Festival',
                'industry' => 'events',
                'location' => 'Montreal, QC',
                'date' => '2024',
                'description' => "Provided the main stage LED installation for one of North America's largest music festivals, featuring a 160m² modular setup with real-time visual effects synced to live performances.",
                'challenge' => 'Rapid 48-hour install/teardown cycle, outdoor weather exposure, and the need for ultra-high refresh rates to support live broadcast cameras without banding or flicker.',
                'solution' => 'Used Maxvision ST-Series rental panels with quick-lock assembly, 3,840Hz refresh rate for broadcast-grade performance, and IP54-rated outdoor cabinets for weather resilience.',
                'is_featured' => false,
                'is_active' => true,
                'metrics' => [
                    ['label' => 'Audience Reach', 'value' => '135K', 'icon' => 'Eye'],
                    ['label' => 'Setup Time', 'value' => '18 hrs', 'icon' => 'BarChart3'],
                    ['label' => 'Broadcast Quality', 'value' => '4K HDR', 'icon' => 'TrendingUp'],
                    ['label' => 'Sponsor Satisfaction', 'value' => '98%', 'icon' => 'DollarSign'],
                ],
                'specs' => [
                    ['label' => 'Display Size', 'value' => '160 m²'],
                    ['label' => 'Pixel Pitch', 'value' => 'P2.6'],
                    ['label' => 'Refresh Rate', 'value' => '3,840 Hz'],
                    ['label' => 'IP Rating', 'value' => 'IP54'],
                ],
                'products' => ['Indoor LED – ST Rental Series', 'Quick-Lock Rigging System'],
            ],

            // Case Study 5: Glass Tower Calgary
            [
                'slug' => 'glass-tower-calgary',
                'title' => 'Glass Tower LED Integration',
                'client' => 'Brookfield Place Calgary',
                'industry' => 'architecture',
                'location' => 'Calgary, AB',
                'date' => '2025',
                'description' => "Integrated transparent LED panels into a 30-story glass office tower's atrium, creating a stunning media canvas that transforms the building's identity while preserving its architectural transparency.",
                'challenge' => "The LED installation needed to integrate seamlessly into structural glass without affecting the building's thermal performance, natural lighting, or architectural aesthetics.",
                'solution' => 'Custom-engineered Maxvision LED Glass panels bonded directly to IGU units, achieving 82% transparency with automated brightness adjustment based on ambient light sensors.',
                'is_featured' => false,
                'is_active' => true,
                'metrics' => [
                    ['label' => 'Property Value Impact', 'value' => '+$18M', 'icon' => 'DollarSign'],
                    ['label' => 'Tenant Attraction Rate', 'value' => '+45%', 'icon' => 'TrendingUp'],
                    ['label' => 'Energy Neutral', 'value' => 'Yes', 'icon' => 'BarChart3'],
                    ['label' => 'Design Awards', 'value' => '3', 'icon' => 'Eye'],
                ],
                'specs' => [
                    ['label' => 'Display Size', 'value' => '520 m²'],
                    ['label' => 'Pixel Pitch', 'value' => 'P7.8'],
                    ['label' => 'Transparency', 'value' => '82%'],
                    ['label' => 'Floors Covered', 'value' => '12'],
                ],
                'products' => ['Transparent LED – LED Glass Series', 'Ambient Light Sensor Module'],
            ],
        ];
    }
}
