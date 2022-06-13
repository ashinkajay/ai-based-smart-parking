<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "lab_evaluation";

	/*$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);*/

	$api_key_value = "tPmAT5Ab3j7F9";

	$api_key= $sprinkler1 = $sprinkler2 = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	$api_key = test_input($_POST["api_key"]);
    	if($api_key == $api_key_value) {
			$sprinkler1 = test_input($_POST["sprinkler1"]);
			$sprinkler2 = test_input($_POST["sprinkler2"]);
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} 
			
			$sql = "UPDATE parkingarea SET occupancy = '".$sprinkler2."' WHERE id = 1";
			
			if ($conn->query($sql) === TRUE) {
					echo "New record created successfully";
			} 
			else {
					echo "Error: " . $sql . "<br>" . $conn->error;
			}
			$conn->close();		
    	}
    	else {
        	echo "Wrong API Key provided.";
    	}
	}
	else {
    		echo "No data posted with HTTP POST.";
	}

	function test_input($data) {
    		$data = trim($data);
    		$data = stripslashes($data);
    		$data = htmlspecialchars($data);
    		return $data;
	}
?>
