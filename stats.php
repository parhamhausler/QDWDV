<?php

$name = $_GET['apname'];
echo "<b>".$name."</b><br>";

//gets data for markers from database
$server = "localhost";
$username = "govhack";
$password = "govhack";
$db = "govhack";

$conn = new mysqli($server, $username, $password, $db);
if ($conn->connect_error) {
        die("Connection failed: " . $conn->connection_eror);
}
//connected to db

$unique = "select count(distinct macaddress) from wifi where name = \"" . $name . "\"";  //number of unique visitors
$average = ""; //average time spent
$iphone = "select count(distinct macaddress) from wifi where browseragent like \"%iPhone%\" and name = \"" . $name ."\""; //number of iPhones
$android = "select count(distinct macaddress) from wifi where browseragent like \"%Android%\" and name = \"" . $name . "\""; //number of Android 
$avgaccesscount = "select avg(accesscount) from wifi where name = \"" . $name . "\"";


$data = $conn->query($unique);
$uniquenum = $data->fetch_assoc()['count(distinct macaddress)'];
echo "<br>Unique Visitors: " . $uniquenum;
$data = $conn->query($iphone);
$iphonenum = $data->fetch_assoc()['count(distinct macaddress)'];
echo "<br>Number of iPhones: " . $iphonenum;
$data = $conn->query($android);
$androidnum = $data->fetch_assoc()['count(distinct macaddress)'];
echo "<br>Number of Android Devices: " . $androidnum;
echo "<br>Number of Other Devices: " . ($uniquenum - ($androidnum + $iphonenum));
$data = $conn->query($avgaccesscount);
echo "<br>Average Accesscount: " . $data->fetch_assoc()['avg(accesscount)'];


$conn->close();
?>
