<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Seed the settings table with data from the frontend Footer and hero sections.
     */
    public function run(): void
    {
        // General Settings
        Setting::set('site_name', 'MaxVision Display Inc.', 'string');
        Setting::set('site_tagline', 'High-Performance LED Display Solutions', 'string');
        Setting::set('site_description', 'High-performance LED display solutions engineered for impact. Custom installations for retail, outdoor, events, and architectural applications.', 'text');
        Setting::set('site_logo', '/logo.svg', 'string');
        Setting::set('site_favicon', '/favicon.ico', 'string');

        // Contact Information
        Setting::set('contact_phone', '1-888-LED-PROS', 'string');
        Setting::set('contact_email', 'sales@maxvisiondisplay.com', 'string');
        Setting::set('contact_address', "123 Technology Drive\nToronto, ON M5V 1A1, Canada", 'text');

        // Social Media
        Setting::set('social_linkedin', 'https://linkedin.com/company/maxvision', 'string');
        Setting::set('social_youtube', 'https://youtube.com/@maxvision', 'string');
        Setting::set('social_twitter', 'https://twitter.com/maxvision', 'string');

        // Hero Section
        Setting::set('hero_title', 'Engineering Brilliance in LED Display Technology', 'string');
        Setting::set('hero_subtitle', 'From our founding in Shenzhen to our Canadian headquarters in Toronto, we\'ve been building LED display solutions that set the standard for brightness, durability, and visual impact.', 'text');
        Setting::set('hero_stats', json_encode([
            ['label' => 'Installations', 'value' => '1,500+'],
            ['label' => 'Years Experience', 'value' => '15+'],
            ['label' => 'Patents Held', 'value' => '12'],
            ['label' => 'Uptime SLA', 'value' => '99.5%'],
        ]), 'json');

        // Footer
        Setting::set('footer_about', 'High-performance LED display solutions engineered for impact. Custom installations for retail, outdoor, events, and architectural applications.', 'text');
        Setting::set('footer_copyright', '© ' . date('Y') . ' Maxvision Display Inc. All rights reserved.', 'string');
    }
}
