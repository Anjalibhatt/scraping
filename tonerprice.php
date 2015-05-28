<?php
set_time_limit(0);
include_once('../../simple_html_dom.php');
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
			 
			 $sql = "SELECT url FROM tonerprice";
			 $result = $conn->query($sql);
			 $rows = array();
		  while($row = $result->fetch(PDO::FETCH_ASSOC))
		  {
			$rows[] = $row['url'];
			
		  }
		 //print_r($rows);
		}
	catch(PDOException $e) 
	{
		echo 'ERROR: ' . $e->getMessage();
	}
	$conn = null;
  return $rows;
}
function dataSave($Arr1)
{	
			try {	
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					 $stmt = $conn->prepare("INSERT INTO scrapper (code,title,price) 
						VALUES (:c,:t,:p)");
					   
						$stmt->bindParam(':c', $c);
						$stmt->bindParam(':t', $t);
						$stmt->bindParam(':p', $p);
						
						// insert a row

						
						$c = $Arr1['code'];
						$t = $Arr1['title'];
						$p = $Arr1['price'];
						//$p = $price;
						
						$stmt->execute();
						 echo '<br />';   
				}
				catch(PDOException $e)
				{
						echo 'ERROR: ' . $e->getMessage();
				}

			  $conn = null; 

} 
	 
function singleprod_Page($url)
{
		 $Arr1 = array();
		
		 $html = file_get_html($url);		
		 $Scode=$html->find('p[class="ItemNumber"]', 0)->innertext;
		 $Scode = str_replace("Item Number:"," ",$Scode);
		 $Arr1['code'] = $Scode;
			
		 $Stitle = $html->find('h1[itemprop="name"]', 0)->innertext;
		 $Arr1['title'] = $Stitle;
		
		$Sprice = $html->find('span[itemprop="price"]', 0)->innertext;
		$Sprice = strip_tags($Sprice);
		$Sprice = str_replace("$"," ",$Sprice);
		$Arr1['price'] = $Sprice;	
		
		print_r($Arr1);		
			
	$html->clear();
    unset($html);
	//dataSave($Arr1);
    return $Arr1;
	
}
function Prod_Page($url) 
{
    // create HTML DOM
    $html = file_get_html($url);
	$Arr = array();
	//echo $title = $html->find('a',0)->href;
	foreach($html->find('h2[class="product-name"]') as $element) 
       {
			 echo $title = $element->find('a',0)->href; 
			 echo "<br>";
			//singleprod_Page($title);
	   } 
		
    $html->clear();
    unset($html);
    return $Arr;
}

// -----------------------------------------------------------------------------
// test it!
 $dbUrl = fetchUrl();
//print_r($dbUrl);
$i=0;
$arr = array();
foreach($dbUrl as $element=>$a)
{
	if(preg_match("/http:\/\/tonerprice\.com\//", $a))
	{
		$i++;
					
		$arr['url']=$a;
		//$b=array_chunk($arr,100,true);
		//print_r($b);
		echo "<br>";
	
		//print_r(array_unique($arr));
			echo "<br>";
		//$p_code = Prod_Page($a);		
	}
	
	
} 
echo $i;
//$p_code = Prod_Page('http://tonerprice.com/ink-toner/xerox/workcentre/workcentre-c2424.html');
?>