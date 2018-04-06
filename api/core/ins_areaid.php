<?php
include_once '../database/database.php';

$database = new Database();
$dbconn = $database->getConnection();

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

  $q = "SELECT * FROM areas WHERE areaid=".$areaid;
  $select_area = $dbconn->prepare($q);
  $select_area->execute();
  if(count($select_area->fetchAll())>0){
    continue;
  }

  $values = "('".$areaid."', '".$displayname."')";

  $insertqry = "INSERT INTO areas(areaid, displayname) VALUES ".$values;
  $insert = $dbconn->prepare($insertqry);
  $insert->execute();

}
?>
