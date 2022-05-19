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
            $car_image[$i] = '<img src = "images/free_slot.gif" height = "80">';
        }
        else{
            $car_image[$i] = '<img src = "images/free_slot.gif" height = "80">';
        }
    }
    else{
        if($i%2 == 0){
            $car_image[$i] = '<img src = "images/car_red_left.gif" height = "80">';
        }
        else{
            $car_image[$i] = '<img src = "images/car_red_right.gif" height = "80">';
        }
    }
}
?>

<head>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <title> Park here </title>
</head>
<body>
<h1 style="text-align: center;">
    Park here
</h1>
<div id = "tablebox">
<table>
    <?php
    echo '
    <tr>
        <td class = "car">'.$car_image[8].'A9</td>
        <td></td>
        <td class = "car">'.$car_image[9].'A10</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[6].'A7</td>
        <td><img src = "images/arrow.gif" height = "50" ></td>
        <td class = "car">'.$car_image[7].'A8</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[4].'A5</td>
        <td><img src = "images/arrow.gif" height = "50" ></td>
        <td class = "car">'.$car_image[5].'A6</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[2].'A3</td>
        <td><img src = "images/arrow.gif" height = "50" ></td>
        <td class = "car">'.$car_image[3].'A4</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[0].'A1</td>
        <td><img src = "images/arrow.gif" height = "50" ></td>
        <td class = "car">'.$car_image[1].'A2</td>
    </tr>';
    ?>
</table>
</div>
</body>
</html>