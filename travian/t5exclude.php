<?
	include( "dbclass.inc.php" );
	
	$dbc = new dbdriver();
	$dbc->open_db();
	
	if( $_POST["getit"] )
	{
		$ex = $_POST["ex"];
		$server = $_POST["server"];
		$each = explode( "()", $ex );
		
		for( $a = 0; $a < sizeof( $each ); $a++ )
		{
			$dbc->do_sql( "INSERT INTO " . $server . "_ex VALUES( '" . trim( $each[$a] ) . "' )" );
		}
	}
?>

<html>
	<head>
		<meta charset="UTF-8">
		<script>
		</script>
	</head>
	<body>
		<form name="form1" method="POST" action="t5exclude.php">
			<input type="text" value="" name="ex" placeholder="exclude coord"></input>
			<input type="text" value="" name="server" placeholder="server"></input>
			<input type="submit" value="Save" name="getit" style="width:70px">
		</form>
	</body>
</html>
<?
	$dbc->close_db();
?>