<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Wifi Map</title>
    <link rel="stylesheet" text="test/css" href="styles.css">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script>

function initialize() {
  var mapOptions = {
    zoom: 11,
    center: new google.maps.LatLng(-38.1673076,144.4993399) //Set center to geelong
  }
  map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
  setMarkers(map, ap);
  return map;
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
	document.getElementById("side-panel").innerHTML = "Loading";
	document.getElementById("side-panel").innerHTML = xmlhttp.responseText
	if (name != "main") {
		getphonestats(name);
	}
	//alert(xmlhttp.responseText);
    }
  }
xmlhttp.open("GET","stats.php?apname="+name,true);
xmlhttp.send();
}

function getphonestats(name){
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
      var vars = xmlhttp.responseText.split(',');
       DrawPhoneStats(vars[0],vars[1],vars[2]);
    }
  }
xmlhttp.open("GET","phonestats.php?apname="+name,true);
xmlhttp.send();
}

function DrawPhoneStats(iphone, android, other){
  var BarHeight = 25;
  var HeightOffset = 15;
  var canvas = document.getElementById("theCanvas");
  var ctx = canvas.getContext("2d");
  var iphoneInt = parseInt(iphone);
  var androidInt = parseInt(android);
  var otherInt = parseInt(other);
  var max = iphoneInt;
  if(androidInt > max){
    max = androidInt;
  }
  if(otherInt > max){
    max = otherInt;
  }
  var mulitplier = canvas.width / max;
  ctx.fillStyle = "#FF0000";
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.fillRect(0,HeightOffset,iphoneInt * mulitplier,BarHeight);
  ctx.fillStyle = "#00FF00";
  ctx.fillRect(0,HeightOffset+BarHeight,androidInt * mulitplier,BarHeight);
  ctx.fillStyle = "#0000FF";
  ctx.fillRect(0,HeightOffset+ 2*BarHeight,otherInt * mulitplier,BarHeight);
  ctx.fillStyle = "#000000";
  ctx.font = "12px Arial";
  ctx.fillText("Popularity of Operating System", 0,10);
  ctx.fillText("iPhone",0,BarHeight/2 + 10 + HeightOffset); 
  ctx.fillText("Android",0,BarHeight/2 + BarHeight + 10 + HeightOffset);
  ctx.fillText("Other",0,BarHeight/2 + 2* BarHeight + 10 + HeightOffset);
}

function setMarkers(map, locations) {
  var image = {
    url: 'wifi.png',
    size: new google.maps.Size(32, 32),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(16,16)
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
      var CircleOptions = {
      strokeColor: '#FF0000',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#FF0000',
      fillOpacity: 0.35,
      map: map,
      center: myLatLng,
      radius: 50
      }; 
      var Circle = new google.maps.Circle(CircleOptions); 
    google.maps.event.addListener(marker, 'click', function() {
    if (map.getZoom() <17) {
    	map.setZoom(17);
    }
    map.setCenter(this.getPosition());
    document.getElementById("side-panel").innerHTML = "Loading";
    getstats(this.getTitle());
    //alert(this.getTitle());
    });
  }
}
google.maps.event.addDomListener(window, 'load', initialize);

function setinitial() {
	getstats("main");
	map.setZoom(11);
        latlng = new google.maps.LatLng(-38.1673076,144.4993399)
	map.setCenter(latlng); //Set center to geelong
	var canvas = document.getElementById("theCanvas");
	var context = canvas.getContext("2d");
	context.clearRect(0, 0, canvas.width, canvas.height);
}
function goto(lat, lng) {
	latlng = new google.maps.LatLng(lat,lng)
	map.setCenter(latlng);
	if (map.getZoom() < 19) {
		map.setZoom(19);
	}
}

    </script>
  </head>
  <body onload="setinitial();">
    <div id="map-canvas"></div>
    <div id="side-panel"></div>
    <div id="side-panel-canvas">
    <canvas id="theCanvas">Your browser doesn't support canvas, sorry!</canvas>
    <div>
  </body>
</html>
