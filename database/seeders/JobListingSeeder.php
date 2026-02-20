<?php

namespace Database\Seeders;

use App\Models\JobListing;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Senior LED Systems Engineer',
                'department' => 'Engineering',
                'category' => 'engineering',
                'location' => 'toronto',
                'job_type' => 'full-time',
                'summary' => 'Join our engineering team to design and develop next-generation LED display systems. Work on cutting-edge technology for commercial and industrial applications.',
                'description' => '<p>We are looking for an experienced LED Systems Engineer to join our growing engineering team. You will be responsible for designing, developing, and optimizing LED display systems for various applications including retail, outdoor advertising, and architectural installations.</p><p>The ideal candidate has extensive experience in electronics design, LED technology, and display systems architecture.</p>',
                'requirements' => [
                    'Bachelor\'s degree in Electrical Engineering, Computer Engineering, or related field',
                    '5+ years of experience in LED display or similar electronic systems design',
                    'Strong knowledge of LED driver circuits, power electronics, and thermal management',
                    'Experience with PCB design tools (Altium, Eagle, or similar)',
                    'Proficiency in embedded systems programming (C/C++)',
                    'Excellent problem-solving and analytical skills',
                    'Strong communication and teamwork abilities',
                ],
                'benefits' => [
                    'Competitive salary ($100,000 - $130,000 CAD)',
                    'Comprehensive health and dental benefits',
                    'Flexible work arrangements',
                    'Professional development budget',
                    'Latest technology and tools',
                    'Collaborative and innovative work environment',
                ],
                'salary_range' => '$100,000 - $130,000 CAD',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Sales Account Manager',
                'department' => 'Sales',
                'category' => 'sales',
                'location' => 'toronto',
                'job_type' => 'full-time',
                'summary' => 'Drive growth by managing key client relationships and identifying new business opportunities in the LED display market across North America.',
                'description' => '<p>We are seeking a motivated Sales Account Manager to manage and grow our client relationships in the LED display industry. You will be responsible for achieving sales targets, developing new business opportunities, and providing exceptional customer service.</p>',
                'requirements' => [
                    'Bachelor\'s degree in Business, Marketing, or related field',
                    '3+ years of B2B sales experience, preferably in display technology or related industries',
                    'Proven track record of meeting or exceeding sales targets',
                    'Strong presentation and negotiation skills',
                    'Experience with CRM systems (Salesforce preferred)',
                    'Willingness to travel up to 25% of the time',
                    'Excellent communication and relationship-building skills',
                ],
                'benefits' => [
                    'Competitive base salary plus commission',
                    'Car allowance',
                    'Health and dental benefits',
                    'Uncapped commission structure',
                    'Annual sales incentive trips',
                ],
                'salary_range' => '$70,000 - $90,000 CAD + Commission',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Digital Marketing Specialist',
                'department' => 'Marketing',
                'category' => 'marketing',
                'location' => 'hybrid',
                'job_type' => 'full-time',
                'summary' => 'Create and execute digital marketing campaigns to promote our LED display solutions and drive brand awareness across multiple channels.',
                'description' => '<p>We are looking for a creative Digital Marketing Specialist to join our marketing team. You will develop and implement digital marketing strategies, manage social media presence, and create engaging content to showcase our LED display solutions.</p>',
                'requirements' => [
                    'Bachelor\'s degree in Marketing, Communications, or related field',
                    '2+ years of experience in digital marketing',
                    'Proficiency in Google Analytics, Google Ads, and social media advertising',
                    'Experience with content management systems and email marketing platforms',
                    'Strong copywriting and content creation skills',
                    'Knowledge of SEO best practices',
                    'Creative mindset with attention to detail',
                ],
                'benefits' => [
                    'Competitive salary',
                    'Flexible hybrid work model',
                    'Professional development opportunities',
                    'Health and wellness benefits',
                    'Creative and supportive team environment',
                ],
                'salary_range' => '$55,000 - $75,000 CAD',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Technical Support Specialist',
                'department' => 'Customer Support',
                'category' => 'customer_support',
                'location' => 'remote',
                'job_type' => 'full-time',
                'summary' => 'Provide expert technical support to our clients, troubleshooting LED display systems and ensuring customer satisfaction.',
                'description' => '<p>We are seeking a Technical Support Specialist to provide exceptional support to our customers. You will troubleshoot technical issues, provide product guidance, and ensure customer satisfaction with our LED display solutions.</p>',
                'requirements' => [
                    'Technical diploma or degree in Electronics, IT, or related field',
                    '2+ years of experience in technical support or customer service',
                    'Strong understanding of display technology, electronics, or AV systems',
                    'Excellent troubleshooting and problem-solving skills',
                    'Strong verbal and written communication skills',
                    'Experience with support ticketing systems',
                    'Ability to work independently in a remote environment',
                ],
                'benefits' => [
                    'Fully remote work',
                    'Competitive salary',
                    'Home office equipment provided',
                    'Flexible working hours',
                    'Comprehensive benefits package',
                ],
                'salary_range' => '$50,000 - $65,000 CAD',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Manufacturing Technician',
                'department' => 'Operations',
                'category' => 'operations',
                'location' => 'toronto',
                'job_type' => 'full-time',
                'summary' => 'Assemble and test LED display modules and systems, ensuring quality standards are met for our premium products.',
                'description' => '<p>We are looking for a Manufacturing Technician to join our production team. You will be responsible for assembling, testing, and quality-checking LED display modules and complete systems.</p>',
                'requirements' => [
                    'Technical diploma or equivalent experience',
                    'Experience in electronics assembly or manufacturing',
                    'Familiarity with soldering and electronic components',
                    'Attention to detail and quality-focused mindset',
                    'Ability to read and interpret technical drawings',
                    'Strong work ethic and reliability',
                    'Team player with good communication skills',
                ],
                'benefits' => [
                    'Competitive hourly wage',
                    'Overtime opportunities',
                    'Health and dental benefits',
                    'Training and skill development',
                    'Modern manufacturing facility',
                ],
                'salary_range' => '$45,000 - $55,000 CAD',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'UX/UI Designer',
                'department' => 'Design',
                'category' => 'design',
                'location' => 'hybrid',
                'job_type' => 'full-time',
                'summary' => 'Design intuitive user interfaces for our LED display control software and web applications.',
                'description' => '<p>We are seeking a talented UX/UI Designer to create beautiful and functional interfaces for our LED display control software and web platforms. You will work closely with product and engineering teams to deliver exceptional user experiences.</p>',
                'requirements' => [
                    'Bachelor\'s degree in Design, HCI, or related field',
                    '3+ years of UX/UI design experience',
                    'Proficiency in Figma, Sketch, or similar design tools',
                    'Strong portfolio demonstrating UI design skills',
                    'Experience with user research and usability testing',
                    'Understanding of responsive design principles',
                    'Ability to create interactive prototypes',
                ],
                'benefits' => [
                    'Competitive salary',
                    'Flexible hybrid work model',
                    'Creative and collaborative environment',
                    'Professional development budget',
                    'Latest design tools and equipment',
                ],
                'salary_range' => '$70,000 - $90,000 CAD',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Software Engineering Intern',
                'department' => 'Engineering',
                'category' => 'engineering',
                'location' => 'toronto',
                'job_type' => 'internship',
                'summary' => 'Gain hands-on experience developing software for LED display systems and control applications.',
                'description' => '<p>We are offering an exciting internship opportunity for students interested in software development for embedded systems and web applications. You will work on real projects that impact our LED display solutions.</p>',
                'requirements' => [
                    'Currently pursuing a degree in Computer Science, Software Engineering, or related field',
                    'Familiarity with JavaScript, TypeScript, or Python',
                    'Interest in embedded systems or hardware-software integration',
                    'Strong problem-solving skills',
                    'Eagerness to learn and grow',
                    'Good communication skills',
                ],
                'benefits' => [
                    'Competitive internship stipend',
                    'Mentorship from experienced engineers',
                    'Real-world project experience',
                    'Potential for full-time opportunity',
                    'Flexible schedule around classes',
                ],
                'salary_range' => '$20 - $25 per hour',
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($jobs as $job) {
            JobListing::create($job);
        }

        $this->command->info('Job listings seeded successfully!');
    }
}
