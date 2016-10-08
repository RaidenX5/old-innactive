rm /opt/progs/backup/lastbackup.cpy
PGPASSWORD=123456 pg_dump tr -Fc -f /opt/progs/backup/lastbackup.cpy -U www-data
/opt/progs/ks1com.php
sleep 5
/opt/progs/ts7coid.sh
sleep 5
/opt/progs/ts19com.sh
sleep 5
exit 0
