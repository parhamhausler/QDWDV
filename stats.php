<?php

$name = $_GET['apname'];
echo $name;

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
$uniqueid = "select count(distinct visitorid) from wifi where name = \"" . $name . "\"";  //number of unique visitors
$average = ""; //average time spent
$iphones = ""; //number of iphones

//$sql = "select locationid, name from wifi where name=" . $accesspoint . "\"";

$data = $conn->query($unique);
echo "<br>Unique Visitors: " . $data->fetch_assoc()['count(distinct macaddress)'];

$data = $conn->query($uniqueid);
echo "<br>Unique Visitors id: " . $data->fetch_assoc()['count(distinct visitorid)'];

/*
if ($data->num_rows > 0) {
    // output data of each row
    $i = 1;
    while($row = $data->fetch_assoc()) {
        echo "['" . $row["name"]. "', " . $row["latitude"]. "," . $row["longitude"]. ",". $i . "]";
        if ($i != $data->num_rows) {
                echo ",";
        }
        $i++;
    }
}
*/
$conn->close();
?>
