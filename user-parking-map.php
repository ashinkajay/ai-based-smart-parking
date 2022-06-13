<!DOCTYPE html>
<html>
<?php

$api_key_value = "tPmAT5Ab3j7F9";
$api_key= "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $api_key = test_input($_GET["api_key"]);
    if($api_key == $api_key_value) {
        $freeSlotNo = $_GET["slot"];
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

$sql = "SELECT * FROM parkingarea"; /*select items to display from the sensordata table in the data base*/
$cars = array(); //stores slot occupancy info

 if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        array_push($cars,$row["occupancy"]);
    }
    $result->free();
}
    
$conn->close();

// To put required html of car image
$car_image = array();
for($i = 0; $i<10; $i++){
    if ($cars[$i] == 0){
        if($i%2 == 0){
            $car_image[$i] = '<img src = "images/free_slot.gif" height = "180">';
        }
        else{
            $car_image[$i] = '<img src = "images/free_slot.gif" height = "180">';
        }
    }
    else{
        if($i%2 == 0){
            $car_image[$i] = '<img src = "images/car_red_left.gif" height = "180">';
        }
        else{
            $car_image[$i] = '<img src = "images/car_red_right.gif" height = "180">';
        }
    }
}

// To put required html for the road
$road = ["", "", "", "", ""];
$step_count = floor(($freeSlotNo - 1)/2);
for($i=0; $i<$step_count; $i++){
$road[$i] = '<img src = "images/arrow.gif" height = "80">';
}
if($freeSlotNo %2 == 0){
    $road[$i] = '<img src = "images/arrow_right.gif" height = "80">';
}
else{
    $road[$i] = '<img src = "images/arrow_left.gif" height = "80">';
}

?>

<head>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <title> Park here </title>
</head>
<body>
<h1 style="text-align: center; font-size:100px;">
    Park here
</h1>
<br>
<br>
<div id = "tableboxmobile">
<table>
    <?php
    echo '
    <tr>
        <td class = "car">'.$car_image[8].'A9</td>
        <td>'.$road[4].'</td>
        <td class = "car">'.$car_image[9].'A10</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[6].'A7</td>
        <td>'.$road[3].'</td>
        <td class = "car">'.$car_image[7].'A8</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[4].'A5</td>
        <td>'.$road[2].'</td>
        <td class = "car">'.$car_image[5].'A6</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[2].'A3</td>
        <td>'.$road[1].'</td>
        <td class = "car">'.$car_image[3].'A4</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[0].'A1</td>
        <td>'.$road[0].'</td>
        <td class = "car">'.$car_image[1].'A2</td>
    </tr>
    <tr>
        <td style="border: none"></td>
        <td><img src = "images/gate.gif" height = "180"></td>
        <td style="border: none"></td>
    </tr>';
    ?>
</table>
</div>
</body>
</html>