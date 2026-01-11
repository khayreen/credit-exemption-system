#!/bin/bash

# Laravel Permission Fix Script
# Run this anytime you get permission errors
# Usage: sudo bash fix-permissions.sh

echo "🔧 Fixing Laravel file permissions..."

# Change ownership to web server user
echo "1️⃣ Setting ownership to www-data..."
chown -R www-data:www-data /var/www/uitm-credit-system

# Set directory permissions (755)
echo "2️⃣ Setting directory permissions..."
find /var/www/uitm-credit-system -type d -exec chmod 755 {} \;

# Set file permissions (644)
echo "3️⃣ Setting file permissions..."
find /var/www/uitm-credit-system -type f -exec chmod 644 {} \;

# Storage and cache need write permissions (775)
echo "4️⃣ Setting write permissions for storage and cache..."
chmod -R 775 /var/www/uitm-credit-system/storage
chmod -R 775 /var/www/uitm-credit-system/bootstrap/cache

# Make scripts executable
echo "5️⃣ Making scripts executable..."
chmod +x /var/www/uitm-credit-system/fix-permissions.sh

# Clear and recache
echo "6️⃣ Clearing caches..."
cd /var/www/uitm-credit-system
php artisan config:cache
php artisan route:cache
php artisan view:clear
composer dump-autoload --no-dev --optimize

echo "✅ All permissions fixed!"
echo ""
echo "📊 Summary:"
echo "  - All files: 644 (rw-r--r--)"
echo "  - All directories: 755 (rwxr-xr-x)"
echo "  - Storage/cache: 775 (rwxrwxr-x)"
echo "  - Owner: www-data:www-data"
echo ""
echo "🚀 Your Laravel app should work now!"
