<?php

namespace Database\Seeders;

use App\Models\CompanyInfo;
use Illuminate\Database\Seeder;

class CompanyInfoSeeder extends Seeder
{
    /**
     * Seed the company_info table with data from the frontend About page.
     */
    public function run(): void
    {
        // Milestones
        CompanyInfo::updateKey('milestones', [
            ['year' => '2008', 'title' => 'Founded in Shenzhen', 'description' => 'Maxvision Display Inc. was established with a mission to manufacture high-performance LED panels for global markets.'],
            ['year' => '2012', 'title' => 'North American Expansion', 'description' => 'Opened our first Canadian office in Toronto, bringing factory-direct pricing and local engineering support to the NA market.'],
            ['year' => '2015', 'title' => 'UL & CSA Certification', 'description' => 'Achieved UL Listed and CSA certification across the full product line, meeting strict Canadian safety standards.'],
            ['year' => '2018', 'title' => '1,000th Installation', 'description' => 'Milestone deployment — over 1,000 commercial LED installations across retail, transit, and architectural sectors.'],
            ['year' => '2021', 'title' => 'R&D Innovation Lab', 'description' => 'Launched a dedicated R&D lab focused on transparent LED, MicroLED, and smart city display technologies.'],
            ['year' => '2024', 'title' => 'Nationwide Service Network', 'description' => 'Expanded field service coverage to every major Canadian metro with 24/7 remote monitoring capabilities.'],
        ]);

        // Team Members
        CompanyInfo::updateKey('team_members', [
            ['name' => 'David Chen', 'role' => 'CEO & Co-Founder', 'bio' => '20+ years in LED display engineering. Previously VP of Product at a Fortune 500 display manufacturer.', 'initials' => 'DC'],
            ['name' => 'Sarah Mitchell', 'role' => 'VP of Sales, North America', 'bio' => 'Drives enterprise partnerships across retail, outdoor advertising, and architectural sectors.', 'initials' => 'SM'],
            ['name' => 'James Park', 'role' => 'Director of Engineering', 'bio' => 'Leads product development and custom installation engineering. UL/CSA certification specialist.', 'initials' => 'JP'],
            ['name' => 'Amara Okonkwo', 'role' => 'Head of Customer Success', 'bio' => 'Oversees project delivery and post-installation support across 500+ active accounts.', 'initials' => 'AO'],
            ['name' => 'Liam Torres', 'role' => 'R&D Lead', 'bio' => 'Pioneers transparent LED and MicroLED technologies. Holds 12 patents in display optics.', 'initials' => 'LT'],
            ['name' => 'Priya Sharma', 'role' => 'Operations Manager', 'bio' => 'Manages supply chain, logistics, and warehouse operations for the Canadian distribution network.', 'initials' => 'PS'],
        ]);

        // Certifications
        CompanyInfo::updateKey('certifications', [
            ['name' => 'UL Listed', 'description' => 'Underwriters Laboratories safety certification for all display products'],
            ['name' => 'CSA Certified', 'description' => 'Canadian Standards Association compliance for electrical safety'],
            ['name' => 'CE Marked', 'description' => 'European conformity for health, safety, and environmental protection'],
            ['name' => 'FCC Compliant', 'description' => 'Federal Communications Commission electromagnetic compatibility'],
            ['name' => 'RoHS', 'description' => 'Restriction of Hazardous Substances — lead-free manufacturing'],
            ['name' => 'IP65 Rated', 'description' => 'Ingress Protection for dust-tight and water-jet resistance'],
            ['name' => 'ISO 9001', 'description' => 'International standard for quality management systems'],
            ['name' => 'ISO 14001', 'description' => 'Environmental management system certification'],
        ]);

        // Technology Partners
        CompanyInfo::updateKey('partners', [
            ['name' => 'Novastar', 'logo' => null],
            ['name' => 'Colorlight', 'logo' => null],
            ['name' => 'Brompton', 'logo' => null],
            ['name' => 'Cree LED', 'logo' => null],
            ['name' => 'Nationstar', 'logo' => null],
            ['name' => 'Kinglight', 'logo' => null],
            ['name' => 'MBI', 'logo' => null],
            ['name' => 'ICN', 'logo' => null],
            ['name' => 'Macroblock', 'logo' => null],
            ['name' => 'Meanwell', 'logo' => null],
        ]);

        // Company Stats
        CompanyInfo::updateKey('stats', [
            ['value' => '1,500+', 'label' => 'Installations'],
            ['value' => '15+', 'label' => 'Years Experience'],
            ['value' => '12', 'label' => 'Patents Held'],
            ['value' => '99.5%', 'label' => 'Uptime SLA'],
        ]);
    }
}
