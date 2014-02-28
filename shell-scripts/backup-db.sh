#!/bin/sh
MUSER="lat_church"
MPASS="password1"
MDB="latvian_church"

# Detect paths
#MYSQL=$(which mysql)
MYSQLDUMP=$(which mysqldump)
#AWK=$(which awk)
#GREP=$(which grep)

echo "*****backing up into latvian_church_date.sql******"
suffix=$(date "+%Y-%m-%d")
$MYSQLDUMP -u $MUSER -p$MPASS $MDB>db_backup/dbbackup_latvian_church_$suffix.sql