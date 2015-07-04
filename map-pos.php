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

/**
 * Data for the markers consisting of a name, a LatLng and a zIndex for
 * the order in which these markers should display on top of each
 * other.
 */

<?php
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
echo "];";
$conn->close();
?>


function setMarkers(map, locations) {
  // Add markers to the map

  // Marker sizes are expressed as a Size of X,Y
  // where the origin of the image (0,0) is located
  // in the top left of the image.

  // Origins, anchor positions and coordinates of the marker
  // increase in the X direction to the right and in
  // the Y direction down.
  var image = {
    url: 'wifi.png',
    // This marker is 20 pixels wide by 32 pixels tall.
    size: new google.maps.Size(32, 32),
    // The origin for this image is 0,0.
    origin: new google.maps.Point(0,0),
    // The anchor for this image is the base of the flagpole at 0,32.
    anchor: new google.maps.Point(0,0)
  };
  // Shapes define the clickable region of the icon.
  // The type defines an HTML &lt;area&gt; element 'poly' which
  // traces out a polygon as a series of X,Y points. The final
  // coordinate closes the poly by connecting to the first
  // coordinate.
  var shape = {
      coords: [1, 1, 1, 20, 18, 20, 18 , 1],
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
  }
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>
