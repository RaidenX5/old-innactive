#!/bin/sh

PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

cd /opt/progs
rm map.sql
wget http://ts7.travian.co.id/map.sql

if [ "$?" != "0" ]
then
	exit 1
fi

sed -i "s/\`x_world\`/ts7coid/g" map.sql

PGPASSWORD=123456 psql tr -U www-data -c "DELETE FROM ts7coid;";

PGPASSWORD=123456 psql tr -U www-data -f map.sql

PGPASSWORD=123456 psql tr -U www-data -c "SELECT * FROM populate_table( 'ts7coid' );"

echo `date` >> lastrunningts7coid

exit 0
