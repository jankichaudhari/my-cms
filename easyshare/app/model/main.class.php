<?php
class mainClass
{
	//Class starts here .
	//PHP and MySQL Connection and Error Specific methods
	
	public $DBASE="";
	public $CONN="";
	
	public function mainClass($server="",$dbase="", $user="", $pass="")
	{
		$this->DBASE = $dbase;
		$conn = mysql_pconnect($server,$user,$pass);
		if(!$conn) {
			$this->error("Connection attempt failed");
		}
		if(!mysql_select_db($dbase,$conn)) {
			$this->error("Database Select failed");
		}
		$this->CONN = $conn;
		return true;
	}
	public function close()
	{
		$conn = $this->CONN ;
		$close = mysql_close($conn);
		if(!$close) {
			$this->error("Connection close failed");
		}
		return true;
	}
	
	public function error($text)
	{
		$no = mysql_errno();
		$msg = mysql_error();
		exit;
	}
	
	public function select($sql="")
	{
		if(empty($sql)) { return false; }
		if(!eregi("^select",$sql))
		{
			echo "wrongquery<br>$sql<p>";
			echo "<H2>Wrong function!</H2>\n";
			return false;
		}
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = @mysql_query($sql,$conn);
		if( (!$results) or (empty($results)) ) {
			return false;
		}
		$count = 0;
		$data = array();
		while ($row = mysql_fetch_array($results))
		{
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($results);
		return $data;
	}
	
	
	public function insert ($sql="")
	{
		if(empty($sql)) { return false; }
		if(!eregi("^insert",$sql))
		{
			return false;
		}
		if(empty($this->CONN))
		{
			return false;
		}
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			$this->error("<H2>No results!</H2>\n");
			return false;
		}
		$id = mysql_insert_id();
		return $id;
	}
	
	public function sql_query($sql="")
	{	if(empty($sql)) { return false; }
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn) or die("query fail");
		if(!$results)
		{   $message = "Query went bad!";
			$this->error($message);
			return false;
		}		
		if(!eregi("^select",$sql)){
			return true; }
		else {
			$count = 0;
			$data = array();
			while ( $row = mysql_fetch_array($results))	{
				$data[$count] = $row;
				$count++;
			}
			mysql_free_result($results);
			return $data;
	 	}
		
	}	
	
//ends the class over here
}
?>