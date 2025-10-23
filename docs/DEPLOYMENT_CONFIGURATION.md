# Deployment & Configuration Documentation

## Overview
This document provides comprehensive deployment and configuration guidelines for the Gateway Dashboard application, including environment setup, server configuration, and production deployment procedures.

## Table of Contents
1. [Environment Setup](#environment-setup)
2. [Server Requirements](#server-requirements)
3. [Installation Guide](#installation-guide)
4. [Configuration Files](#configuration-files)
5. [Database Setup](#database-setup)
6. [Redis Configuration](#redis-configuration)
7. [Web Server Configuration](#web-server-configuration)
8. [SSL/TLS Setup](#ssltls-setup)
9. [Production Deployment](#production-deployment)
10. [Monitoring & Logging](#monitoring--logging)
11. [Backup & Recovery](#backup--recovery)
12. [Security Configuration](#security-configuration)
13. [Performance Optimization](#performance-optimization)
14. [Troubleshooting](#troubleshooting)

---

## Environment Setup

### Development Environment
```bash
# Required software versions
PHP >= 8.1
Laravel >= 10.0
PostgreSQL >= 13.0
Redis >= 6.0
Node.js >= 16.0
Composer >= 2.0
NPM >= 8.0
```

### Production Environment
```bash
# Recommended server specifications
CPU: 4+ cores
RAM: 8GB+ (16GB recommended)
Storage: 100GB+ SSD
OS: Ubuntu 20.04 LTS or CentOS 8+
Web Server: Nginx 1.18+
Database: PostgreSQL 13+
Cache: Redis 6.0+
```

---

## Server Requirements

### PHP Requirements
```ini
; php.ini configuration
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
post_max_size = 100M
upload_max_filesize = 100M
max_file_uploads = 20
date.timezone = UTC
extension=pdo_pgsql
extension=redis
extension=gd
extension=zip
extension=mbstring
extension=openssl
extension=curl
extension=json
extension=bcmath
```

### PHP Extensions
```bash
# Required PHP extensions
php-pgsql
php-redis
php-gd
php-zip
php-mbstring
php-openssl
php-curl
php-json
php-bcmath
php-xml
php-tokenizer
php-fileinfo
php-dom
php-simplexml
php-xmlwriter
php-xmlreader
```

### System Dependencies
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install -y nginx postgresql postgresql-contrib redis-server
sudo apt install -y php8.1 php8.1-fpm php8.1-pgsql php8.1-redis php8.1-gd
sudo apt install -y php8.1-zip php8.1-mbstring php8.1-openssl php8.1-curl
sudo apt install -y php8.1-json php8.1-bcmath php8.1-xml php8.1-tokenizer
sudo apt install -y php8.1-fileinfo php8.1-dom php8.1-simplexml
sudo apt install -y composer nodejs npm git

# CentOS/RHEL
sudo yum update
sudo yum install -y nginx postgresql postgresql-server redis
sudo yum install -y php81 php81-php-fpm php81-php-pgsql php81-php-redis
sudo yum install -y php81-php-gd php81-php-zip php81-php-mbstring
sudo yum install -y php81-php-openssl php81-php-curl php81-php-json
sudo yum install -y php81-php-bcmath php81-php-xml php81-php-tokenizer
sudo yum install -y php81-php-fileinfo php81-php-dom php81-php-simplexml
sudo yum install -y composer nodejs npm git
```

---

## Installation Guide

### 1. Clone Repository
```bash
# Clone the repository
git clone <repository-url> /var/www/gateway_dashboard
cd /var/www/gateway_dashboard

# Set proper permissions
sudo chown -R www-data:www-data /var/www/gateway_dashboard
sudo chmod -R 755 /var/www/gateway_dashboard
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set proper permissions
sudo chmod 600 .env
sudo chown www-data:www-data .env
```

### 4. Database Setup
```bash
# Create database
sudo -u postgres createdb gateway

# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed

# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 5. Cache Configuration
```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Configuration Files

### .env Configuration
```env
# Application
APP_NAME="Gateway Dashboard"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5433
DB_DATABASE=gateway
DB_USERNAME=gateway_user
DB_PASSWORD=your-secure-password

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Session
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

# Security
BCRYPT_ROUNDS=12
HASH_VERIFY=true
```

### Nginx Configuration
```nginx
# /etc/nginx/sites-available/gateway_dashboard
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com www.your-domain.com;
    root /var/www/gateway_dashboard/public;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /etc/ssl/certs/your-domain.crt;
    ssl_certificate_key /etc/ssl/private/your-domain.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript application/json;

    # Main location
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /(storage|bootstrap/cache) {
        deny all;
    }

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
    limit_req_zone $binary_remote_addr zone=api:10m rate=100r/m;

    location /login {
        limit_req zone=login burst=5 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }

    location /api {
        limit_req zone=api burst=20 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

### PHP-FPM Configuration
```ini
; /etc/php/8.1/fpm/pool.d/gateway_dashboard.conf
[gateway_dashboard]
user = www-data
group = www-data
listen = /var/run/php/php8.1-fpm-gateway.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 1000

php_admin_value[error_log] = /var/log/php8.1-fpm-gateway.log
php_admin_flag[log_errors] = on
php_admin_value[memory_limit] = 512M
php_admin_value[max_execution_time] = 300
php_admin_value[max_input_time] = 300
php_admin_value[post_max_size] = 100M
php_admin_value[upload_max_filesize] = 100M
```

---

## Database Setup

### PostgreSQL Configuration
```bash
# Install PostgreSQL
sudo apt install postgresql postgresql-contrib

# Start PostgreSQL service
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Create database and user
sudo -u postgres psql
```

```sql
-- Create database
CREATE DATABASE gateway;

-- Create user
CREATE USER gateway_user WITH PASSWORD 'your-secure-password';

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE gateway TO gateway_user;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO gateway_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO gateway_user;

-- Set default privileges
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO gateway_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO gateway_user;

-- Exit PostgreSQL
\q
```

### PostgreSQL Configuration
```bash
# Edit PostgreSQL configuration
sudo nano /etc/postgresql/13/main/postgresql.conf
```

```ini
# postgresql.conf
listen_addresses = 'localhost'
port = 5433
max_connections = 100
shared_buffers = 256MB
effective_cache_size = 1GB
maintenance_work_mem = 64MB
checkpoint_completion_target = 0.9
wal_buffers = 16MB
default_statistics_target = 100
random_page_cost = 1.1
effective_io_concurrency = 200
work_mem = 4MB
min_wal_size = 1GB
max_wal_size = 4GB
```

```bash
# Edit PostgreSQL authentication
sudo nano /etc/postgresql/13/main/pg_hba.conf
```

```
# pg_hba.conf
local   all             postgres                                peer
local   all             all                                     md5
host    all             all             127.0.0.1/32            md5
host    all             all             ::1/128                 md5
```

```bash
# Restart PostgreSQL
sudo systemctl restart postgresql
```

---

## Redis Configuration

### Redis Installation
```bash
# Install Redis
sudo apt install redis-server

# Start Redis service
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

### Redis Configuration
```bash
# Edit Redis configuration
sudo nano /etc/redis/redis.conf
```

```ini
# redis.conf
bind 127.0.0.1
port 6379
timeout 0
tcp-keepalive 300
tcp-backlog 511
databases 16
save 900 1
save 300 10
save 60 10000
stop-writes-on-bgsave-error yes
rdbcompression yes
rdbchecksum yes
dbfilename dump.rdb
dir /var/lib/redis
maxmemory 2gb
maxmemory-policy allkeys-lru
appendonly yes
appendfilename "appendonly.aof"
appendfsync everysec
no-appendfsync-on-rewrite no
auto-aof-rewrite-percentage 100
auto-aof-rewrite-min-size 64mb
```

```bash
# Restart Redis
sudo systemctl restart redis-server
```

---

## Web Server Configuration

### Nginx Installation
```bash
# Install Nginx
sudo apt install nginx

# Start Nginx service
sudo systemctl start nginx
sudo systemctl enable nginx
```

### Enable Site
```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/gateway_dashboard /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

### Firewall Configuration
```bash
# Configure UFW firewall
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

---

## SSL/TLS Setup

### Let's Encrypt SSL Certificate
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Test automatic renewal
sudo certbot renew --dry-run

# Set up automatic renewal
sudo crontab -e
```

```bash
# Add to crontab
0 12 * * * /usr/bin/certbot renew --quiet
```

### Custom SSL Certificate
```bash
# Copy certificate files
sudo cp your-domain.crt /etc/ssl/certs/
sudo cp your-domain.key /etc/ssl/private/
sudo chmod 600 /etc/ssl/private/your-domain.key
sudo chmod 644 /etc/ssl/certs/your-domain.crt
```

---

## Production Deployment

### Deployment Script
```bash
#!/bin/bash
# deploy.sh

# Set variables
APP_DIR="/var/www/gateway_dashboard"
BACKUP_DIR="/var/backups/gateway_dashboard"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup
echo "Creating backup..."
mkdir -p $BACKUP_DIR
pg_dump -h localhost -U gateway_user -d gateway > $BACKUP_DIR/database_$DATE.sql
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C $APP_DIR storage bootstrap/cache

# Pull latest changes
echo "Pulling latest changes..."
cd $APP_DIR
git pull origin main

# Install dependencies
echo "Installing dependencies..."
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Clear caches
echo "Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "Setting permissions..."
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

# Restart services
echo "Restarting services..."
sudo systemctl restart php8.1-fpm
sudo systemctl restart nginx
sudo systemctl restart redis-server

echo "Deployment completed successfully!"
```

### Queue Worker Setup
```bash
# Create systemd service for queue worker
sudo nano /etc/systemd/system/gateway-queue.service
```

```ini
[Unit]
Description=Gateway Dashboard Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
WorkingDirectory=/var/www/gateway_dashboard
ExecStart=/usr/bin/php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
```

```bash
# Enable and start queue worker
sudo systemctl enable gateway-queue
sudo systemctl start gateway-queue
```

---

## Monitoring & Logging

### Log Configuration
```bash
# Create log directories
sudo mkdir -p /var/log/gateway_dashboard
sudo chown www-data:www-data /var/log/gateway_dashboard
```

### Logrotate Configuration
```bash
# Create logrotate configuration
sudo nano /etc/logrotate.d/gateway_dashboard
```

```
/var/log/gateway_dashboard/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        sudo systemctl reload php8.1-fpm
    endscript
}
```

### Monitoring Script
```bash
#!/bin/bash
# monitor.sh

# Check services
echo "Checking services..."
systemctl is-active --quiet nginx && echo "Nginx: OK" || echo "Nginx: FAIL"
systemctl is-active --quiet php8.1-fpm && echo "PHP-FPM: OK" || echo "PHP-FPM: FAIL"
systemctl is-active --quiet postgresql && echo "PostgreSQL: OK" || echo "PostgreSQL: FAIL"
systemctl is-active --quiet redis-server && echo "Redis: OK" || echo "Redis: FAIL"

# Check disk space
echo "Checking disk space..."
df -h | grep -E "(Filesystem|/dev/)"

# Check memory usage
echo "Checking memory usage..."
free -h

# Check database connections
echo "Checking database connections..."
psql -h localhost -U gateway_user -d gateway -c "SELECT count(*) FROM pg_stat_activity;"

# Check Redis connections
echo "Checking Redis connections..."
redis-cli info clients | grep connected_clients
```

---

## Backup & Recovery

### Backup Script
```bash
#!/bin/bash
# backup.sh

# Set variables
APP_DIR="/var/www/gateway_dashboard"
BACKUP_DIR="/var/backups/gateway_dashboard"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
echo "Backing up database..."
pg_dump -h localhost -U gateway_user -d gateway > $BACKUP_DIR/database_$DATE.sql

# Files backup
echo "Backing up files..."
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C $APP_DIR storage bootstrap/cache

# Redis backup
echo "Backing up Redis..."
redis-cli BGSAVE
cp /var/lib/redis/dump.rdb $BACKUP_DIR/redis_$DATE.rdb

# Cleanup old backups
echo "Cleaning up old backups..."
find $BACKUP_DIR -name "*.sql" -mtime +$RETENTION_DAYS -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +$RETENTION_DAYS -delete
find $BACKUP_DIR -name "*.rdb" -mtime +$RETENTION_DAYS -delete

echo "Backup completed: $DATE"
```

### Recovery Script
```bash
#!/bin/bash
# restore.sh

# Set variables
BACKUP_DIR="/var/backups/gateway_dashboard"
APP_DIR="/var/www/gateway_dashboard"
BACKUP_DATE=$1

if [ -z "$BACKUP_DATE" ]; then
    echo "Usage: $0 <backup_date>"
    echo "Available backups:"
    ls -la $BACKUP_DIR/*.sql | awk '{print $9}' | sed 's/.*database_//' | sed 's/\.sql$//'
    exit 1
fi

# Restore database
echo "Restoring database..."
psql -h localhost -U gateway_user -d gateway < $BACKUP_DIR/database_$BACKUP_DATE.sql

# Restore files
echo "Restoring files..."
tar -xzf $BACKUP_DIR/files_$BACKUP_DATE.tar.gz -C $APP_DIR

# Restore Redis
echo "Restoring Redis..."
sudo systemctl stop redis-server
cp $BACKUP_DIR/redis_$BACKUP_DATE.rdb /var/lib/redis/dump.rdb
sudo systemctl start redis-server

echo "Recovery completed: $BACKUP_DATE"
```

---

## Security Configuration

### Security Headers
```nginx
# Add to Nginx configuration
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "no-referrer-when-downgrade" always;
add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
```

### File Permissions
```bash
# Set proper file permissions
sudo chown -R www-data:www-data /var/www/gateway_dashboard
sudo chmod -R 755 /var/www/gateway_dashboard
sudo chmod -R 775 /var/www/gateway_dashboard/storage
sudo chmod -R 775 /var/www/gateway_dashboard/bootstrap/cache
sudo chmod 600 /var/www/gateway_dashboard/.env
```

### Firewall Rules
```bash
# Configure UFW firewall
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

---

## Performance Optimization

### PHP-FPM Optimization
```ini
; /etc/php/8.1/fpm/pool.d/gateway_dashboard.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 1000
```

### Nginx Optimization
```nginx
# Add to Nginx configuration
worker_processes auto;
worker_connections 1024;
keepalive_timeout 65;
client_max_body_size 100M;
```

### Database Optimization
```sql
-- PostgreSQL optimization
ALTER SYSTEM SET shared_buffers = '256MB';
ALTER SYSTEM SET effective_cache_size = '1GB';
ALTER SYSTEM SET maintenance_work_mem = '64MB';
ALTER SYSTEM SET checkpoint_completion_target = 0.9;
ALTER SYSTEM SET wal_buffers = '16MB';
ALTER SYSTEM SET default_statistics_target = 100;
ALTER SYSTEM SET random_page_cost = 1.1;
ALTER SYSTEM SET effective_io_concurrency = 200;
ALTER SYSTEM SET work_mem = '4MB';
ALTER SYSTEM SET min_wal_size = '1GB';
ALTER SYSTEM SET max_wal_size = '4GB';
```

---

## Troubleshooting

### Common Issues

#### 1. Permission Issues
```bash
# Fix file permissions
sudo chown -R www-data:www-data /var/www/gateway_dashboard
sudo chmod -R 755 /var/www/gateway_dashboard
sudo chmod -R 775 /var/www/gateway_dashboard/storage
sudo chmod -R 775 /var/www/gateway_dashboard/bootstrap/cache
```

#### 2. Database Connection Issues
```bash
# Check PostgreSQL status
sudo systemctl status postgresql

# Check database connection
psql -h localhost -U gateway_user -d gateway

# Check database configuration
sudo nano /etc/postgresql/13/main/pg_hba.conf
```

#### 3. Redis Connection Issues
```bash
# Check Redis status
sudo systemctl status redis-server

# Check Redis connection
redis-cli ping

# Check Redis configuration
sudo nano /etc/redis/redis.conf
```

#### 4. Nginx Issues
```bash
# Check Nginx status
sudo systemctl status nginx

# Test Nginx configuration
sudo nginx -t

# Check Nginx error logs
sudo tail -f /var/log/nginx/error.log
```

#### 5. PHP-FPM Issues
```bash
# Check PHP-FPM status
sudo systemctl status php8.1-fpm

# Check PHP-FPM error logs
sudo tail -f /var/log/php8.1-fpm.log
```

### Log Files
```bash
# Application logs
tail -f /var/www/gateway_dashboard/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# PHP-FPM logs
tail -f /var/log/php8.1-fpm.log

# PostgreSQL logs
tail -f /var/log/postgresql/postgresql-13-main.log

# Redis logs
tail -f /var/log/redis/redis-server.log
```

### Health Check Script
```bash
#!/bin/bash
# health_check.sh

echo "=== Gateway Dashboard Health Check ==="
echo "Date: $(date)"
echo ""

# Check services
echo "1. Checking services..."
services=("nginx" "php8.1-fpm" "postgresql" "redis-server")
for service in "${services[@]}"; do
    if systemctl is-active --quiet $service; then
        echo "   ✓ $service: Running"
    else
        echo "   ✗ $service: Not running"
    fi
done

echo ""

# Check disk space
echo "2. Checking disk space..."
df -h | grep -E "(Filesystem|/dev/)"

echo ""

# Check memory usage
echo "3. Checking memory usage..."
free -h

echo ""

# Check database connection
echo "4. Checking database connection..."
if psql -h localhost -U gateway_user -d gateway -c "SELECT 1;" > /dev/null 2>&1; then
    echo "   ✓ Database: Connected"
else
    echo "   ✗ Database: Connection failed"
fi

echo ""

# Check Redis connection
echo "5. Checking Redis connection..."
if redis-cli ping > /dev/null 2>&1; then
    echo "   ✓ Redis: Connected"
else
    echo "   ✗ Redis: Connection failed"
fi

echo ""

# Check application
echo "6. Checking application..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost | grep -q "200"; then
    echo "   ✓ Application: Responding"
else
    echo "   ✗ Application: Not responding"
fi

echo ""
echo "=== Health Check Complete ==="
```

---

This comprehensive deployment and configuration documentation covers all aspects of setting up, configuring, and maintaining the Gateway Dashboard application in a production environment.
