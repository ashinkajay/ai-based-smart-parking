<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "project";

	/*$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);*/

	$api_key_value = "tPmAT5Ab3j7F9";

	$api_key= "";
	$car = array(1,1,1,1,1,0,0,0,0,0);

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
    	$api_key = test_input($_GET["api_key"]);
    	if($api_key == $api_key_value) {
			//$car = test_input($_GET["car"]);
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} 
			
			for($i = 0; $i <10; $i++){
				$sql = "UPDATE parkingarea SET occupancy = '".$car[$i]."' WHERE slot = " . ($i + 1) ."";
				if ($conn->query($sql) === TRUE) {
						echo "Record ".$i." created successfully";
				} 
				else {
						echo "Error creating record: " . $i . " " . $sql . "<br>" . $conn->error;
				}
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
