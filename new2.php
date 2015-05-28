<?php
include_once('../../simple_html_dom.php');

function get_urls($url) 
{
    // create HTML DOM
    $html = file_get_html($url);
	$Arr = array();
	$i = 0;$k=0; 
	foreach($html->find('a') as $element) 
       {
			// $title = $element->find('a',0)->href; 
			// echo "<br>";
			$title=$element->href;
			
			$Arr[]=$title;
			//get_urls($title);
			//echo "<br>";
			//echo "<br>";
	   } 
		print_r($Arr);
    $html->clear();
    unset($html);
    return $Arr;
}
get_urls('http://www.suppliesoutlet.com');
?>