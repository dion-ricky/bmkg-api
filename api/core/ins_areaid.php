<?php
  $dbcon = pg_pconnect("host=localhost user=postgres password=postgres dbname=api_test");

  if(!$dbcon){
    die("Could not connect to database!\n");
  }

  $term = $_GET['term'];
  $result = file_get_contents("http://web.meteo.bmkg.go.id/id/?option=com_forecast&task=citysearch&term=".$term, false);

  if(!$result){
    die("HTTP GET Error!");
  }

  if($result=="[]"){
    die("Empty data!");
  }

  $result = substr($result, 2, -1);
  $res = preg_split('/\},\{/', $result);

  for($x=0; $x<count($res); $x++){
    preg_match('/\"\:\"(\d+)\",\"label\"\:\"([\w\s\(\)\.\'`\-]+)\"/', $res[$x], $regres[$x]);
  }

  $values = "";
  for($x=0; $x<count($regres); $x++){

    $areaid = $regres[$x][1];
    $displayname = $regres[$x][2];

    $q = pg_query($dbcon, "SELECT * FROM areas WHERE areaid=".$areaid);
    if(pg_num_rows($q)>0){
      continue;
    }

    $values = "('".$areaid."', '".$displayname."')";
    echo $values;
    $query = "INSERT INTO areas(areaid, displayname) VALUES ".$values;
    $queryres = pg_query($dbcon, $query);
    if(!$queryres){
      die("Error query!");
    }
  }
?>
