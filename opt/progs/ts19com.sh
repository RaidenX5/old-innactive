#!/bin/sh

PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

cd /opt/progs
rm map.sql
wget http://ts19.travian.com/map.sql

if [ "$?" != "0" ]
then
	exit 1
fi

sed -i "s/\`x_world\`/ts19com/g" map.sql

PGPASSWORD=123456 psql tr -U www-data -c "DELETE FROM ts19com;";

PGPASSWORD=123456 psql tr -U www-data -f map.sql

PGPASSWORD=123456 psql tr -U www-data -c "SELECT * FROM populate_table( 'ts19com' );"

echo `date` >> lastrunningts19com

exit 0
