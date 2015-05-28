<?php 
set_time_limit(0);
//ini_set ('max_execution_time', 1200); 

	 //if(isset($_POST['submit']))
	// {
	 $_SESSION['visitedlinks'] = array();
	 //$domain = "suppliesoutlet.com"; 
	//  $fix = $_POST['competitor'];  
	//$url = $_POST['competitor']; 
	$fix = "http://www.ldproducts.com";
	$domain = str_replace("http://www."," ",$fix);
	$url = "http://www.ldproducts.com";
	 $Arr1 = array();
				function connection()
				{
							$servername = "localhost";
							$username = "root";
							$dbname = "web_crawler";
							$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);
							return $conn;
				}
				function dataSave($a,$domain)
				{	
							try {	$conn = connection();
									$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									 $stmt = $conn->prepare("INSERT INTO temp_competitor_urls (competitor_name,url) 
										VALUES (:c,:u)");
									   
										$stmt->bindParam(':c', $c);
										$stmt->bindParam(':u', $u);
										
										// insert a row

										
										$c = $domain;
										$u = $a;
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
	
	 function get_all_links($string, $domain)
			{
				if (preg_match_all('/<a[^>]+href\s*=\s*["\'](?!(?:#|javascript\s*:))([^"\']+)[^>]*>(.*?)<\/a>/si', $string, $links))
				{
					$domain = preg_quote($domain, '/');

					foreach (array_keys($links[1]) AS $key)
					{
						if (preg_match("/^(ht|f)tps?:\/\/(?!((www\.)?{$domain}))/i", $links[1][$key]))
						{
							$type = 'external';
						}
						else
						{
							$type = 'internal';
						//$links[1][$key]=strip_tags(trim($links[1][$key]));
							$links[$type]['url'][]  = $links[1][$key];
							//print_r($links);
						}
						if (!$text = preg_replace('/\s{2,}/', ' ', strip_tags(trim($links[2][$key]), '<href>')))
						{
							$text = 'Undefined link text';
						}
						
					}
						
					// Clean array
					unset($links[0], $links[1], $links[2]);

					return $links;
					
				}
				
				return false;
			}
 	 function curl($url)
	 {
        $ch = curl_init();  // Initialising cURL
        curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
        $html = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
       	  curl_close($ch);
		  return $html;
    }
	function check_url($a)
	{
		
		if(preg_match("/http:\/\//", $a))
		{
					return trim($a);
		}
		else 
		{
				if(preg_match('^/\/\www^',$a))
				{
					$a = "http:".$a;
				return trim($a);
				}
				else if(preg_match("^/+([a-zA-Z0-9])^",$a))
				{
					$a = $GLOBALS['fix'].$a;
				return trim($a);	
				}
				else //if(preg_match("/([a-zA-Z0-9])/",$a))
				{
				return trim($a);	
				}
		}
	}
	
	function call_link($url,$domain)
				{	
					$_SESSION['visitedlinks'][] = $url;
					$result = curl($url);	
					$cvv = get_all_links($result, $domain);
					//print_r($cvv);
					//$cvv->lenght;
					for($i=0;$i<=count($cvv["internal"]["url"]);$i++)
					{	
					
					$a= check_url($cvv["internal"]["url"][$i]);
						if($a)
						{
						
							if(!in_array($a,$_SESSION['visitedlinks']))
								{	
									
										
									call_link($a,$domain);
									//if(preg_match("/.html/", $a))
									//{
									$_SESSION['visitedlinks'][] = $a;
									 //datasave($a,$domain);
									//}	
								}
								$_SESSION['visitedlinks'] = array_unique($_SESSION['visitedlinks']);
						}
					}
					
					
					print_r($_SESSION['visitedlinks']);
				}
				
	$dburl= call_link($url,$domain);
	//print_r($dburl);
	//}