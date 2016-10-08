<?php
	include( "dbclass.inc.php" );
	
	$dbc = new dbdriver();
	$dbc->open_db();
	
	if( $_POST["getit"] )
	{
		$x = $_POST["xcoord"];
		$y = $_POST["ycoord"];
		$distance = $_POST["dista"];
		$exclude = $_POST["exc"];
		$tbl = $_POST["tbl"];
		$days = $_POST["days"];
		
		$query = $dbc->get_innactivet5( $x, $y, $distance, $exclude, $tbl, $days );
		$lastrun = exec( "cat /opt/progs/lastrunning" . $tbl );
	}
	else
	{
		$lastrun = "";
		$days = 5;
	}
	
	?>
<html>
	<head>
		<meta charset="UTF-8">
		<script>
			function load()
			{
				if( "<?php echo$dbc->rowcount?>" != "0" )
				{
					document.getElementById( "table" ).style.display = "inline";
					document.getElementById( "list" ).style.display = "inline";
				}
			}
		</script>
	</head>
	<body onload="load();">
		<form name="FormItem" method="POST" action="innactivet5.php">
		<div id="input" style="display:inline">
			x:<input type="input" name="xcoord" value="<?php echo$x?>" style="font-size:10pt;height:24px;width:50px;">
			y:<input type="input" name="ycoord"  value="<?php echo$y?>" style="font-size:10pt;height:24px;width:50px;">
			dist:<input type="input" name="dista"  value="<?php echo$distance?>" style="font-size:10pt;height:24px;width:50px;">
			exclude:<input type="input" name="exc"  value="<?php echo$exclude?>" style="font-size:10pt;height:24px;width:50px;">
			table:<input type="input" name="tbl"  value="<?php echo$tbl?>" style="font-size:10pt;height:24px;width:50px;">
			days:<input type="input" name="days"  value="<?php echo$days?>" style="font-size:10pt;height:24px;width:50px;">
			<input type="submit" value="getit" name="getit" style="width:70px">
			Last update : <?php echo$lastrun;?>
			Row : <?php echo$dbc->rowcount;?>
		</div>
		<div id="table" style="display:none">
			<table border="1">
				<tr>
					<td>
						distance
					</td>
					<td>
						x|y
					</td>
					<td>
						tribe
					</td>
					<td>
						village
					</td>
					<td>
						name
					</td>
					<td>
						ally
					</td>
					<td>
						pop
					</td>
					<td>
						role
					</td>
					<td>
						treasure
					</td>
					<td>
						capital
					</td>
					<td>
						city
					</td>
				</tr>
			
	<?php
	
	while( $arr = pg_fetch_array( $query ) )
	{
		$stringlist = $stringlist . "()" . $arr[2] . "|" . $arr[3];
		?>
				<tr>
					<td>
						<?php echo$arr[0]?>
					</td>
					<td>
						<?php echo$arr[2] . "|" . $arr[3]?>
					</td>
					<td>
						<?php echo$arr[4]?>
					</td>
					<td>
						<?php echo$arr[6]?>
					</td>
					<td>
						<?php echo$arr[8]?>
					</td>
					<td>
						<?php echo$arr[10]?>
					</td>
					<td>
						<?php echo$arr[11]?>
					</td>
					<td>
						<?php echo$arr[12]?>
					</td>
					<td>
						<?php echo$arr[13]?>
					</td>
					<td>
						<?php echo$arr[14]?>
					</td>
					<td>
						<?php echo$arr[15]?>
					</td>
				</tr>
		<?php
	}

	$dbc->close_db();
?>
			</table>
		</div>
		<div id="list" style="display:none">
			<?php echosubstr($stringlist,2)?>
		</div>
		</form>
	</body>
</html>
