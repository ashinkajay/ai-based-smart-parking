<!DOCTYPE html>
<html>
<?php
// Include the qrlib file
include 'includes/phpqrcode/qrlib.php';

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
$cars = array(); //stores slot availability info

 if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        array_push($cars,$row["availability"]);
    }
    $result->free();
}

$conn->close();

// Free slot calculation
$freeSlotNo = 10;
for($i = 0; $i<10; $i++){
    if($cars[$i] == 0){
        $freeSlotNo = $i +1;
        break;
    }
}

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
<h1 style="text-align: center;">
    Park here
</h1>
<div class="flex-parent-element">
<div class="flex-child-element">
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
        <td><img src = "images/gate.gif" height = "80"></td>
        <td style="border: none"></td>
    </tr>';
    ?>
</table>
</div>
<div class="flex-child-element">
    <?php
    $text = "http://localhost/project/parkingmap.php?api_key=tPmAT5Ab3j7F9&slot=".$freeSlotNo;
  
    // $path variable store the location where to 
    // store image and $file creates directory name
    // of the QR code file
    $path = 'images/';
    $file = $path."qr.png";
      
    // $ecc stores error correction capability('L')
    $ecc = 'L';
    $pixel_Size = 10;
    $frame_Size = 2;
      
    // Generates QR Code and Stores it in directory given
    QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);
      
    // Displaying the stored QR code from directory
    echo "<center><img src='".$file."'></center>";
    ?>
<h3 style="text-align: center ;">Scan this QR Code to get this map on your phone.</h3>
</div>
</div>
</body>
</html>