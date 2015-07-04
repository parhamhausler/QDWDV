<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Wifi Map</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script>

function initialize() {
  var mapOptions = {
    zoom: 11,
    center: new google.maps.LatLng(-38.1673076,144.4993399) //Set center to geelong
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);

  setMarkers(map, ap);
}


<?php
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


$sql = "select distinct locationid, latitude, longitude, name from wifi where longitude != \"\"";
$data = $conn->query($sql);
echo "var ap = [";
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

//create click listners

echo "];";
$conn->close();
?>


function getstats(name)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    	//document.getElementById("sidepanel").innerHTML=xmlhttp.responseText;
	alert(xmlhttp.responseText);
    }
  }
xmlhttp.open("GET","stats.php?apname="+name,true);
xmlhttp.send();
}


function setMarkers(map, locations) {
  var image = {
    url: 'wifi.png',
    size: new google.maps.Size(32, 32),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(0,0)
  };
  var shape = {
      coords: [0, 0, 32, 0, 32,32, 0, 32],
      type: 'poly'
  };
  for (var i = 0; i < locations.length; i++) {
    var ap = locations[i];
    var myLatLng = new google.maps.LatLng(ap[1], ap[2]);
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: image,
        shape: shape,
        title: ap[0],
        zIndex: ap[3]
    });
    google.maps.event.addListener(marker, 'click', function() {
    if (map.getZoom() <17) {
    	map.setZoom(17);
    }
    map.setCenter(this.getPosition());
    getstats(this.getTitle());
    //alert(this.getTitle());
    });
  }
}
google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>
