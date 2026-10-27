# RajasthanReads

A modern, SEO-first digital cultural library platform for Rajasthan heritage and literature.

## Features

- **MVC Architecture** - Clean, organized PHP 8+ code structure
- **User Authentication** - Login, register, logout with CSRF protection
- **Book Management** - Upload, organize, and read PDFs online
- **Online PDF Reader** - PDF.js integration with progress tracking and bookmarks
- **Admin Dashboard** - Full CRUD for books, authors, categories
- **SEO Optimized** - Meta tags, Schema.org markup, sitemap.xml, robots.txt
- **PWA Support** - Installable web app with offline caching
- **Responsive Design** - Mobile-first with Rajasthan cultural theme

## Installation

### 1. Database Setup

```sql
-- Import the schema
mysql -u root -p rajasthan_reads < database_schema.sql
```

Or run the SQL file in phpMyAdmin.

### 2. Configuration

Edit `config.php` if needed:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'rajasthan_reads');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Create Admin User

```sql
INSERT INTO users (name, email, password_hash, role, status)
VALUES (
    'Admin',
    'admin@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'admin',
    'active'
);
```

Default admin credentials:
- Email: `admin@example.com`
- Password: `password`

### 4. PDF.js Setup (Required for online reader)

Download PDF.js from https://mozilla.github.io/pdf.js/getting_started/#download

Copy these files to `public/assets/pdfjs/`:
- `pdf.min.js`
- `pdf.worker.min.js`

### 5. Web Server Setup

**Apache:** Point DocumentRoot to `public/` folder

Or use the included `.htaccess` in `public/`:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

## Directory Structure

```
rajasthan_reads/
├── app/
│   ├── controllers/      # Controllers (Home, Book, Auth, Admin, etc.)
│   ├── core/              # Core classes (Controller, Model, View, Router, Database)
│   ├── helpers/           # Helper classes (CSRF)
│   ├── models/            # Models (User, Book, Author, Category)
│   └── views/             # View templates
│       ├── admin/
│       ├── auth/
│       ├── books/
│       ├── home/
│       ├── layouts/
│       └── partials/
├── config/                # Configuration files
├── public/                # Web root
│   ├── assets/
│   │   ├── css/          # Stylesheets
│   │   ├── images/       # Images, favicons, PWA icons
│   │   ├── js/           # JavaScript files
│   │   └── pdfjs/        # PDF.js library files
│   ├── index.php        # Front controller
│   ├── manifest.json    # PWA manifest
│   ├── robots.txt       # SEO robots file
│   └── sw.js            # Service Worker
├── routes/
│   └── web.php           # Route definitions
├── storage/              # File storage (uploads, logs)
├── config.php            # Main configuration
└── database_schema.sql   # Database schema
```

## Usage

### Public Pages
- `/` - Homepage
- `/books` - Browse all books
- `/books/{slug}` - View single book
- `/reader/{slug}` - Read book online with PDF.js
- `/authors` - Browse authors
- `/authors/{slug}` - View author details
- `/categories` - Browse categories
- `/categories/{slug}` - View category details

### Authentication
- `/login` - Login page
- `/register` - Registration page

### Admin Panel (requires admin role)
- `/admin/books` - Manage books
- `/admin/books/create` - Add new book
- `/admin/books/{id}/edit` - Edit book

### SEO/PWA
- `/sitemap.xml` - Dynamic XML sitemap
- `/robots.txt` - Search engine directives
- `/manifest.json` - PWA manifest

## API Endpoints (for reader)
- `POST /api/reader/{slug}/progress` - Save reading progress
- `POST /api/reader/{slug}/bookmark` - Save bookmark

## Technical Stack

- **Backend:** PHP 8+, MySQL, PDO
- **Frontend:** Vanilla CSS (Rajasthan theme), minimal JavaScript
- **PDF:** PDF.js (Mozilla)
- **Architecture:** MVC pattern
- **Security:** CSRF tokens, prepared statements, password hashing

## Customization

### Colors
Edit CSS variables in `public/assets/css/style.css`:
```css
:root {
    --primary-700: #c2410c;  /* Main Rajasthan orange */
    --primary-50: #fff7ed;   /* Sand background */
}
```

### SEO
Edit meta defaults in `config.php`:
```php
define('DEFAULT_TITLE', SITE_NAME . ' - ' . SITE_TAGLINE);
define('DEFAULT_DESCRIPTION', 'Your custom description');
define('DEFAULT_KEYWORDS', 'your, keywords, here');
```

## License

MIT License - Open source for Rajasthan cultural preservation.

## Credits

- Built for Rajasthan cultural heritage
- PDF.js by Mozilla
- Inspired by the rich literature and traditions of Rajasthan
