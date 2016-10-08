<?php
	class dbdriver
	{
		private $dbx;
		private $query;
		public $status = "close";
		public $rowcount = 0;
		public $error;
		public function open_db()
		{
			if( $this->status == "open" )
			{
				return -1;
			}
			
			$gVER = "1.20140416";
			$gPGHOST = "127.0.0.1";
			$gPGPORT = "5432";
			$gPGDATABASE = "tr";
			$gPGUSER = "www-data";
			$gPGPASSWORD = "123456";
			$gPGCLIENTENCODING = "UNICODE";
			$gERROR_ON_CONNECT_FAILED = "Sorry, Can Not Connect The Database Server Now !";
			$gSTR_CON = "host= $gPGHOST port= $gPGPORT dbname= $gPGDATABASE user= $gPGUSER password= $gPGPASSWORD ";

			$this->dbx = pg_connect($gSTR_CON); // connect to postgres Database
			$this->status = "open";
		}
		
		public function close_db()
		{
			if( $this->status == "close" )
			{
				return -1;
			}
			
			$this->status = "close";
			pg_close( $this->dbx );
		}
		
		public function get_innactive( $x, $y, $dist, $exclude, $tbl, $days )
		{
			$all_ex = explode( "<|>", $exclude );
			if( $x < 0 )
			{
				$x = "+" . abs( $x );
			}
			else
			{
				$x = "-$x";
			}
			
			if( $y < 0 )
			{
				$y = "+" . abs( $y );
			}
			else
			{
				$y = "-$y";
			}
			
			switch( $days )
			{
				case 1 :
					$dstr = "1 = 1";
					break;
				case 2 :
					$dstr = "d1 <= d2";
					break;
				case 3 :
					$dstr = "d1 <= d2 and d2 <= d3";
					break;
				case 4 :
					$dstr = "d1 <= d2 and d2 <= d3 and d3 <= d4";
					break;
				case 5 :
					$dstr = "d1 <= d2 and d2 <= d3 and d3 <= d4 and d4 <= d5";
					break;
				default :
					$dstr = "d1 <= d2 and d2 <= d3 and d3 <= d4 and d4 <= d5";
					break;
			}
			
			if( $dist == "" )
			{
				$query_get_innactive = "SELECT round(sqrt(abs(x$x)^2 + abs(y$y)^2)::numeric, 1) as xx, * from $tbl where userid in (SELECT userid FROM {$tbl}_populate where $dstr)";
			}
			else
			{
				$query_get_innactive = "SELECT round(sqrt(abs(x$x)^2 + abs(y$y)^2)::numeric, 1) as xx, * from $tbl where sqrt(abs(x$x)^2 + abs(y$y)^2) <= $dist and userid in (SELECT userid FROM {$tbl}_populate where $dstr)";
			}
			
			// $query_get_innactive = "SELECT round(sqrt(least(abs(x$x),400-abs(x$x))^2 + least(abs(y$y),400-abs(y$y))^2)::numeric, 1) as xx, * from $tbl where sqrt(least(abs(x$x),400-abs(x$x))^2 + least(abs(y$y),400-abs(y$y))^2) <= $dist and userid in (SELECT userid FROM {$tbl}_populate where d1 = d2 and d2 = d3 and d3 = d4 and d4 = d5)";
			
			
			for( $a=0; $a<sizeof( $all_ex ); $a++ )
			{
				if( $all_ex[$a] != "" )
				{
					$query_get_innactive = $query_get_innactive . " and LOWER( ally ) not like '%$all_ex[$a]%'";
				}
			}
			
			$query_get_innactive = $query_get_innactive . " order by xx;";

			$this->query = pg_query( $this->dbx, $query_get_innactive );
			
			if( !$this->query )
			{
				$this->error = pg_last_error();
			}
			
			$this->rowcount = pg_num_rows( $this->query );
			
			return $this->query;
		}
		
		public function get_innactivet5( $x, $y, $dist, $exclude, $tbl, $days )
		{
			$all_ex = explode( "<|>", $exclude );
			if( $x < 0 )
			{
				$x = "+" . abs( $x );
			}
			else
			{
				$x = "-$x";
			}
			
			if( $y < 0 )
			{
				$y = "+" . abs( $y );
			}
			else
			{
				$y = "-$y";
			}
			
			switch( $days )
			{
				case 1 :
					$dstr = "1 = 1";
					break;
				case 2 :
					$dstr = "d1 <= d2";
					break;
				case 3 :
					$dstr = "d1 <= d2 and d2 <= d3";
					break;
				case 4 :
					$dstr = "d1 <= d2 and d2 <= d3 and d3 <= d4";
					break;
				case 5 :
					$dstr = "d1 <= d2 and d2 <= d3 and d3 <= d4 and d4 <= d5";
					break;
				default :
					$dstr = "d1 <= d2 and d2 <= d3 and d3 <= d4 and d4 <= d5";
					break;
			}
			
			// $query_get_innactive = "SELECT round(sqrt(least(abs(x$x),400-abs(x$x))^2 + least(abs(y$y),400-abs(y$y))^2)::numeric, 1) as xx, * from $tbl where sqrt(least(abs(x$x),400-abs(x$x))^2 + least(abs(y$y),400-abs(y$y))^2) <= $dist and userid in (SELECT userid FROM {$tbl}_populate where d1 = d2 and d2 = d3 and d3 = d4 and d4 = d5)";
			$query_get_innactive = "SELECT round(sqrt(abs(x$x)^2 + abs(y$y)^2)::numeric, 1) as xx, * from $tbl where sqrt(abs(x$x)^2 + abs(y$y)^2) <= $dist and userid in (SELECT userid FROM {$tbl}_populate where $dstr) AND (x || '|' || y) not IN (select coord from {$tbl}_ex)";
			
			for( $a=0; $a<sizeof( $all_ex ); $a++ )
			{
				if( $all_ex[$a] != "" )
				{
					$query_get_innactive = $query_get_innactive . " and LOWER( ally ) not like '%$all_ex[$a]%'";
				}
			}
			
			$query_get_innactive = $query_get_innactive . " order by xx LIMIT 100;";

			$this->query = pg_query( $this->dbx, $query_get_innactive );
			
			if( !$this->query )
			{
				$this->error = pg_last_error();
			}
			
			$this->rowcount = pg_num_rows( $this->query );
			
			return $this->query;
		}
		
		public function get_map( $x, $y, $tbl )
		{
			if( $x < 0 )
			{
				$x = "+" . abs( $x );
			}
			else
			{
				$x = "-$x";
			}
			
			if( $y < 0 )
			{
				$y = "+" . abs( $y );
			}
			else
			{
				$y = "-$y";
			}

			$query_get_map = "SELECT round(sqrt(abs(x$x)^2 + abs(y$y)^2)::numeric, 1) as xx, * from $tbl where sqrt(abs(x$x)^2 + abs(y$y)^2) >= 0";
			
			$query_get_map = $query_get_map . " order by xx;";

			$this->query = pg_query( $this->dbx, $query_get_map );
			
			if( !$this->query )
			{
				$this->error = pg_last_error();
			}
			
			$this->rowcount = pg_num_rows( $this->query );
			
			return $this->query;
		}
		
		public function do_sql( $querystr )
		{
			// echo "$querystr" . "\n";
			pg_query( $this->dbx, $querystr );
		}
		
		public function get_innactive_region( $x, $y, $dist, $exclude, $tbl, $days, $region, $ally, $sort1, $sort2, $sorttype )
		{
			$all_ex = explode( "<|>", $exclude );
			$ally_inc = explode( "<|>", $ally );
			if( $x < 0 )
			{
				$x = "+" . abs( $x );
			}
			else
			{
				$x = "-$x";
			}
			
			if( $y < 0 )
			{
				$y = "+" . abs( $y );
			}
			else
			{
				$y = "-$y";
			}
			
			switch( $days )
			{
				case 1 :
					$dstr = "1 = 1";
					break;
				case 2 :
					$dstr = "d1 <= d2";
					break;
				case 3 :
					$dstr = "d1 <= d2 and d2 <= d3";
					break;
				case 4 :
					$dstr = "d1 <= d2 and d2 <= d3 and d3 <= d4";
					break;
				case 5 :
					$dstr = "d1 <= d2 and d2 <= d3 and d3 <= d4 and d4 <= d5";
					break;
				default :
					$dstr = "d1 <= d2 and d2 <= d3 and d3 <= d4 and d4 <= d5";
					break;
			}
			
			if( $dist == "" )
			{
				$query_get_innactive = "SELECT round(sqrt(abs(x$x)^2 + abs(y$y)^2)::numeric, 1) as xx, * from $tbl left join regions on x || '|' || y = coord where userid in (SELECT userid FROM {$tbl}_populate where $dstr)";
			}
			else
			{
				$query_get_innactive = "SELECT round(sqrt(abs(x$x)^2 + abs(y$y)^2)::numeric, 1) as xx, * from $tbl left join regions on x || '|' || y = coord  where sqrt(abs(x$x)^2 + abs(y$y)^2) <= $dist and userid in (SELECT userid FROM {$tbl}_populate where $dstr)";
			}
			
			// $query_get_innactive = "SELECT round(sqrt(least(abs(x$x),400-abs(x$x))^2 + least(abs(y$y),400-abs(y$y))^2)::numeric, 1) as xx, * from $tbl where sqrt(least(abs(x$x),400-abs(x$x))^2 + least(abs(y$y),400-abs(y$y))^2) <= $dist and userid in (SELECT userid FROM {$tbl}_populate where d1 = d2 and d2 = d3 and d3 = d4 and d4 = d5)";
			
			
			for( $a=0; $a<sizeof( $all_ex ); $a++ )
			{
				if( $all_ex[$a] != "" )
				{
					$query_get_innactive = $query_get_innactive . " and LOWER( ally ) not like '%$all_ex[$a]%'";
				}
			}
			
			for( $a=0; $a<sizeof( $ally_inc ); $a++ )
			{
				if( $ally_inc[$a] != "" )
				{
					$query_get_innactive = $query_get_innactive . " AND LOWER( ally ) like '%$ally_inc[$a]%'";
				}
			}
			
			if( $region != "" )
			{
				$query_get_innactive = $query_get_innactive . " AND LOWER( region ) LIKE ( '%{$region}%' )";
			}
			
			$query_get_innactive = $query_get_innactive . " order by";
			
			if( $sort1 != "0" )
			{
				$query_get_innactive = $query_get_innactive . " {$sort1},";
			}
			
			if( $sort2 != "0" )
			{
				$query_get_innactive = $query_get_innactive . " {$sort2},";
			}
			
			
			
			$query_get_innactive = $query_get_innactive . "  xx";
			
			$query_get_innactive = $query_get_innactive . " " . $sorttype . ";";

			$this->query = pg_query( $this->dbx, $query_get_innactive );
			
			if( !$this->query )
			{
				$this->error = pg_last_error();
			}
			
			$this->rowcount = pg_num_rows( $this->query );
			
			return $this->query;
		}
	}
	
?>
