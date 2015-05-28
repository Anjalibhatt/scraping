<?php
	function connection()
{
			$servername = "localhost";
			$username = "root";
			$dbname = "web_crawler";
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);
			return $conn;
}
function fetchUrl()
{
			
	try {
			$conn = connection();
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			 
			 $sql = "SELECT * FROM `competitor_info`";
			 $result = $conn->query($sql);
			 $rows = array();
		  while($row = $result->fetch(PDO::FETCH_ASSOC))
		  {
			//$rows[] = $row['competitor_name'];
			echo '<option value="' . $row['website_url'] .'">' . $row['competitor_name']. '</option>';
		  }
		 //print_r($rows);
		}
	catch(PDOException $e) 
	{
		echo 'ERROR: ' . $e->getMessage();
	}
	$conn = null;
  return $row;
}

?>