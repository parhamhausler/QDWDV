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
if ($name === "main") {
//this is the default stuff

echo "<b>Welcome to QDWDV</b><br><br>Click on an access point to see more detailed statistics.<br>";
echo "<br><b>Top 10 access points, by unique visitors.</b><br>";
//gets unique macs per access point
//$query = "select latitude, longitude, name, count(*) from (select distinct name, macAddress from wifi where macAddress IS NOT NULL AND macAddress <> '') a group by name order by count(*) desc;";
$query = "select name, latitude, longitude, count(*) from (select distinct name, macAddress, latitude, longitude from wifi where macAddress IS NOT NULL AND macAddress <> '' AND longitude <> '') a group by name order by count(*) desc";
$data = $conn->query($query);
if ($data->num_rows > 0) {
    // output top 10 rows 
    for($x=0;$x<10;$x++) {
    	$row = $data->fetch_assoc();
        echo "<br><a href=\"#\" onclick=goto(" . $row['latitude'] . "," . $row['longitude'] . ");>"  . $row["name"]. "</a> " . $row["count(*)"];
    }
}


} else {
//this is the ap specific stuff
echo "<b>".$name."</b><br>";
$unique = "select count(distinct macaddress) from wifi where name = \"" . $name . "\"";  //number of unique visitors
$average = ""; //average time spent
$iphone = "select count(distinct macaddress) from wifi where browseragent like \"%iPhone%\" and name = \"" . $name ."\""; //number of iPhones
$android = "select count(distinct macaddress) from wifi where browseragent like \"%Android%\" and name = \"" . $name . "\""; //number of Android 
$avgaccesscount = "select avg(accesscount) from wifi where name = \"" . $name . "\"";

//start querying database
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
echo "<br>Average Accesscount: " . round($data->fetch_assoc()['avg(accesscount)'],2);

echo "<br><br><a href=\"#\" onclick=setinitial();>Back</a>";
}


$conn->close();
?>
