<h1 align="center">Maxvision Display Inc. - Backend API</h1>

<p align="center">
<img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
<img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php" alt="PHP">
<img src="https://img.shields.io/badge/Filament-3.2-50C878?style=for-the-badge" alt="Filament">
<img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="License">
</p>

<p align="center">
<strong>RESTful API & Admin Dashboard for LED Display Solutions Company</strong>
</p>

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [Installation](#installation)
- [Configuration](#configuration)
- [Admin Panel](#admin-panel)
- [Development](#development)
- [Testing](#testing)
- [API Documentation](#api-documentation)

---

## 🎯 Overview

This is the backend API for **Maxvision Display Inc.**, a company specializing in high-performance LED display solutions. The backend powers:

- **Public REST API** - For the company website/frontend
- **Admin Dashboard** - Filament-powered CMS for content management
- **Contact/Quote System** - With email notifications
- **Careers Portal** - Job listings and application management

The API serves product catalogs, solution information, case studies, company data, and handles contact form submissions and job applications.

---

## ✨ Features

### Public API Features

| Feature | Description |
|---------|-------------|
| 📦 **Product Catalog** | LED display products with categories, specifications, features, and applications |
| 💡 **Solutions** | Industry-specific solutions (retail, outdoor, corporate, events, architecture) |
| 📊 **Case Studies** | Customer success stories with metrics and specifications |
| 🏢 **Company Info** | About page data, team, certifications, and site settings |
| 📧 **Contact System** | Quote request submissions with email notifications |
| 💼 **Careers** | Job listings with filtering and search |
| 📝 **Job Applications** | Resume uploads and application tracking |

### Admin Dashboard Features

| Feature | Description |
|---------|-------------|
| 🎛️ **Filament Admin Panel** | Full-featured CMS for all content |
| 📦 **Product Management** | CRUD for products with image optimization |
| 💡 **Solutions Management** | Manage industry solutions and related products |
| 📊 **Case Study Management** | Create and manage customer success stories |
| 📧 **Contact Submissions** | View and manage incoming quote requests |
| 💼 **Job Listings** | Post and manage job openings |
| 📝 **Job Applications** | Review applications, update status, download resumes |
| ⚙️ **Site Settings** | Dynamic logo, favicon, contact info, social links |
| 📈 **Dashboard Widgets** | Analytics, charts, and statistics |

### Technical Features

- **Caching** - Redis/file caching for API responses
- **Rate Limiting** - Throttling on contact/application forms
- **Image Optimization** - Automatic image resizing and optimization
- **Email Notifications** - Confirmation emails to users, alerts to admins
- **Search & Filtering** - Full-text search and category filtering
- **Pagination** - Efficient data loading with pagination
- **API Resources** - Clean, consistent JSON responses
- **Request Validation** - Comprehensive form request validation

---

## 🛠️ Tech Stack

| Category | Technology |
|----------|------------|
| **Framework** | Laravel 10.x |
| **PHP Version** | 8.1+ |
| **Admin Panel** | Filament 3.2 |
| **Authentication** | Laravel Sanctum |
| **Database** | MySQL 8.0+ |
| **Cache** | File/Redis |
| **Image Processing** | Intervention Image 3.x |
| **Mail** | SMTP (configurable) |

---

## 🏗️ Architecture

```
maxvision-backend/
├── app/
│   ├── Console/Commands/      # Artisan commands
│   ├── Enums/                  # PHP Enums for categories, statuses
│   ├── Filament/               # Admin panel resources & widgets
│   │   ├── Pages/              # Custom Filament pages
│   │   ├── Resources/          # CRUD resources for models
│   │   └── Widgets/            # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/Api/    # REST API controllers
│   │   ├── Middleware/         # Custom middleware
│   │   ├── Requests/           # Form request validation
│   │   └── Resources/          # API resource transformers
│   ├── Models/                 # Eloquent ORM models
│   ├── Notifications/          # Email notifications
│   ├── Observers/              # Model observers
│   ├── Providers/              # Service providers
│   └── Services/               # Business logic services
├── database/
│   ├── migrations/             # Database schema migrations
│   └── seeders/                # Sample/test data seeders
├── routes/
│   └── api.php                 # API route definitions
├── postman_collection.json     # Postman API collection
└── postman_environment.json    # Postman environment vars
```

---

## 🚀 API Endpoints

### Base URL
```
http://your-domain.com/api
```

### Products

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/products/categories` | Get all product categories with counts |
| GET | `/products` | List products (paginated, filterable) |
| GET | `/products/{slug}` | Get single product with full details |

**Query Parameters (GET /products):**
- `category` - Filter by category (outdoor, indoor, transparent, posters, controllers)
- `pixel_pitch_min` - Minimum pixel pitch
- `pixel_pitch_max` - Maximum pixel pitch
- `brightness_min` - Minimum brightness (nits)
- `search` - Search in name, series, description
- `per_page` - Items per page (default: 12, max: 50)

### Solutions

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/solutions` | List all solutions |
| GET | `/solutions/{slug}` | Get solution with benefits, specs, products |

### Case Studies

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/case-studies` | List case studies (paginated) |
| GET | `/case-studies/{slug}` | Get case study with metrics, gallery |

**Query Parameters (GET /case-studies):**
- `industry` - Filter by industry
- `per_page` - Items per page

### Company

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/company/about` | Get company information |
| GET | `/company/settings` | Get site settings (logo, contact, social) |

### Contact

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/contact` | Submit contact/quote request |

**Request Body:**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "phone": "+1-555-123-4567",
  "company": "Acme Corp",
  "project_type": "Outdoor Advertising",
  "timeline": "1 – 3 months",
  "size_requirements": "3m x 2m display",
  "budget_range": "$10,000 - $25,000",
  "message": "Additional details..."
}
```

### Careers

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/jobs/filters` | Get available filter options |
| GET | `/jobs` | List job listings (filterable) |
| GET | `/jobs/{slug}` | Get job with full details |

**Query Parameters (GET /jobs):**
- `category` - Filter by category
- `location` - Filter by location
- `type` - Filter by employment type
- `search` - Search in title and description

### Job Applications

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/job-applications` | Submit job application |

**Request Body (multipart/form-data):**
```
job_id: "senior-developer"          // Optional - job slug
first_name: "John"                  // Required
last_name: "Doe"                    // Required
email: "john@example.com"           // Required
phone: "+1-555-123-4567"            // Optional
cover_letter: "I am excited..."     // Optional
resume: [PDF/DOC/DOCX file]         // Required (or linkedin_url)
linkedin_url: "https://linkedin..."  // Optional
portfolio_url: "https://portfolio..." // Optional
is_general: false                    // Optional - for general applications
```

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/user` | Get authenticated user (requires Bearer token) |

---

## 🗄️ Database Schema

### Core Tables

```
┌─────────────────────┐     ┌─────────────────────┐
│      products       │     │     solutions       │
├─────────────────────┤     ├─────────────────────┤
│ id                  │     │ id                  │
│ name                │     │ title               │
│ slug (unique)       │     │ slug (unique)       │
│ series              │     │ category            │
│ category            │     │ description         │
│ description         │     │ image               │
│ image               │     │ hero_image          │
│ environment         │     │ icon                │
│ pixel_pitch         │     │ is_active           │
│ brightness_min/max  │     └─────────────────────┘
│ cabinet_size        │
│ price               │     ┌─────────────────────┐
│ view_count          │     │    case_studies     │
│ is_active           │     ├─────────────────────┤
└─────────────────────┘     │ id                  │
                            │ title               │
┌─────────────────────┐     │ slug (unique)       │
│  product_features   │     │ client              │
├─────────────────────┤     │ industry            │
│ id                  │     │ location            │
│ product_id          │     │ description         │
│ icon                │     │ challenge           │
│ title               │     │ solution            │
│ description         │     │ results             │
│ order               │     │ gallery             │
└─────────────────────┘     │ completion_date     │
                            │ is_active           │
┌─────────────────────┐     └─────────────────────┘
│ product_applications│
├─────────────────────┤     ┌─────────────────────┐
│ id                  │     │   job_listings      │
│ product_id          │     ├─────────────────────┤
│ name                │     │ id                  │
│ order               │     │ title               │
└─────────────────────┘     │ slug (unique)       │
                            │ category            │
┌─────────────────────┐     │ location            │
│ product_specs       │     │ type                │
├─────────────────────┤     │ department          │
│ id                  │     │ description         │
│ product_id          │     │ responsibilities    │
│ label               │     │ requirements        │
│ value               │     │ benefits            │
│ order               │     │ salary_range        │
└─────────────────────┘     │ application_deadline│
                            │ is_remote           │
┌─────────────────────┐     │ is_urgent           │
│  contact_submissions│     │ is_active           │
├─────────────────────┤     └─────────────────────┘
│ id                  │
│ first_name          │     ┌─────────────────────┐
│ last_name           │     │  job_applications   │
│ email               │     ├─────────────────────┤
│ phone               │     │ id                  │
│ company             │     │ job_listing_id      │
│ project_type        │     │ first_name          │
│ timeline            │     │ last_name           │
│ size_requirements   │     │ email               │
│ budget_range        │     │ phone               │
│ message             │     │ cover_letter        │
│ status              │     │ resume_path         │
│ status_notes        │     │ resume_original_name│
│ submitted_at        │     │ linkedin_url        │
└─────────────────────┘     │ portfolio_url       │
                            │ is_general_application│
┌─────────────────────┐     │ status              │
│      settings       │     │ applied_at          │
├─────────────────────┤     └─────────────────────┘
│ id                  │
│ site_name           │     ┌─────────────────────┐
│ site_logo           │     │    company_info     │
│ site_favicon        │     ├─────────────────────┤
│ contact_phone       │     │ id                  │
│ contact_email       │     │ company_name        │
│ contact_address     │     │ tagline             │
│ social_linkedin     │     │ description         │
│ social_youtube      │     │ mission             │
│ social_twitter      │     │ vision              │
│ footer_about        │     │ values              │
│ footer_copyright    │     │ stats               │
└─────────────────────┘     │ team                │
                            │ certifications      │
                            │ headquarters        │
                            │ founded_year        │
                            └─────────────────────┘
```

---

## 📥 Installation

### Requirements

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js & NPM (for frontend assets)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/maxvision-backend.git
   cd maxvision-backend
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   
   Update `.env` with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=maxvision
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Configure mail settings** (for notifications)
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host
   MAIL_PORT=587
   MAIL_USERNAME=your-username
   MAIL_PASSWORD=your-password
   MAIL_FROM_ADDRESS="noreply@maxvisiondisplay.com"
   MAIL_FROM_NAME="Maxvision Display"
   ```

7. **Create admin user**
   ```bash
   php artisan db:seed --class=AdminUserSeeder
   ```

8. **Publish Filament assets**
   ```bash
   php artisan filament:assets
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

---

## ⚙️ Configuration

### Environment Variables

| Variable | Description |
|----------|-------------|
| `APP_URL` | Application URL |
| `DB_*` | Database connection settings |
| `MAIL_*` | Email configuration |
| `ADMIN_EMAIL` | Admin email for notifications |

### CORS Configuration

Update `config/cors.php` to allow your frontend domain:
```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:8080'],
'allowed_headers' => ['*'],
```

### Cache Configuration

The API uses caching for improved performance. Configure in `.env`:
```env
CACHE_DRIVER=file  # or 'redis' for production
```

---

## 🎛️ Admin Panel

### Access

Navigate to `/admin` after creating an admin user.

### Default Admin Credentials (after seeding)
```
Email: admin@maxvisiondisplay.com
Password: admin123
```
> ⚠️ Change the default password immediately in production!

### Available Resources

| Resource | Description |
|----------|-------------|
| Products | Manage LED display products |
| Solutions | Manage industry solutions |
| Case Studies | Manage customer success stories |
| Contact Submissions | View and manage inquiries |
| Job Listings | Post and manage jobs |
| Job Applications | Review applications |
| Settings | Site-wide settings |
| Company Info | Company information |

### Dashboard Widgets

- Contact submission statistics
- Popular products chart
- Products by category breakdown
- Recent contact submissions
- Stats overview

---

## 💻 Development

### Artisan Commands

```bash
# Clear all cache
php artisan optimize:clear

# Clear view cache
php artisan view:clear

# Seed sample data
php artisan db:seed --class=DevelopmentSeeder

# Backup seed data
php artisan seed:backup

# Restore seed data
php artisan seed:restore

# Optimize images
php artisan images:optimize
```

### Creating New API Endpoints

1. Create controller: `php artisan make:controller Api/YourController`
2. Add route in `routes/api.php`
3. Create form request for validation
4. Create API resource for response transformation

### Code Style

```bash
# Format code with Laravel Pint
./vendor/bin/pint
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter TestClassName
```

---

## 📚 API Documentation

### Postman Collection

Import the following files into Postman:
- `postman_collection.json` - API endpoints
- `postman_environment.json` - Environment variables

### OpenAPI/Swagger

The API follows RESTful conventions:
- All responses are JSON
- Errors return appropriate HTTP status codes
- Validation errors include field-specific messages

### Response Format

**Success Response:**
```json
{
  "data": { ... }
}
```

**Paginated Response:**
```json
{
  "data": [ ... ],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": { "current_page": 1, "total": 50 }
}
```

**Error Response:**
```json
{
  "message": "Error description",
  "errors": { "field": ["Error message"] }
}
```

---

## 📄 License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 👥 Authors

**Maxvision Display Inc. Development Team**

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📞 Support

For support, email support@maxvisiondisplay.com
