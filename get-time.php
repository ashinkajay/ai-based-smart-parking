<?php


	$api_key_value = "tPmAT5Ab3j7F9";

	$api_key= "";
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
    	$api_key = test_input($_GET["api_key"]);
    	if($api_key == $api_key_value) {
        	echo time();
		}
    	else {
        	echo "Wrong API Key provided.";
    	}
	}
	else {
    		echo "Not using HTTP GET.";
	}

	function test_input($data) {
    		$data = trim($data);
    		$data = stripslashes($data);
    		$data = htmlspecialchars($data);
    		return $data;
	}
?>
