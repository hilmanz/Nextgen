#!/bin/sh
#####################################
## Unblock User Locked - Status 9  ##
## Ver 0.1                         ##
## inong@kana.co.id                ##
## 15 August 2013                  ##
#####################################
##
## 20130815 : - Initial scripts
##
##
##

## Configuration

# Date format
TODAY=`date '+%Y-%m-%d'`
NOW=`date '+%s'`

# Difference Time
DIFF='300'

# Direktori dimana script ini disimpan
BASEDIR="/home/inong/beat/tools/cron"

# File-file konfigurasi
USER_LIST="$BASEDIR/logs/list_locked-account.txt"
LOCK_FILE="$BASEDIR/logs/lock_locked-account"

# Database
USERNAME='root'
PASSWORD='coppermine'
DATABASE='beat_web_2013'
HOST='117.54.1.106'

## Scripts

cd $BASEDIR

#--Cek lock file, kalau tidak ada buat dan jalankan script, kalau tidak ada mati--#
PROC=$(cat $LOCK_FILE)

if [ "$PROC" != "" ]; then
   exit 1
fi

echo "running" > $LOCK_FILE

#--Run Revenue Report H-8 compare with H-9--#
mysql -u $USERNAME -p$PASSWORD -h $HOST -D $DATABASE -Bse "select email from social_member where n_status = '9' and last_login is not null;" > $USER_LIST


for myUSER_LIST in `cat $USER_LIST`
        do
		LAST_LOGIN=$(mysql -u $USERNAME -p$PASSWORD -h $HOST -D $DATABASE -Bse "select unix_timestamp(last_login) from social_member where email = '$myUSER_LIST';")
		
		TIME_DIFF=`echo "($NOW-$LAST_LOGIN)" | bc`
		
		echo "$TIME_DIFF"
		
		if [ "$TIME_DIFF" -gt "$DIFF" ]; then
			mysql -u $USERNAME -p$PASSWORD -h $HOST -D $DATABASE -Bse "update social_member set n_status = '1', try_to_login = '0' where email = '$myUSER_LIST';"
		fi
done

##--Proses selesai, hapus status lock--##
cat /dev/null > $LOCK_FILE
