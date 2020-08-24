<?php  
 
$bobblog_api_url = "http://www.roberttamayo.com/blog/api/"; 
 
if (isset($blog_postid)) { 
    $url = "$bobblog_api_url?key&type=post&postid=$blog_postid"; 
     
} else if (isset($blog_permalink)) { 
    $url = "$bobblog_api_url?key&type=post&permalink=$blog_permalink"; 
     
} 
 
$curl = curl_init();  
curl_setopt($curl, CURLOPT_URL, $url); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
$data = curl_exec($curl); 
 
// Check if any error occurred 
if(curl_errno($curl)) { 
    echo 'Curl error: ' . curl_error($curl); exit; 
} 
curl_close($curl); 
 
$blog_data = json_decode($data, true); 
