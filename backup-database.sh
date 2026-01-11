#!/bin/bash

# UiTM Credit System - Automated Database Backup Script
# This script creates daily backups of the MySQL database

# Configuration
BACKUP_DIR="/var/backups/uitm-credit-system"
DB_NAME="uitm_credit_system"
DB_USER="uitm_user"
DB_PASSWORD="ewAB42H7Xu8IPWxAjtVxsy3CTTMxFoiv"
DB_HOST="127.0.0.1"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_FILE="${BACKUP_DIR}/backup_${DATE}.sql.gz"
LOG_FILE="${BACKUP_DIR}/backup.log"
DAYS_TO_KEEP=7

# Create backup directory if it doesn't exist
mkdir -p "$BACKUP_DIR"

# Log start
echo "========================================" >> "$LOG_FILE"
echo "Backup started at: $(date)" >> "$LOG_FILE"

# Perform the backup
if mysqldump -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" --no-tablespaces "$DB_NAME" | gzip > "$BACKUP_FILE"; then
    echo "✓ Backup successful: $BACKUP_FILE" >> "$LOG_FILE"

    # Get file size
    FILESIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    echo "  File size: $FILESIZE" >> "$LOG_FILE"

    # Delete backups older than DAYS_TO_KEEP days
    find "$BACKUP_DIR" -name "backup_*.sql.gz" -type f -mtime +$DAYS_TO_KEEP -delete
    echo "✓ Old backups cleaned (kept last $DAYS_TO_KEEP days)" >> "$LOG_FILE"

    # Count remaining backups
    BACKUP_COUNT=$(find "$BACKUP_DIR" -name "backup_*.sql.gz" -type f | wc -l)
    echo "  Total backups: $BACKUP_COUNT" >> "$LOG_FILE"

    exit 0
else
    echo "✗ Backup FAILED!" >> "$LOG_FILE"
    exit 1
fi

echo "Backup completed at: $(date)" >> "$LOG_FILE"
