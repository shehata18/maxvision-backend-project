# Database Seeders

This document covers the MaxVision database seeding system, including how to run seeders, what data is populated, and tips for managing sample data.

## Overview

The seeders populate the database with real product data extracted from the frontend React application. This includes products, solutions, case studies, company information, site settings, and sample contact submissions for testing.

## Seeder Summary

| Seeder | Records | Dependencies | Description |
|--------|---------|--------------|-------------|
| `AdminUserSeeder` | 1 user | None | Creates default admin user for Filament panel |
| `ProductSeeder` | 8 products | None | Products with features, specifications, and applications |
| `SolutionSeeder` | 5 solutions | Products | Solutions with benefits, specs, and recommended products |
| `CaseStudySeeder` | 5 case studies | Products | Case studies with metrics, specs, and product associations |
| `CompanyInfoSeeder` | 5 keys | None | Milestones, team members, certifications, partners, stats |
| `SettingsSeeder` | ~15 settings | None | Site configuration, contact info, social media, hero |
| `ContactSubmissionSeeder` | 23 submissions | None | Sample contact form submissions for dashboard testing |

## Products Seeded

| # | Name | Category | Pixel Pitch | Series |
|---|------|----------|-------------|--------|
| 1 | PTF-P3 Outdoor Display | Outdoor | 3.0mm | PTF Series |
| 2 | PTF-P5 Outdoor Display | Outdoor | 5.0mm | PTF Series |
| 3 | TMAX-P1.5 Indoor Display | Indoor | 1.5mm | TMAX Series |
| 4 | ST-P2.5 Indoor Display | Indoor | 2.5mm | ST Series |
| 5 | LED Glass P6 Transparent Display | Transparent | 6.0mm | LED Glass |
| 6 | Micro Mesh P10 Transparent Display | Transparent | 10.0mm | Micro Mesh |
| 7 | Digital Poster P2 | Posters | 2.0mm | Poster Series |
| 8 | Digital Poster P2.5 | Posters | 2.5mm | Poster Series |

## Running Seeders

### Fresh Database (Recommended for Development)

Drops all tables, runs migrations, and seeds everything:

```bash
php artisan migrate:fresh --seed
```

### Using the Custom Command

The `maxvision:seed-sample-data` command provides more control:

```bash
# Full seeding with progress display and summary
php artisan maxvision:seed-sample-data

# Fresh migration + seeding
php artisan maxvision:seed-sample-data --fresh

# Seed only a specific table
php artisan maxvision:seed-sample-data --only=products
php artisan maxvision:seed-sample-data --only=solutions
php artisan maxvision:seed-sample-data --only=case-studies
php artisan maxvision:seed-sample-data --only=company
php artisan maxvision:seed-sample-data --only=settings
php artisan maxvision:seed-sample-data --only=contacts
```

### Individual Seeders

```bash
php artisan db:seed --class=ProductSeeder
php artisan db:seed --class=SolutionSeeder
php artisan db:seed --class=CaseStudySeeder
php artisan db:seed --class=CompanyInfoSeeder
php artisan db:seed --class=SettingsSeeder
php artisan db:seed --class=ContactSubmissionSeeder
```

## Environment-Aware Seeding

The `DatabaseSeeder` adjusts behavior based on the current environment:

| Environment | What Gets Seeded |
|-------------|-----------------|
| **Development** | Everything — admin, products, solutions, case studies, company, settings, sample contact submissions |
| **Staging** | Same as development, but **excludes** sample contact submissions |
| **Production** | Only essential data — admin user, company info, and site settings |

To override this behavior for production, use the custom command:

```bash
php artisan maxvision:seed-sample-data
```

This command prompts for confirmation in production.

## Backup & Restore

### Creating a Backup

```bash
# Standard SQL dump
php artisan maxvision:backup-seed-data

# Compressed backup
php artisan maxvision:backup-seed-data --compress
```

Backups are saved to `database/backups/seed-data-{timestamp}.sql(.gz)`.

### Restoring from Backup

```bash
php artisan maxvision:restore-seed-data seed-data-2026-02-17_021300.sql
```

If you provide a filename that doesn't exist, the command will list available backups.

## Data Sources

All seeder data was extracted from the frontend React application:

| Data | Source |
|------|--------|
| Products | `src/data/products.ts` — specs, features, applications |
| Solutions | `src/data/solutions.ts` — benefits, specs, recommended products |
| Case Studies | `src/pages/CaseStudies/` — metrics, specs, project details |
| Company Info | `src/pages/About.tsx` — milestones, team, certifications |
| Settings | `src/components/Footer.tsx`, `src/pages/Home/Hero.tsx` |
| Contact Submissions | Fabricated sample data for testing |

## Storage & Images

Products, solutions, and case studies reference image paths in the `public` storage disk:

```
storage/app/public/
├── products/
│   ├── outdoor/         # PTF-P3, PTF-P5
│   ├── indoor/          # TMAX-P1.5, ST-P2.5
│   ├── transparent/     # LED Glass P6, Micro Mesh P10
│   └── posters/         # Digital Poster P2, P2.5
├── solutions/           # retail, outdoor, corporate, events, architecture
├── case-studies/        # luxury-mall, highway-billboard, etc.
└── company/
```

Ensure the storage symlink exists:

```bash
php artisan storage:link
```

## Troubleshooting

### Foreign Key Constraint Errors
Products must be seeded before solutions and case studies. Always use `migrate:fresh --seed` or the custom command which handles the correct order.

### Duplicate Key Errors
If you see unique constraint violations, the data already exists. Use `migrate:fresh --seed` for a clean slate, or run a specific seeder after clearing the relevant table.

### Images Not Loading
1. Run `php artisan storage:link`
2. Check `APP_URL` in `.env` matches your server URL
3. Verify files exist in `storage/app/public/`

### Admin Login Not Working
The `AdminUserSeeder` creates a user with the credentials defined in the seeder. Check the seeder for the default email/password.

## Customization

To add new products or modify existing data:

1. Edit the relevant seeder file in `database/seeders/`
2. Run `php artisan migrate:fresh --seed` to apply changes
3. Alternatively, clear the relevant table first, then run the individual seeder

To add a new seeder:

1. Create the class in `database/seeders/`
2. Add it to `DatabaseSeeder.php` in the correct dependency order
3. Add it to the `SeedSampleData` command's seeder list
