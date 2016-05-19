<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diseno_electronico";

$Selector=htmlspecialchars($_COOKIE["selector"]);

$desde =htmlspecialchars($_POST["desde"]);//"2016-04-27 10:58:00.0";
$hasta =htmlspecialchars($_POST["hasta"]);//"2016-04-27 15:12:00.0";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);
/* ----------------array de fechas y horas---------------*/
// query
$querytime = mysqli_query($conn, "SELECT datetime 
FROM diseno_electronico.telemetry
WHERE datetime between '$desde'  and '$hasta' and idtracker = '$Selector' order by datetime;");
// set array
$time = array();

// look through query
while($row = mysqli_fetch_assoc($querytime)){
  $time[] = $row;
}

$datetime = array();
$datetime = array_map('current', $time);

/* ----------------array de variables medidas--------------------*/
//query
$querylong = mysqli_query($conn, "SELECT variable 
FROM diseno_electronico.telemetry
WHERE datetime between '$desde'  and '$hasta' and idtracker = '$Selector' order by datetime;");
// set array
$var = array();

// look through query
while($row = mysqli_fetch_assoc($querylong)){
  $var[] = $row;
}

$variables = array();
$variables = array_map('current', $var);
?>
<html>
  <head>

    <!-- esta es una etiqueta de responsive design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- este es el estílo CSS de la página, archivo externo-->
        <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css" >
        <link rel="stylesheet" type="text/css" href="pagina.css" >
        <script src="jquery.js"></script>
        <script src="jquery.datetimepicker.full.min.js"></script>
        <!-- este script define las funciones del picker-->
        <script src="pickers.js"></script>
        <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3qSjTtUgl_NkPvyK2ckURE88eNuRuj1E&callback=initMap">
    </script>
        
        <!--Import Google Icon Font-->
            <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
            <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

        <!--Let browser know website is optimized for mobile-->
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    var dt = <?php echo json_encode($datetime); ?>;
    var vr = <?php echo json_encode($variables); ?>;
    var datetime   = dt.toString().split(",");
    var variable   = vr.toString().split(",");
    var arr = []; var array = [];

      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        array[0] = ['Fecha-Hora', 'Variable'];
        console.log(array);
        for (var i = 1; i < variable.length; i++) {
            arr = [String(datetime[i]), parseInt(variable[i])];
            console.log(arr);
            array.push(arr);
          }
        var data = google.visualization.arrayToDataTable(array);
        var options = {
          title: 'Variable Medida V Tiempo',
          legend: { position: 'none' },
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
  <div class="navbar-fixed">
        <nav>
            <div class="nav-wrapper orange">
                <a href="#" class="brand-logo">TRTracking</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
<!--            <li><a href="sass.html">Históricos</a></li>
                <li><a href="badges.html">Tiempo Real</a></li> -->
                </ul>
            </div>
        </nav>
        </div>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>

</html>