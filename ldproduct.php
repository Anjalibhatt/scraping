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
			 
			 $sql = "SELECT url FROM `tonerprice`";
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
			try {	$conn = connection();
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
		 if($html==true)
		{
			$Scode=$html->find('span[class="item_number"]', 0)->innertext;
			 
			 if($Scode==''){
			 multipleprod_Page($url);
			 return "Product Listing Page";
			 }
			 else{
				$Arr1['code'] = $Scode;
				
			 $Stitle = $html->find('span[itemprop="name"]', 0)->innertext;
					/* if(preg_match("/Bundle/", $Stitle))
					{
					echo "<br>".$Stitle."<br>";
					} */
			$Arr1['title'] = $Stitle;
			 $Sprice = $html->find('span[class="price"]', 0)->innertext;
				//$Sprice = strip_tags($Sprice);
					$Sprice = str_replace("$"," ",$Sprice);
				$Arr1['price'] = $Sprice;	
			//print_r($Arr1);		
			}	
		}
		else 
		{
			echo "error";
		}
	$html->clear();
    unset($html);
	//dataSave($Arr1);
    return $Arr1;
	
}
function multipleprod_Page($url) 
{
    // create HTML DOM
    $html = file_get_html($url);
	$Arr = array();
	$i = 0;$k=0; 
	foreach($html->find('h3[class="product-name"]') as $element) 
       {
			 $title = $element->find('a',0)->href; 
			// echo "<br>";
			singleprod_Page($title);
			//echo "<br>";
			//echo "<br>";
	   } 
		
    $html->clear();
    unset($html);
    return $Arr;
}

// -----------------------------------------------------------------------------
// test it!
$dbUrl = fetchUrl();
 $i=0;
// $arr = array();
$dbUrl=array_unique($dbUrl);
foreach($dbUrl as $element=>$a)
{
	
	if(preg_match("/http:\/\/\www\.ldproducts\.com\//", $a))
	{
	$i++;
		$a=trim($a);
		//$a=array_unique($a);
		//print_r($a);
		singleprod_Page($a);
	}
}
echo $i;

//print_r($dbUrl);
/* for($i=0;i<10;$i++)
{
echo $dbUrl[$i], "\n";
}  */

?>