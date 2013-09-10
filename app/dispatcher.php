<?php

/*
 ===============

(c) 2012 EMBL European Molecular Biology Laboratories

This code is licensed under Version 2.0 of the Open Source Initiative Apache License.
URL: http://www.opensource.org/licenses/apache2.0.php

===============
*/


//   Dispatcher code below based on code found at URL: 
//   http://lamehacks.net/blog/simple-php-url-dispatcher
//   Thank you 

$routes = array(
		"molecule_image" => "molecule_image"
);


function dispatch(){
 
	global $routes;
 
	if(!empty($raw_route) and preg_match('/^[\p{L}\/\d]++$/uD', $_SERVER["PATH_INFO"]) == 0){
		die("Invalid URL");
	}
 
	$url_pieces = explode("/",$_SERVER["PATH_INFO"]);
	$action = $url_pieces[1];
	$params = array();
	if(count($url_pieces)>2){
		$params = array_slice($url_pieces, 2);
	}	
 
	if(empty($action)){
		not_found();
	}
 
	if(!in_array( $action, array_keys($routes))){
		not_found();
	}
	
	include_once("external_utils.php");
	
	$action_function = $routes[$action];
	$action_function($params);
}

// Move along nothing to see here
function not_found(){
    header('HTTP/1.0 404 Not Found');
    exit("<h1>404 Not Found</h1>\nThe page that you have requested could not be found.");
}

dispatch();

?>
