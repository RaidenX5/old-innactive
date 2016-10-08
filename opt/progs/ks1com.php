#!/usr/bin/php
<?php
	include( "/var/www/travian/dbclass.inc.php" );
	
	exec( "rm /opt/progs/map.json" );
	
	// request
	// http://ks1-com.travian.com/api/external.php?action=requestApiKey&email=spycat2002@gmail.com&siteName=innactivefinder&siteUrl=http://www.tristool.com/inactive.php&public=false
	exec( "wget 'http://ks1-com.travian.com/api/external.php?action=getMapData&privateApiKey=bd6d01de869fcad3081ff57c3b4e5e3a' -O /opt/progs/mapks1com.json", $ret, $out );
	
	if( $out != 0 )
	{
		echo "ERROR DOWNLOAD !\n";
		exit( 1 );
	}
	
	if( !file_exists( "/opt/progs/mapks1com.json" ) )
	{
		echo "ERROR NO FILE !\n";
		exit( 1 );
	}
	
	$jsonall = file_get_contents( '/opt/progs/mapks1com.json' );
	
	if( !$jsonall )
	{
		echo "ERROR EMPTY FILE !\n";
		exit( 1 );
	}
	
	$arr_fromjson = json_decode( $jsonall, true );
	
	if( is_null( $arr_fromjson ) )
	{
		echo "INVALID JSON !\n";
		exit( 1 );
	}
	
	$dbc = new dbdriver();
	$dbc->open_db();
	
	$dbc->do_sql( "DELETE FROM ks1com;" );
	$id = 1;
	for( $a = 0; $a < sizeof( $arr_fromjson['response']['players'] ); $a++ )
	{
		// echo "playerId : " . $arr_fromjson['response']['players'][$a]['playerId'] . "<br>";
		// echo "tribeId : " . $arr_fromjson['response']['players'][$a]['tribeId'] . "<br>";
		// echo "Name : " . $arr_fromjson['response']['players'][$a]['name'] . "<br>";
		for( $b = 0; $b < sizeof( $arr_fromjson['response']['players'][$a]['villages'] ); $b++ )
		{
			// echo "villageId : " . $arr_fromjson['response']['players'][$a]['villages'][$b]['villageId'] . "<br>";
			// echo "x : " . $arr_fromjson['response']['players'][$a]['villages'][$b]['x'] . "<br>";
			// echo "y : " . $arr_fromjson['response']['players'][$a]['villages'][$b]['y'] . "<br>";
			// echo "population : " . $arr_fromjson['response']['players'][$a]['villages'][$b]['population'] . "<br>";
			// echo "village name : " . $arr_fromjson['response']['players'][$a]['villages'][$b]['name'] . "<br>";

			$role = $arr_fromjson['response']['players'][$a]['role'];
			$treasures = $arr_fromjson['response']['players'][$a]['treasures'];
			$iscap = $arr_fromjson['response']['players'][$a]['villages'][$b]['isMainVillage'] == '' ? 0 : 1;
			$isCity = $arr_fromjson['response']['players'][$a]['villages'][$b]['isCity'] == '' ? 0 : 1;
			
			$dbc->do_sql( "INSERT INTO ks1com VALUES( " . $id++ . ", " . $arr_fromjson['response']['players'][$a]['villages'][$b]['x'] . ", " . $arr_fromjson['response']['players'][$a]['villages'][$b]['y'] . ", " . $arr_fromjson['response']['players'][$a]['tribeId'] . ", " . $arr_fromjson['response']['players'][$a]['villages'][$b]['villageId']  . ", '" . str_replace( "'", "''", $arr_fromjson['response']['players'][$a]['villages'][$b]['name'] ) . "', " .$arr_fromjson['response']['players'][$a]['playerId']  . ", '" . str_replace( "'", "''", $arr_fromjson['response']['players'][$a]['name'] ) . "', 0, '', " . $arr_fromjson['response']['players'][$a]['villages'][$b]['population']  . ", {$role}, {$treasures}, {$iscap}, {$isCity} )" );
		}
		//echo "<br>";
	}
	
	$dbc->do_sql( "SELECT * FROM populate_table( 'ks1com' );" );
	$dbc->close_db();
	
	exec( "echo `date` >> /opt/progs/lastrunningks1com"  );
?>
