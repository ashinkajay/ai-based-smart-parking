<!DOCTYPE html>
<html>
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

$sql = "SELECT * FROM parking"; /*select items to display from the sensordata table in the data base*/

 if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $cars = array($row["A1"], $row["A2"],$row["A3"],$row["A4"],0,0,0,0,0,0);
    }
    $result->free();
}
    
$conn->close();

// To put required html of car image
$car_image = array();
for($i = 0; $i<10; $i++){
    if ($cars[$i] == 0){
        if($i%2 == 0){
            $car_image[$i] = '<img src = "images/free_slot.gif" width = "200">';
        }
        else{
            $car_image[$i] = '<img src = "images/free_slot.gif" width = "200">';
        }
    }
    else{
        if($i%2 == 0){
            $car_image[$i] = '<img src = "images/car_red_left.gif" width = "200">';
        }
        else{
            $car_image[$i] = '<img src = "images/car_red_right.gif" width = "200">';
        }
    }
}
?>

<head>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <title> Parking Area </title>
</head>
<body>
<h1 style="text-align: center;">
    Parking Area
</h1>
<div id = "tablebox">
<table>
    <?php
    echo '
    <tr>
        <td class = "car">'.$car_image[8].'A9</td>
        <td>Road</td>
        <td class = "car">A10'.$car_image[9].'</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[6].'A7</td>
        <td>Road</td>
        <td class = "car">A8'.$car_image[7].'</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[4].'A5</td>
        <td>Road</td>
        <td class = "car">A6'.$car_image[5].'</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[2].'A3</td>
        <td>Road</td>
        <td class = "car">A4'.$car_image[3].'</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[0].'A1</td>
        <td>Road</td>
        <td class = "car">A2'.$car_image[1].'</td>
    </tr>';
    ?>
</table>
</div>
</body>
</html>