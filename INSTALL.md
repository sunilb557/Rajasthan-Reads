# Installation Guide

## Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Xpdf tools (for OCR) - Optional but recommended
- Node.js 16+ (for TailwindCSS build)

## Installation Steps

### 1. Database Setup

```bash
mysql -u root -p
CREATE DATABASE rajasthan_reads CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Import the schema:
```bash
mysql -u root -p rajasthan_reads < database_schema.sql
mysql -u root -p rajasthan_reads < database_schema_additions.sql
```

### 2. Configuration

Copy `config.php` and update:
- Database credentials
- BASE_URL (your domain)
- Admin email

### 3. Directory Permissions

```bash
chmod 755 storage/
chmod 755 storage/logs/
chmod 755 public/assets/uploads/
mkdir -p storage/logs storage/cache
chmod 755 storage/logs storage/cache
```

### 4. Install Dependencies

```bash
npm install
npm run build
```

### 5. Queue Worker Setup

For background processing (OCR, image processing), set up the queue worker:

**Option A - Cron (recommended for shared hosting):**
```bash
*/5 * * * * cd /path/to/rajasthan_reads && php queue-worker.php --limit=5 >> storage/logs/queue.log 2>&1
```

**Option B - Supervisor (for VPS/dedicated servers):**
Create `/etc/supervisor/conf.d/rajasthanreads.conf`:
```ini
[program:rajasthanreads-worker]
command=php /path/to/rajasthan_reads/queue-worker.php --daemon --limit=10
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/rajasthan_reads/storage/logs/queue.log
```

### 6. OCR Setup (Optional)

Install Xpdf tools for text extraction:

**Ubuntu/Debian:**
```bash
sudo apt-get install poppler-utils
```

**macOS:**
```bash
brew install poppler
```

**Windows:**
Download from https://www.xpdfreader.com/download.html and add to PATH

### 7. Web Server Configuration

**Apache (.htaccess already included):**
- Ensure mod_rewrite is enabled
- DocumentRoot should point to `public/` folder

**Nginx:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/rajasthan_reads/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 8. First Admin User

Register a user via the web interface, then run:
```sql
UPDATE users SET role = 'admin' WHERE email = 'your@email.com';
```

### 9. Security Checklist

- [ ] Change default admin credentials
- [ ] Enable HTTPS
- [ ] Set up firewall rules
- [ ] Configure backup script
- [ ] Review CSP headers in Security.php
- [ ] Enable rate limiting in production

### 10. Production Deployment

Build production assets:
```bash
npm run build
```

Set PHP settings in `.htaccess` or `php.ini`:
```
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
```

## Maintenance

### Daily Tasks (Automated via Cron)
```bash
# Process queue jobs
php queue-worker.php --limit=20

# Clean old sessions
php queue-worker.php --clean
```

### Weekly Tasks
```bash
# Clean old completed jobs
php queue-worker.php --clean --days=7

# Backup database
mysqldump -u root -p rajasthan_reads > backup_$(date +%Y%m%d).sql
```

### Monthly Tasks
- Review analytics dashboard
- Check storage usage
- Update dependencies (`npm update`, `composer update`)

## Troubleshooting

### PDF Not Loading in Reader
- Check PDF.js files are in `public/assets/pdfjs/`
- Verify PDF file exists and is readable
- Check browser console for errors

### Queue Jobs Not Processing
- Verify `storage/logs/queue.log` for errors
- Check database connection in config
- Ensure PHP CLI has same extensions as web PHP

### OCR Not Working
- Verify Xpdf tools installed: `which pdftotext`
- Check job queue status in admin dashboard
- Review `storage/logs/security.log` for OCR errors

### Image Upload Fails
- Check directory permissions on `public/assets/uploads/`
- Verify GD extension installed: `php -m | grep gd`
- Check PHP upload limits in configuration

## Support

For issues and questions:
- Check the logs in `storage/logs/`
- Review the ROADMAP.md for planned features
- Consult the README.md for feature overview
