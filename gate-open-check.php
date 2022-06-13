<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM cars  WHERE id = 1"; /*select items to display from the sensordata table in the data base*/
if ($result = $conn->query($sql)) {
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $row_id = $row["id"];
	$cars = $row["cars"];
    }
} 
else {
    echo "0 results";
}    
    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    // $row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));  
    // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
    //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 4 hours"));
    $result->free();
}
 


$conn->close();


	/*$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);*/

	$api_key_value = "tPmAT5Ab3j7F9";

	$api_key= "";
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
    	$api_key = test_input($_GET["api_key"]);
    	if($api_key == $api_key_value) {
        	if ($cars <10){
				echo 1;
			}
			else {
				echo 0;
			}
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
