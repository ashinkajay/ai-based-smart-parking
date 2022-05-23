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

$sql = "SELECT * FROM parkingarea"; /*select items to display from the sensordata table in the data base*/
$cars = array();

 if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        array_push($cars,$row["availability"]);
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
    <title> Parking Area </title>
</head>
<body>

<h2 style="text-align: center; color:#1b00fe">Occupied: <?php echo array_sum($cars); ?> &nbsp&nbsp&nbsp Free slots: <?php echo 10 - array_sum($cars); ?></h2>
<div id = "tablebox">
<table>
    <?php
    echo '
    <tr>
        <td class = "car">'.$car_image[8].'A9</td>
        <td>Road</td>
        <td class = "car">'.$car_image[9].'A10</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[6].'A7</td>
        <td>Road</td>
        <td class = "car">'.$car_image[7].'A8</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[4].'A5</td>
        <td>Road</td>
        <td class = "car">'.$car_image[5].'A6</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[2].'A3</td>
        <td>Road</td>
        <td class = "car">'.$car_image[3].'A4</td>
    </tr>
    <tr>
        <td class = "car">'.$car_image[0].'A1</td>
        <td>Road</td>
        <td class = "car">'.$car_image[1].'A2</td>
    </tr>
    <tr>
        <td style="border: none"></td>
        <td><img src = "images/gate.gif" height = "80"></td>
        <td style="border: none"></td>
    </tr>';
    ?>
</table>
</div>
</body>
</html>