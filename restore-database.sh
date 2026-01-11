#!/bin/bash

# UiTM Credit System - Database Restore Script
# Usage: ./restore-database.sh [backup-file.sql.gz]

# Configuration
BACKUP_DIR="/var/backups/uitm-credit-system"
DB_NAME="uitm_credit_system"
DB_USER="uitm_user"
DB_PASSWORD="ewAB42H7Xu8IPWxAjtVxsy3CTTMxFoiv"
DB_HOST="127.0.0.1"

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "=========================================="
echo "UiTM Credit System - Database Restore"
echo "=========================================="
echo ""

# Check if backup file is provided
if [ -z "$1" ]; then
    echo -e "${YELLOW}Available backups:${NC}"
    echo ""
    ls -lh "$BACKUP_DIR"/backup_*.sql.gz | awk '{print $9, "(" $5 ")", $6, $7, $8}'
    echo ""
    echo -e "${YELLOW}Usage:${NC} $0 /path/to/backup_file.sql.gz"
    echo ""
    echo "Example:"
    echo "  $0 $BACKUP_DIR/backup_2026-01-07_20-30-15.sql.gz"
    exit 1
fi

BACKUP_FILE="$1"

# Check if file exists
if [ ! -f "$BACKUP_FILE" ]; then
    echo -e "${RED}✗ Error: Backup file not found: $BACKUP_FILE${NC}"
    exit 1
fi

# Confirm restore
echo -e "${YELLOW}WARNING: This will replace ALL current database data!${NC}"
echo "Backup file: $BACKUP_FILE"
echo ""
read -p "Are you sure you want to restore? (yes/no): " CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo -e "${YELLOW}Restore cancelled.${NC}"
    exit 0
fi

echo ""
echo "Restoring database from backup..."

# Perform the restore
if gunzip < "$BACKUP_FILE" | mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME"; then
    echo ""
    echo -e "${GREEN}✓ Database restored successfully!${NC}"
    echo "Restored from: $BACKUP_FILE"
    exit 0
else
    echo ""
    echo -e "${RED}✗ Database restore FAILED!${NC}"
    echo "Please check the error messages above."
    exit 1
fi
