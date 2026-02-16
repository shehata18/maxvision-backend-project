<?php

namespace Database\Seeders;

use App\Models\ContactSubmission;
use Illuminate\Database\Seeder;

class ContactSubmissionSeeder extends Seeder
{
    /**
     * Seed the contact_submissions table with sample data.
     */
    public function run(): void
    {
        $projectTypes = array_keys(ContactSubmission::getProjectTypeOptions());
        $timelines = array_keys(ContactSubmission::getTimelineOptions());
        $budgetRanges = array_keys(ContactSubmission::getBudgetRangeOptions());

        $submissions = [
            // New submissions (70%)
            [
                'first_name' => 'Michael', 'last_name' => 'Johnson',
                'email' => 'michael.johnson@retailco.com', 'phone' => '416-555-0101',
                'company' => 'RetailCo Inc.', 'project_type' => 'Indoor LED Display',
                'timeline' => 'Immediate', 'size_requirements' => '3m x 2m video wall for flagship store entrance',
                'budget_range' => '$25,000 - $50,000', 'message' => 'We are renovating our flagship Toronto location and need an impressive LED display for the main entrance. Looking for high brightness and seamless design.',
                'status' => 'new', 'created_at' => now()->subDays(1),
            ],
            [
                'first_name' => 'Sarah', 'last_name' => 'Kim',
                'email' => 'sarah.kim@eventspro.ca', 'phone' => '604-555-0202',
                'company' => 'EventsPro Canada', 'project_type' => 'Rental LED Screen',
                'timeline' => '1-3 Months', 'size_requirements' => 'Multiple panels for outdoor concert series, 6m x 4m main stage',
                'budget_range' => '$50,000 - $100,000', 'message' => 'We organize a summer concert series and need rental LED screens for 8 events. Need weather-proof panels with quick setup.',
                'status' => 'new', 'created_at' => now()->subDays(2),
            ],
            [
                'first_name' => 'David', 'last_name' => 'Patel',
                'email' => 'dpatel@architectvision.com', 'phone' => '905-555-0303',
                'company' => 'ArchitectVision', 'project_type' => 'Transparent LED',
                'timeline' => '3-6 Months', 'size_requirements' => 'Full glass facade coverage, approximately 200 sqft',
                'budget_range' => 'Over $100,000', 'message' => 'Designing a new commercial tower downtown and want to integrate transparent LED into the glass curtain wall on the first 3 floors.',
                'status' => 'new', 'created_at' => now()->subDays(3),
            ],
            [
                'first_name' => 'Emily', 'last_name' => 'Zhang',
                'email' => 'emily@brightmall.ca', 'phone' => '647-555-0404',
                'company' => 'BrightMall Ltd.', 'project_type' => 'Indoor LED Display',
                'timeline' => '1-3 Months', 'size_requirements' => '10 LED poster displays across mall corridors',
                'budget_range' => '$10,000 - $25,000', 'message' => 'Looking to replace static signage in our mall with LED poster displays. Need remote content management for all screens.',
                'status' => 'new', 'created_at' => now()->subDays(4),
            ],
            [
                'first_name' => 'Robert', 'last_name' => 'Thompson',
                'email' => 'robert.t@outdooradvertising.com', 'phone' => '403-555-0505',
                'company' => 'Outdoor Advertising Co.', 'project_type' => 'Outdoor LED Display',
                'timeline' => 'Immediate', 'size_requirements' => 'Highway billboard, 14m x 5m, dual-sided',
                'budget_range' => 'Over $100,000', 'message' => 'Need to replace an aging billboard on Highway 401 near Toronto. Must be visible from 500m+ and withstand Canadian winters.',
                'status' => 'new', 'created_at' => now()->subDays(5),
            ],
            [
                'first_name' => 'Lisa', 'last_name' => 'Martinez',
                'email' => 'lisa@corporatehq.com', 'phone' => '514-555-0606',
                'company' => 'CorporateHQ', 'project_type' => 'LED Video Wall',
                'timeline' => '3-6 Months', 'size_requirements' => 'Boardroom video wall 4m x 2.5m, fine pitch',
                'budget_range' => '$50,000 - $100,000', 'message' => 'Need a seamless video wall for our executive boardroom. Must support 4K content and have zero-bezel design. 24/7 operation.',
                'status' => 'new', 'created_at' => now()->subDays(7),
            ],
            [
                'first_name' => 'Alex', 'last_name' => 'Brown',
                'email' => 'alex.b@startupinc.io', 'phone' => null,
                'company' => 'StartupInc', 'project_type' => 'Custom Solution',
                'timeline' => '6-12 Months', 'size_requirements' => 'Interactive LED floor for retail experience, 5m x 5m',
                'budget_range' => '$25,000 - $50,000', 'message' => 'We are building an interactive retail experience and need an LED floor that responds to foot traffic. Very excited about this project!',
                'status' => 'new', 'created_at' => now()->subDays(8),
            ],
            [
                'first_name' => 'Jennifer', 'last_name' => 'Wilson',
                'email' => 'jwilson@museumtech.org', 'phone' => '613-555-0808',
                'company' => 'Museum of Tech', 'project_type' => 'Indoor LED Display',
                'timeline' => '6-12 Months', 'size_requirements' => 'Multiple displays for new exhibition, various sizes',
                'budget_range' => '$50,000 - $100,000', 'message' => 'Planning a new permanent exhibition on technology history. Need multiple high-quality indoor displays with curved options.',
                'status' => 'new', 'created_at' => now()->subDays(10),
            ],
            [
                'first_name' => 'Omar', 'last_name' => 'Hassan',
                'email' => 'omar@stadiumgroup.ca', 'phone' => '780-555-0909',
                'company' => 'Stadium Entertainment Group', 'project_type' => 'Outdoor LED Display',
                'timeline' => '12+ Months', 'size_requirements' => 'Scoreboard: 12m x 8m, perimeter ribbon: 200m x 1m',
                'budget_range' => 'Over $100,000', 'message' => 'Renovating stadium with new LED scoreboard and perimeter ribbon boards. Need consultation on best products for high-refresh sports content.',
                'status' => 'new', 'created_at' => now()->subDays(12),
            ],
            [
                'first_name' => 'Grace', 'last_name' => 'Lee',
                'email' => 'grace@boutiquebrands.com', 'phone' => '416-555-1010',
                'company' => 'Boutique Brands', 'project_type' => 'Transparent LED',
                'timeline' => '1-3 Months', 'size_requirements' => 'Store window display 2m x 3m transparent',
                'budget_range' => '$10,000 - $25,000', 'message' => 'Want to add transparent LED to our Yorkville boutique window. Must maintain natural light while showing dynamic content.',
                'status' => 'new', 'created_at' => now()->subDays(14),
            ],
            [
                'first_name' => 'Daniel', 'last_name' => 'Nguyen',
                'email' => 'daniel.n@churchgroup.org', 'phone' => '905-555-1111',
                'company' => 'Grace Community Church', 'project_type' => 'Indoor LED Display',
                'timeline' => '3-6 Months', 'size_requirements' => 'Stage backdrop 6m x 3m',
                'budget_range' => '$25,000 - $50,000', 'message' => 'Need a large LED display for our worship stage. Will be used for song lyrics, videos, and live camera feeds.',
                'status' => 'new', 'created_at' => now()->subDays(16),
            ],
            [
                'first_name' => 'Rachel', 'last_name' => 'Foster',
                'email' => 'rachel@hotelchain.com', 'phone' => '604-555-1212',
                'company' => 'Prestige Hotels', 'project_type' => 'LED Video Wall',
                'timeline' => '3-6 Months', 'size_requirements' => 'Lobby video wall 5m x 2m',
                'budget_range' => '$25,000 - $50,000', 'message' => 'Need an elegant lobby display for our new Vancouver hotel. Must blend with modern interior design.',
                'status' => 'new', 'created_at' => now()->subDays(18),
            ],
            [
                'first_name' => 'Chris', 'last_name' => 'Anderson',
                'email' => 'chris@autoshow.ca', 'phone' => '416-555-1313',
                'company' => 'Canadian Auto Show', 'project_type' => 'Rental LED Screen',
                'timeline' => 'Immediate', 'size_requirements' => 'Multiple rental screens for auto show, 3 x 4m screens',
                'budget_range' => '$25,000 - $50,000', 'message' => 'Annual auto show in 6 weeks. Need rental LED screens for multiple brand booths.',
                'status' => 'new', 'created_at' => now()->subDays(20),
            ],
            [
                'first_name' => 'Natalie', 'last_name' => 'Clark',
                'email' => 'natalie@fitnessclub.ca', 'phone' => '647-555-1414',
                'company' => 'FitLife Clubs', 'project_type' => 'Indoor LED Display',
                'timeline' => '1-3 Months', 'size_requirements' => 'Gym motivational display 3m x 1.5m',
                'budget_range' => 'Under $10,000', 'message' => 'Want a vibrant LED display for our main workout area. Display workout routines and motivational content.',
                'status' => 'new', 'created_at' => now()->subDays(22),
            ],

            // Contacted submissions (20%)
            [
                'first_name' => 'James', 'last_name' => 'Wright',
                'email' => 'james@transitauthority.ca', 'phone' => '416-555-1515',
                'company' => 'Metro Transit Authority', 'project_type' => 'Indoor LED Display',
                'timeline' => '6-12 Months', 'size_requirements' => '50 passenger information displays across subway stations',
                'budget_range' => 'Over $100,000', 'message' => 'Upgrading passenger information displays across the subway network. Need reliable 24/7 displays with remote management.',
                'status' => 'contacted', 'created_at' => now()->subDays(25),
            ],
            [
                'first_name' => 'Amanda', 'last_name' => 'Taylor',
                'email' => 'amanda@luxuryretail.com', 'phone' => '416-555-1616',
                'company' => 'Luxury Retail Group', 'project_type' => 'Transparent LED',
                'timeline' => '1-3 Months', 'size_requirements' => '4 storefront windows, 2m x 3m each',
                'budget_range' => '$50,000 - $100,000', 'message' => 'Premium retail brand looking to upgrade 4 storefronts on Bloor Street with transparent LED. High-end aesthetics essential.',
                'status' => 'contacted', 'created_at' => now()->subDays(30),
            ],
            [
                'first_name' => 'Mark', 'last_name' => 'Davis',
                'email' => 'mark@cityplanning.gov.ca', 'phone' => '905-555-1717',
                'company' => 'City of Mississauga', 'project_type' => 'Outdoor LED Display',
                'timeline' => '12+ Months', 'size_requirements' => 'Digital signage network for downtown area, 10 locations',
                'budget_range' => 'Over $100,000', 'message' => 'Smart city initiative to deploy digital signage across downtown. Need weather-proof, remotely managed displays.',
                'status' => 'contacted', 'created_at' => now()->subDays(35),
            ],
            [
                'first_name' => 'Sophia', 'last_name' => 'Chen',
                'email' => 'sophia@mediaco.ca', 'phone' => '604-555-1818',
                'company' => 'MediaCo Productions', 'project_type' => 'Rental LED Screen',
                'timeline' => 'Immediate', 'size_requirements' => 'Virtual production LED volume, 10m curved wall',
                'budget_range' => 'Over $100,000', 'message' => 'Building a virtual production stage and need high-quality LED volume. Must support camera tracking and have excellent refresh rate.',
                'status' => 'contacted', 'created_at' => now()->subDays(38),
            ],
            [
                'first_name' => 'Kevin', 'last_name' => 'O\'Connor',
                'email' => 'kevin@sportsbar.ca', 'phone' => '416-555-1919',
                'company' => 'The Sports Bar & Grill', 'project_type' => 'LED Video Wall',
                'timeline' => '1-3 Months', 'size_requirements' => 'Main viewing wall 5m x 3m for sports events',
                'budget_range' => '$25,000 - $50,000', 'message' => 'Need a massive screen for our sports bar. Must look great from every angle in the venue.',
                'status' => 'contacted', 'created_at' => now()->subDays(40),
            ],
            [
                'first_name' => 'Emma', 'last_name' => 'Parker',
                'email' => 'emma@schooldistrict.ca', 'phone' => '905-555-2020',
                'company' => 'York Region School Board', 'project_type' => 'Indoor LED Display',
                'timeline' => '6-12 Months', 'size_requirements' => 'Auditorium backdrop 8m x 4m',
                'budget_range' => '$50,000 - $100,000', 'message' => 'New performing arts center needs a large LED backdrop for student performances and assemblies.',
                'status' => 'contacted', 'created_at' => now()->subDays(42),
            ],

            // Converted submissions (10%)
            [
                'first_name' => 'Richard', 'last_name' => 'Morrison',
                'email' => 'richard@shoppingcenter.ca', 'phone' => '416-555-2121',
                'company' => 'Grand Shopping Centre', 'project_type' => 'Indoor LED Display',
                'timeline' => 'Immediate', 'size_requirements' => 'Central atrium display 4m x 6m double-sided',
                'budget_range' => 'Over $100,000', 'message' => 'Our shopping center is the premier destination downtown. Need a statement LED piece for the central atrium.',
                'status' => 'converted', 'created_at' => now()->subDays(45),
            ],
            [
                'first_name' => 'Michelle', 'last_name' => 'Adams',
                'email' => 'michelle@dealership.com', 'phone' => '905-555-2222',
                'company' => 'Premium Auto Group', 'project_type' => 'Outdoor LED Display',
                'timeline' => '1-3 Months', 'size_requirements' => 'Showroom window 3m x 2m + outdoor pylon sign 2m x 3m',
                'budget_range' => '$50,000 - $100,000', 'message' => 'Auto dealership needs both indoor showroom display and outdoor signage. Want to showcase inventory dynamically.',
                'status' => 'converted', 'created_at' => now()->subDays(50),
            ],
            [
                'first_name' => 'Thomas', 'last_name' => 'Baker',
                'email' => 'thomas@conference.ca', 'phone' => '613-555-2323',
                'company' => 'National Conference Center', 'project_type' => 'Rental LED Screen',
                'timeline' => 'Immediate', 'size_requirements' => 'Main stage 8m x 4m + 2 side screens 3m x 2m',
                'budget_range' => '$25,000 - $50,000', 'message' => 'Annual management conference in 3 weeks. Need rental screens and full AV support.',
                'status' => 'converted', 'created_at' => now()->subDays(55),
            ],
        ];

        // Disable observer during seeding to avoid sending notifications
        ContactSubmission::withoutEvents(function () use ($submissions) {
            foreach ($submissions as $submission) {
                ContactSubmission::create($submission);
            }
        });
    }
}
