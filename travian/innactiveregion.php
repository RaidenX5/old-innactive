<?php
	include( "dbclass.inc.php" );
	
	$dbc = new dbdriver();
	$dbc->open_db();
	
	if( $_POST["getit"] )
	{
		$x = $_POST["xcoord"];
		$y = $_POST["ycoord"];
		$distance = $_POST["dista"];
		$exclude = strtolower( $_POST["exc"] );
		$tbl = $_POST["tbl"];
		$days = $_POST["days"];
		$region = strtolower( $_POST["region"] );
		$ally = strtolower( $_POST["ally"] );
		$sorttype = $_POST["sorttype"];
		$sort1 = $_POST["sort1"];
		$sort2 = $_POST["sort2"];
		
		$query = $dbc->get_innactive_region( $x, $y, $distance, $exclude, $tbl, $days, $region, $ally, $sort1, $sort2, $sorttype );
		$lastrun = exec( "cat /opt/progs/lastrunning" . $tbl );
	}
	else
	{
		if( $_SERVER['HTTP_REFERER'] != "http://selimutkabut.net23.net/innactiveregion.php" )
		{
			echo "Illegal access !";
			exit;
		}
		$x = 0;
		$y = 0;
		$distance = 999;
		$exclude = "";
		$tbl = "ts19com";
		$region = "";
		$lastrun = "";
		$ally = "";
		$days = 5;
		$sorttype = "";
		$sort1 = "";
		$sort2 = "";
	}
	
	?>
<html>
	<head>
		<meta charset="UTF-8">
		<script>
			function load()
			{
				if( "<?php echo $dbc->rowcount?>" != "0" )
				{
					document.getElementById( "table" ).style.display = "inline";
					document.getElementById( "list" ).style.display = "inline";
				}
			}
		</script>
	</head>
	<body onload="load();">
		Created and maintained by Battle Angel<BR>
		Skype : servlet_angel<BR>
		Credit to : Platzi and Ele. Much thanks, so awesome, wow.<BR>
		<form name="FormItem" method="POST" action="innactiveregion.php">
		<div id="input" style="display:inline">
			x:<input type="input" name="xcoord" value="<?php echo $x?>" style="font-size:10pt;height:24px;width:50px;">
			y:<input type="input" name="ycoord"  value="<?php echo $y?>" style="font-size:10pt;height:24px;width:50px;">
			dist:<input type="input" name="dista"  value="<?php echo $distance?>" style="font-size:10pt;height:24px;width:50px;">
			exclude:<input type="input" name="exc"  value="<?php echo $exclude?>" style="font-size:10pt;height:24px;width:50px;">
			table:<input type="input" name="tbl"  value="<?php echo $tbl?>" style="font-size:10pt;height:24px;width:50px;">
			days:<input type="input" name="days"  value="<?php echo $days?>" style="font-size:10pt;height:24px;width:50px;">
			region:<input type="input" name="region"  value="<?php echo $region?>" style="font-size:10pt;height:24px;width:50px;">
			ally:<input type="input" name="ally"  value="<?php echo $ally?>" style="font-size:10pt;height:24px;width:50px;">
			<input type="submit" value="getit" name="getit" style="width:70px">
			Last update : <?php echo $lastrun;?>
			<br>
			Sort 1:
			<select name="sort1">
				<option value="0">--</option>
				<option value="ally" <?php if( $sort1 == "ally" ){ echo "selected"; }?>>Ally</option>
				<option value="region" <?php if( $sort1 == "region" ){ echo "selected"; }?>>Region</option>
			</select>
			Sort 2:
			<select name="sort2">
				<option value="0">--</option>
				<option value="ally" <?php if( $sort2 == "ally" ){ echo "selected"; }?>>Ally</option>
				<option value="region" <?php if( $sort2 == "region" ){ echo "selected"; }?>>Region</option>
			</select>
			Sort type:
			<select name="sorttype">
				<option value="asc" <?php if( $sorttype == "asc" ){ echo "selected"; }?>>Ascending</option>
				<option value="desc" <?php if( $sorttype == "desc" ){ echo "selected"; }?>>Descending</option>
			</select>
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
						region
					</td>
					<td>
						location
					</td>
				</tr>
			
	<?php
	
	while( $arr = pg_fetch_array( $query ) )
	{
		$stringlist = $stringlist . "()" . $arr[2] . "|" . $arr[3];
		?>
				<tr>
					<td>
						<?php echo $arr[0]?>
					</td>
					<td>
						<?php echo $arr[2] . "|" . $arr[3]?>
					</td>
					<td>
						<?php echo $arr[4]?>
					</td>
					<td>
						<?php echo $arr[6]?>
					</td>
					<td>
						<?php echo $arr[8]?>
					</td>
					<td>
						<?php echo $arr[10]?>
					</td>
					<td>
						<?php echo $arr[11]?>
					</td>
					<td>
						<?php echo $arr[14]?>
					</td>
					<td>
						<a href="http://ts19.travian.com/position_details.php?x=<?php echo $arr[2];?>&y=<?php echo $arr[3];?>">Go to village</a>
					</td>
				</tr>
		<?php
	}

	$dbc->close_db();
?>
			</table>
		</div>
		<div id="list" style="display:none">
			<?php echo substr($stringlist,2)?>
		</div>
		</form>
	</body>
</html>
