<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the products table with real data from the frontend.
     */
    public function run(): void
    {
        $products = $this->getProductsData();

        foreach ($products as $productData) {
            $features = $productData['features'] ?? [];
            $applications = $productData['applications'] ?? [];
            $specifications = $productData['specifications'] ?? [];

            unset($productData['features'], $productData['applications'], $productData['specifications']);

            $product = Product::create($productData);

            if (!empty($features)) {
                $product->features()->createMany($features);
            }

            if (!empty($applications)) {
                foreach ($applications as $index => $app) {
                    $product->applications()->create([
                        'name' => $app,
                        'order' => $index,
                    ]);
                }
            }

            if (!empty($specifications)) {
                foreach ($specifications as $index => $spec) {
                    $product->specifications()->create([
                        'spec_key' => $spec['key'],
                        'spec_value' => $spec['value'],
                        'order' => $index,
                    ]);
                }
            }
        }
    }

    /**
     * Get all product data for seeding.
     */
    private function getProductsData(): array
    {
        return [
            // Product 1: PTF-P3 Outdoor Display
            [
                'name' => 'PTF-P3 Outdoor Display',
                'series' => 'PTF Series',
                'category' => 'outdoor',
                'pixel_pitch' => 3.0,
                'brightness_min' => 6000,
                'brightness_max' => 7500,
                'cabinet_size' => '960×960×90mm',
                'weight' => '28kg/cabinet',
                'power_consumption' => 'Max 650W/m², Avg 220W/m²',
                'protection_rating' => 'IP65 Front / IP54 Rear',
                'lifespan' => '100,000 hours',
                'operating_temp' => '-30°C to +55°C',
                'environment' => 'Outdoor',
                'price' => 'Contact for Quote',
                'tagline' => 'High-brightness outdoor LED for any weather condition',
                'description' => 'The PTF-P3 is engineered for demanding outdoor environments, delivering stunning visuals with 7000 nits of brightness. Featuring IP65-rated weatherproofing, dual power backup, and front-serviceable design, this display ensures uninterrupted performance in rain, snow, or extreme heat. Perfect for billboards, building facades, and stadium installations.',
                'slug' => 'ptf-p3-outdoor-display',
                'image' => 'products/outdoor/ptf-p3-outdoor-display.webp',
                'is_active' => true,
                'features' => [
                    ['icon' => 'Sun', 'title' => '7000 nits Brightness', 'description' => 'Crystal clear visibility even in direct sunlight, ensuring your content stands out 24/7.'],
                    ['icon' => 'Shield', 'title' => 'IP65 Weatherproof', 'description' => 'Fully sealed front panel protects against rain, dust, and extreme temperatures.'],
                    ['icon' => 'Zap', 'title' => 'Dual Power Backup', 'description' => 'Redundant power supply ensures uninterrupted operation for critical applications.'],
                    ['icon' => 'Wrench', 'title' => 'Front Maintenance', 'description' => 'Easy front-access module design allows for quick servicing without rear access.'],
                ],
                'applications' => ['Billboards', 'Building Facades', 'Stadiums', 'Transportation Hubs'],
                'specifications' => [
                    ['key' => 'refreshRate', 'value' => '3840Hz'],
                    ['key' => 'viewingAngle', 'value' => '160°/160°'],
                    ['key' => 'contrast', 'value' => '5000:1'],
                    ['key' => 'grayScale', 'value' => '16bit'],
                    ['key' => 'processingDepth', 'value' => '14-bit'],
                    ['key' => 'driveMode', 'value' => '1/8 Scan'],
                    ['key' => 'inputVoltage', 'value' => 'AC 110-240V'],
                    ['key' => 'operatingHumidity', 'value' => '10%-90%'],
                ],
            ],

            // Product 2: PTF-P5 Outdoor Display
            [
                'name' => 'PTF-P5 Outdoor Display',
                'series' => 'PTF Series',
                'category' => 'outdoor',
                'pixel_pitch' => 5.0,
                'brightness_min' => 6000,
                'brightness_max' => 8000,
                'cabinet_size' => '960×960×100mm',
                'weight' => '32kg/cabinet',
                'power_consumption' => 'Max 600W/m², Avg 200W/m²',
                'protection_rating' => 'IP65 Front / IP54 Rear',
                'lifespan' => '100,000 hours',
                'operating_temp' => '-30°C to +55°C',
                'environment' => 'Outdoor',
                'price' => 'Contact for Quote',
                'tagline' => 'Cost-effective outdoor LED for large-scale installations',
                'description' => 'The PTF-P5 delivers exceptional value for large-scale outdoor installations. With a 5mm pixel pitch optimized for longer viewing distances, this display provides brilliant visuals for highways, building wraps, and large venue applications while maintaining lower power consumption.',
                'slug' => 'ptf-p5-outdoor-display',
                'image' => 'products/outdoor/ptf-p5-outdoor-display.webp',
                'is_active' => true,
                'features' => [
                    ['icon' => 'Sun', 'title' => '8000 nits Brightness', 'description' => 'Ultra-high brightness for unmatched visibility even in the harshest sunlight.'],
                    ['icon' => 'Shield', 'title' => 'IP65 Weatherproof', 'description' => 'Fully sealed design withstands rain, snow, dust, and extreme temperatures.'],
                    ['icon' => 'DollarSign', 'title' => 'Cost Effective', 'description' => 'Optimized pixel pitch reduces overall cost for large-scale installations.'],
                    ['icon' => 'Maximize', 'title' => 'Large Format', 'description' => 'Designed for expansive displays visible from great distances.'],
                ],
                'applications' => ['Highway Billboards', 'Building Wraps', 'Sports Venues', 'Event Stages'],
                'specifications' => [
                    ['key' => 'refreshRate', 'value' => '3840Hz'],
                    ['key' => 'viewingAngle', 'value' => '160°/160°'],
                    ['key' => 'contrast', 'value' => '5000:1'],
                    ['key' => 'grayScale', 'value' => '16bit'],
                    ['key' => 'processingDepth', 'value' => '14-bit'],
                    ['key' => 'driveMode', 'value' => '1/4 Scan'],
                    ['key' => 'inputVoltage', 'value' => 'AC 110-240V'],
                    ['key' => 'operatingHumidity', 'value' => '10%-90%'],
                ],
            ],

            // Product 3: TMAX-P1.5 Indoor Display
            [
                'name' => 'TMAX-P1.5 Indoor Display',
                'series' => 'TMAX Series',
                'category' => 'indoor',
                'pixel_pitch' => 1.5,
                'brightness_min' => 600,
                'brightness_max' => 1000,
                'cabinet_size' => '600×337.5×65mm',
                'weight' => '7.5kg/cabinet',
                'power_consumption' => 'Max 280W/m², Avg 95W/m²',
                'protection_rating' => 'IP30',
                'lifespan' => '100,000 hours',
                'operating_temp' => '0°C to +45°C',
                'environment' => 'Indoor',
                'price' => '$2,499',
                'tagline' => 'Ultra-fine pixel pitch for immersive indoor experiences',
                'description' => 'The TMAX-P1.5 sets the standard for premium indoor LED displays. With an ultra-fine 1.5mm pixel pitch, this display delivers razor-sharp visuals perfect for control rooms, boardrooms, and high-end retail environments where close viewing distances require exceptional clarity.',
                'slug' => 'tmax-p1-5-indoor-display',
                'image' => 'products/indoor/tmax-p1-5-indoor-display.webp',
                'is_active' => true,
                'features' => [
                    ['icon' => 'Eye', 'title' => '1.5mm Fine Pitch', 'description' => 'Ultra-fine pixel pitch delivers stunning detail at close viewing distances.'],
                    ['icon' => 'Monitor', 'title' => '16:9 Aspect Ratio', 'description' => 'Standard aspect ratio enables seamless content playback and video wall configurations.'],
                    ['icon' => 'Cpu', 'title' => 'HDR Support', 'description' => 'High Dynamic Range processing for vibrant colors and deep contrast.'],
                    ['icon' => 'Layers', 'title' => 'Seamless Splicing', 'description' => 'Near-invisible seams between cabinets for a truly unified display surface.'],
                ],
                'applications' => ['Control Rooms', 'Boardrooms', 'Retail Flagship Stores', 'Broadcast Studios'],
                'specifications' => [
                    ['key' => 'refreshRate', 'value' => '3840Hz'],
                    ['key' => 'viewingAngle', 'value' => '160°/160°'],
                    ['key' => 'contrast', 'value' => '8000:1'],
                    ['key' => 'grayScale', 'value' => '16bit'],
                    ['key' => 'processingDepth', 'value' => '16-bit'],
                    ['key' => 'driveMode', 'value' => '1/32 Scan'],
                    ['key' => 'inputVoltage', 'value' => 'AC 110-240V'],
                    ['key' => 'operatingHumidity', 'value' => '10%-90%'],
                    ['key' => 'colorTemperature', 'value' => '3200K-9300K'],
                    ['key' => 'pixelDensity', 'value' => '444,444 pixels/m²'],
                ],
            ],

            // Product 4: ST-P2.5 Indoor Display
            [
                'name' => 'ST-P2.5 Indoor Display',
                'series' => 'ST Series',
                'category' => 'indoor',
                'pixel_pitch' => 2.5,
                'brightness_min' => 800,
                'brightness_max' => 1200,
                'cabinet_size' => '640×480×75mm',
                'weight' => '9.5kg/cabinet',
                'power_consumption' => 'Max 320W/m², Avg 110W/m²',
                'protection_rating' => 'IP30',
                'lifespan' => '100,000 hours',
                'operating_temp' => '0°C to +45°C',
                'environment' => 'Indoor',
                'price' => '$1,899',
                'tagline' => 'Versatile indoor solution for any commercial space',
                'description' => 'The ST-P2.5 is a versatile indoor LED display designed for commercial applications. With a balanced 2.5mm pixel pitch, it delivers crisp visuals for conference rooms, shopping malls, hotel lobbies, and exhibition spaces at an attractive price point.',
                'slug' => 'st-p2-5-indoor-display',
                'image' => 'products/indoor/st-p2-5-indoor-display.webp',
                'is_active' => true,
                'features' => [
                    ['icon' => 'Layout', 'title' => 'Flexible Sizing', 'description' => 'Modular design allows custom screen sizes to fit any installation space.'],
                    ['icon' => 'Volume2', 'title' => 'Low Noise', 'description' => 'Fanless design operates silently, perfect for quiet indoor environments.'],
                    ['icon' => 'RefreshCw', 'title' => 'Quick Install', 'description' => 'Magnetic module design enables tool-free installation and maintenance.'],
                    ['icon' => 'Wifi', 'title' => 'Smart Control', 'description' => 'Built-in network control for remote management and content scheduling.'],
                ],
                'applications' => ['Conference Rooms', 'Shopping Malls', 'Hotel Lobbies', 'Exhibition Spaces'],
                'specifications' => [
                    ['key' => 'refreshRate', 'value' => '3840Hz'],
                    ['key' => 'viewingAngle', 'value' => '160°/160°'],
                    ['key' => 'contrast', 'value' => '6000:1'],
                    ['key' => 'grayScale', 'value' => '16bit'],
                    ['key' => 'processingDepth', 'value' => '14-bit'],
                    ['key' => 'driveMode', 'value' => '1/16 Scan'],
                    ['key' => 'inputVoltage', 'value' => 'AC 110-240V'],
                    ['key' => 'operatingHumidity', 'value' => '10%-90%'],
                ],
            ],

            // Product 5: LED Glass P6
            [
                'name' => 'LED Glass P6 Transparent Display',
                'series' => 'LED Glass',
                'category' => 'transparent',
                'pixel_pitch' => 6.0,
                'brightness_min' => 4000,
                'brightness_max' => 5500,
                'cabinet_size' => '1000×500×12mm',
                'weight' => '12kg/m²',
                'power_consumption' => 'Max 250W/m², Avg 85W/m²',
                'protection_rating' => 'IP54',
                'lifespan' => '100,000 hours',
                'operating_temp' => '-20°C to +50°C',
                'environment' => 'Indoor/Outdoor',
                'price' => 'Contact for Quote',
                'tagline' => 'See-through LED technology for stunning window displays',
                'description' => 'LED Glass P6 combines cutting-edge transparent technology with stunning visual performance. With up to 85% transparency, this display transforms ordinary glass facades into dynamic digital canvases while maintaining natural light and visibility. Perfect for retail storefronts, museum installations, and architectural features.',
                'slug' => 'led-glass-p6-transparent-display',
                'image' => 'products/transparent/led-glass-p6-transparent-display.webp',
                'is_active' => true,
                'features' => [
                    ['icon' => 'Eye', 'title' => '85% Transparency', 'description' => 'Ultra-high transparency maintains natural light and visibility through the display.'],
                    ['icon' => 'Sun', 'title' => '5500 nits Brightness', 'description' => 'High brightness ensures vivid content visibility even in bright ambient conditions.'],
                    ['icon' => 'Feather', 'title' => 'Ultra Lightweight', 'description' => 'At just 12kg/m², it can be installed on existing glass structures without reinforcement.'],
                    ['icon' => 'Palette', 'title' => 'Aesthetic Design', 'description' => 'Minimal visual impact when off, transforms into vibrant display when activated.'],
                ],
                'applications' => ['Retail Storefronts', 'Museum Installations', 'Glass Facades', 'Airport Terminals'],
                'specifications' => [
                    ['key' => 'refreshRate', 'value' => '3840Hz'],
                    ['key' => 'viewingAngle', 'value' => '140°/140°'],
                    ['key' => 'contrast', 'value' => '4000:1'],
                    ['key' => 'grayScale', 'value' => '14bit'],
                    ['key' => 'transparency', 'value' => '85%'],
                    ['key' => 'panelThickness', 'value' => '12mm'],
                    ['key' => 'inputVoltage', 'value' => 'AC 110-240V'],
                    ['key' => 'operatingHumidity', 'value' => '10%-90%'],
                ],
            ],

            // Product 6: Micro Mesh P10
            [
                'name' => 'Micro Mesh P10 Transparent Display',
                'series' => 'Micro Mesh',
                'category' => 'transparent',
                'pixel_pitch' => 10.0,
                'brightness_min' => 5000,
                'brightness_max' => 7000,
                'cabinet_size' => '1000×1000×15mm',
                'weight' => '8kg/m²',
                'power_consumption' => 'Max 200W/m², Avg 70W/m²',
                'protection_rating' => 'IP65',
                'lifespan' => '100,000 hours',
                'operating_temp' => '-30°C to +55°C',
                'environment' => 'Outdoor',
                'price' => 'Contact for Quote',
                'tagline' => 'Architectural mesh LED for large-scale transparent installations',
                'description' => 'The Micro Mesh P10 is designed for large-scale architectural applications where transparency and wind load are critical factors. With its lightweight mesh structure and IP65 rating, this display is ideal for building media facades, architectural landmarks, and outdoor transparent installations.',
                'slug' => 'micro-mesh-p10-transparent-display',
                'image' => 'products/transparent/micro-mesh-p10-transparent-display.webp',
                'is_active' => true,
                'features' => [
                    ['icon' => 'Wind', 'title' => 'Wind Resistant', 'description' => 'Open mesh design allows 70% wind pass-through, reducing structural load.'],
                    ['icon' => 'Shield', 'title' => 'IP65 Rated', 'description' => 'Full weatherproofing for year-round outdoor operation.'],
                    ['icon' => 'Feather', 'title' => 'Ultra Light 8kg/m²', 'description' => 'Industry-leading lightweight design minimizes structural requirements.'],
                    ['icon' => 'Maximize', 'title' => 'Massive Scale', 'description' => 'Designed for building-scale installations up to thousands of square meters.'],
                ],
                'applications' => ['Building Media Facades', 'Architectural Landmarks', 'Shopping Centers', 'Sports Arenas'],
                'specifications' => [
                    ['key' => 'refreshRate', 'value' => '1920Hz'],
                    ['key' => 'viewingAngle', 'value' => '140°/140°'],
                    ['key' => 'contrast', 'value' => '3000:1'],
                    ['key' => 'grayScale', 'value' => '14bit'],
                    ['key' => 'transparency', 'value' => '70%'],
                    ['key' => 'windLoadResistance', 'value' => '117km/h'],
                    ['key' => 'inputVoltage', 'value' => 'AC 110-240V'],
                    ['key' => 'operatingHumidity', 'value' => '10%-95%'],
                ],
            ],

            // Product 7: Digital Poster P2
            [
                'name' => 'Digital Poster P2',
                'series' => 'Poster Series',
                'category' => 'posters',
                'pixel_pitch' => 2.0,
                'brightness_min' => 800,
                'brightness_max' => 1200,
                'cabinet_size' => '640×1920×40mm',
                'weight' => '18kg',
                'power_consumption' => 'Max 210W, Avg 70W',
                'protection_rating' => 'IP30',
                'lifespan' => '100,000 hours',
                'operating_temp' => '0°C to +45°C',
                'environment' => 'Indoor',
                'price' => '$1,299',
                'tagline' => 'Ultra-slim standalone digital poster for retail and hospitality',
                'description' => 'The Digital Poster P2 is a sleek, standalone LED display designed for high-impact messaging in retail and hospitality environments. With a stunning 2mm pixel pitch, built-in media player, and ultra-slim 40mm profile, this display delivers eye-catching content with minimal footprint.',
                'slug' => 'digital-poster-p2',
                'image' => 'products/posters/digital-poster-p2.webp',
                'is_active' => true,
                'features' => [
                    ['icon' => 'Smartphone', 'title' => '40mm Ultra-Slim', 'description' => 'Portrait-format display with an incredibly thin profile that fits any space.'],
                    ['icon' => 'Play', 'title' => 'Built-in Player', 'description' => 'Integrated media player supports USB, HDMI, and WiFi content delivery.'],
                    ['icon' => 'Move', 'title' => 'Portable Design', 'description' => 'Lightweight with caster wheels for easy repositioning and event use.'],
                    ['icon' => 'Clock', 'title' => 'Content Scheduling', 'description' => 'Built-in scheduler for automated content rotation and time-based messaging.'],
                ],
                'applications' => ['Retail Stores', 'Hotel Lobbies', 'Restaurants', 'Event Venues'],
                'specifications' => [
                    ['key' => 'refreshRate', 'value' => '3840Hz'],
                    ['key' => 'viewingAngle', 'value' => '160°/160°'],
                    ['key' => 'contrast', 'value' => '6000:1'],
                    ['key' => 'grayScale', 'value' => '16bit'],
                    ['key' => 'resolution', 'value' => '320×960'],
                    ['key' => 'mediaFormat', 'value' => 'JPG, PNG, MP4, AVI'],
                    ['key' => 'inputVoltage', 'value' => 'AC 110-240V'],
                    ['key' => 'connectivity', 'value' => 'USB, HDMI, WiFi, LAN'],
                ],
            ],

            // Product 8: Digital Poster P2.5
            [
                'name' => 'Digital Poster P2.5',
                'series' => 'Poster Series',
                'category' => 'posters',
                'pixel_pitch' => 2.5,
                'brightness_min' => 800,
                'brightness_max' => 1000,
                'cabinet_size' => '640×1920×40mm',
                'weight' => '16kg',
                'power_consumption' => 'Max 180W, Avg 60W',
                'protection_rating' => 'IP30',
                'lifespan' => '100,000 hours',
                'operating_temp' => '0°C to +45°C',
                'environment' => 'Indoor',
                'price' => '$999',
                'tagline' => 'Budget-friendly digital signage poster with premium quality',
                'description' => 'The Digital Poster P2.5 combines affordability with impressive visual quality. Featuring a 2.5mm pixel pitch and the same sleek form factor as its premium sibling, this poster display is perfect for chain stores, museums, and wayfinding applications where value meets performance.',
                'slug' => 'digital-poster-p2-5',
                'image' => 'products/posters/digital-poster-p2-5.webp',
                'is_active' => true,
                'features' => [
                    ['icon' => 'DollarSign', 'title' => 'Best Value', 'description' => 'Premium quality at an accessible price point for multi-unit deployments.'],
                    ['icon' => 'Play', 'title' => 'Built-in Player', 'description' => 'Integrated media player with USB, HDMI, and network connectivity.'],
                    ['icon' => 'Copy', 'title' => 'Daisy Chain', 'description' => 'Connect multiple units in series for synchronized content playback.'],
                    ['icon' => 'Settings', 'title' => 'Remote Management', 'description' => 'Cloud-based CMS platform for managing content across multiple locations.'],
                ],
                'applications' => ['Chain Stores', 'Museums', 'Wayfinding', 'Quick Service Restaurants'],
                'specifications' => [
                    ['key' => 'refreshRate', 'value' => '3840Hz'],
                    ['key' => 'viewingAngle', 'value' => '160°/160°'],
                    ['key' => 'contrast', 'value' => '5000:1'],
                    ['key' => 'grayScale', 'value' => '16bit'],
                    ['key' => 'resolution', 'value' => '256×768'],
                    ['key' => 'mediaFormat', 'value' => 'JPG, PNG, MP4, AVI'],
                    ['key' => 'inputVoltage', 'value' => 'AC 110-240V'],
                    ['key' => 'connectivity', 'value' => 'USB, HDMI, WiFi, LAN'],
                ],
            ],
        ];
    }
}
