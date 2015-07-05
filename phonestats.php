<?php

$name = $_GET['apname'];

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
//to do- add sanitation for sql requests
//this is the ap specific stuff
$unique = "select count(distinct macaddress) from wifi where name = \"" . $name . "\"";  //number of unique visitors
$average = ""; //average time spent
$iphone = "select count(distinct macaddress) from wifi where browseragent like \"%iPhone%\" and name = \"" . $name ."\""; //number of iPhones
$android = "select count(distinct macaddress) from wifi where browseragent like \"%Android%\" and name = \"" . $name . "\""; //number of Android 
$avgaccesscount = "select avg(accesscount) from wifi where name = \"" . $name . "\"";

//start querying database
$data = $conn->query($unique);
$uniquenum = $data->fetch_assoc()['count(distinct macaddress)'];
$data = $conn->query($iphone);
$iphonenum = $data->fetch_assoc()['count(distinct macaddress)'];
echo $iphonenum.",";
$data = $conn->query($android);
$androidnum = $data->fetch_assoc()['count(distinct macaddress)'];
echo $androidnum.",";
echo ($uniquenum - ($androidnum + $iphonenum));


$conn->close();
?>
