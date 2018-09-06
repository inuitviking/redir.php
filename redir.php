<?php

	/*
	**	VERSION:	1.0
	**	NOTICE:
	**		This script should be used on a website with an SSL certificate!
	**		It WILL work with http protocol, since it checks if the website
	**		uses it!
	*/

	// Check if the url parameter is empty or not
	if(!empty($_GET['url'])){

		// If it's not empty, put it in variable $url
		$url = $_GET['url'];
	}elseif(empty($_GET['url'])){

		// If it is empty, take the server name (base domain name),
		// and add 'http://' in front of it to make below work.
		// This is a fallback, in case the url parameter is not set
		$url = 'http://'.$_SERVER['SERVER_NAME'];

	}

	// All of this checks if $url has an SSL certificate
	$stream = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
	$read = fopen("$url", "rb", false, $stream);
	$cont = stream_context_get_params($read);
	$var = ($cont["options"]["ssl"]["peer_certificate"]);
	// The result is stored in $ssl
	$ssl = (!is_null($var)) ? true : false;

	// If the site has an SSL certificate
	if($ssl){
		// Check if https does not exist in $url
		if(preg_match('/\bhttps\b/',$url) == false){

			// Replace http with https
			$url = preg_replace("/^http:/i", "https:", $url);
		}
		// Redirect user to $url
		header("Location: $url");

	// If the site does not have an SSL certificate
	}elseif(!$ssl){
		// Check if https exists in $url
		if (preg_match('/\bhttps\b/',$url)){

			// Replace https with http
			$url = preg_replace("/^https:/i", "http:", $url);
		}
		// Redirect user to $url
		header("Location: $url");

	}

?>
