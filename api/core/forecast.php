<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../system/date_system.php';
include_once '../system/trim.php';
include_once '../system/temperature_conversion.php';
include_once '../database/database.php';

// create new database object
$database = new Database();
$dbconn = $database->getConnection();

// define error array
$error = array(
  "error" => array()
);

// catch GET argument
$areaid = isset($_GET['id']) ? $_GET['id'] : array_push($error['error'], "Please specify area id!");
$displayname = isset($_GET['name']) ? $_GET['name'] : "";
$temperature_unit = isset($_GET['unit']) ? $_GET['unit'] : "c";
switch($temperature_unit){
  case "F":
  case "f":
  case "K":
  case "k":
  case "C":
  case "c":
    break;
  default:
    // insert new array value if temperature unit is not recognized
    array_push($error['error'], "Temperature unit is not recognized!");
}
$masa = isset($_GET['masa']) ? preg_split("/,/", $_GET['masa']) : array("1", "2", "3", "4");

// querying data
$q = "SELECT * FROM areas WHERE areaid=".$areaid;
$query = $dbconn->prepare($q);
$query->execute();
$arr = $query->fetchAll();
// check if areaid is valid
if(count($arr)>0){
  $displayname = $arr[0]['displayname'];
} else {
  array_push($error['error'], "Area id not found!");
}

// print error array if any value exists
if(count($error['error'])>0){
  echo json_encode($error);
  die();
}

// define url for http POST request
$url = 'http://web.meteo.bmkg.go.id/city-pages/';
$data = array('search' => $displayname, 'areaid' => $areaid);

// will be sent together to the defined url
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
// push new array value if an error occur
if ($result === FALSE) {
  array_push($error['error'],"Error fetching data!");
  echo json_encode($error);
  die();
}

// regex pattern for preg_match
$re = '/\<div class\="[\w\s]+"\>\<\w+\>([\s\w\,]+)\<[\/\w]+\>\<[\/\w]+\>\<[\w\s\="]+\>\<\w+\>[\w\s\,]+\<[\/\w]+\>\<[\/\w]+\>\<[\w\s\="]+\>\<\w+\>[\w\s\,]+\<[\/\w]+\>\<[\/\w]+\>\s+\<[\/\w]+\>\s+\<[\w\s\="]+\>\<[\/\w]+\>\s+\<[\w\s\="]+\>\s+\<[\w\s\="]+\>\<[\w\s\="]+\>\<[\w]+\>([\w\s]+)\<[\/\w]+\>\<[\w\s\="\/\:\.\(\)\;]+\>\<[\w\s\="]+\>([\w\s]+)\<[\/\w]+\>\<[\w\s\="]+\>([\s\w\.]+)\<\w+\>°*\w\<[\/\w]+\>\<[\/\w]+\>\<[\w\s\="]+\>\<[\w\s\="]+\>([\s\w\/\.]+)\<[\/\w]+\>\<[\w\/]+\>\<[\w\s\="]+\>([\w\s\.]+)\<[\w\s\="\-\:\/\.\(\)\;]+\>\<[\/\w]+\>\s*\<[\w\/]+\>\<[\w\s\="]+\>([\s\w\%\.]+)\<[\w\s\="\-\:\/\.\(\)\;]+\>\s*\<[\/\w]+\>\<[\/\w]+\>\<[\/\w]+\>\<[\s\w\d\="]+\>\<[\w]+\>([\w\s]+)\<[\/\w]+\>\<[\w\s\="\/\:\.\(\)\;]+\>\<[\w\s\="]+\>([\s\w]+)\<[\/\w]+\>\<[\w\s\="]+\>([\d\s\.]+)\<\w+\>°*\w+\<[\/\w]+\>\<[\/\w]+\>\<[\w\s\="]+\>\<[\w\s\="]+\>([\s\w\/\.]+)\<[\/\w]+\>\<[\w\/]+\>\<[\w\s\="]+\>([\w\s\.]+)\<[\w\s\="\-\:\/\.\(\)\;]+\>\<[\/\w]+\>\s+\<[\w\/]+\>\<[\w\s\="]+\>([\w\s\%\.]+)\<[\w\s\="\-\:\/\.\(\)\;]+\>\s*\<[\/\w]+\>\<[\/\w]+\>\<[\/\w]+\>\<[\s\w\d\="]+\>\<[\w]+\>([\w\s]+)\<[\/\w]+\>\<[\w\s\="\/\:\.\(\)\;]+\>\<[\w\s\="]+\>([\s\w]+)\<[\/\w]+\>\<[\w\s\="]+\>([\d\s\.]+)\<\w+\>°*\w+\<[\/\w]+\>\<[\/\w]+\>\<[\w\s\="]+\>\<[\w\s\="]+\>([\s\w\/\.]+)\<[\/\w]+\>\<[\w\/]+\>\<[\w\s\="]+\>([\w\s\.]+)\<[\w\s\="\-\:\/\.\(\)\;]+\>\<[\/\w]+\>\s+\<[\w\/]+\>\<[\w\s\="]+\>([\w\s\%\.]+)\<[\w\s\="\-\:\/\.\(\)\;]+\\>\s*\<[\/\w]+\>\<[\/\w]+\>\<[\/\w]+\>\<[\s\w\d\="]+\>\<[\w]+\>([\w\s]+)\<[\/\w]+\>\<[\w\s\="\/\:\.\(\)\;]+\>\<[\w\s\="]+\>([\s\w]+)\<[\/\w]+\>\<[\w\s\="]+\>([\d\s\.]+)\<\w+\>°*\w+\<[\/\w]+\>\<[\/\w]+\>\<[\w\s\="]+\>\<[\w\s\="]+\>([\s\w\/\.]+)\<[\/\w]+\>\<[\w\/]+\>\<[\w\s\="]+\>([\w\s\.]+)\<[\w\s\="\-\:\/\.\(\)\;]+\>\<[\/\w]+\>\s+\<[\w\/]+\>\<[\w\s\="]+\>([\w\s\%\.]+)\<[\w\s\="\-\:\/\.\(\)\;]+\>\s*\<[\/\w]+\>\<[\/\w]+\>\<[\/\w]+\>\<[\/\w]+\>\<[\w\s\="]+\>\<[\w\s\="]+\>\<[\w]+\>/';

// '

// the comment above is actually not necessary!

// regex matching with pattern
preg_match($re, $result, $regres);

// if array from regex match is empty then print error
if(count($regres)==0){
  array_push($error['error'], "Regex match error!");
  echo json_encode($error);
  die();
}

// structuring fetched data into array
$print_json=array(
  "result" => array(
    "tanggal" => expandDate($regres[1]),
    "areaid" => $areaid,
    "area_name" => advTrim(trim($displayname)),
    "temperature_unit" => $temperature_unit
  )
);

// structuring data
$count = count($masa);
for($i=0; $i<$count; $i++){
  switch($masa[$i]){
    case "1":
      $start = 3;
      $text = "Pagi";
      break;
    case "2":
      $start = 9;
      $text = "Siang";
      break;
    case "3":
      $start = 15;
      $text = "Malam";
      break;
    case "4":
      $start = 21;
      $text = "Dini Hari";
      break;
    default:
      array_push($error['error'], "Masa tidak valid!");
      echo json_encode($error);
      die();
  }
  $data = array(
    "description" => trim($regres[$start]),
    "suhu" => temperatureConvert($temperature_unit, trim($regres[$start+1])),
    "wind_speed" => dblSpace(trim($regres[$start+2])),
    "wind_dir" => trim($regres[$start+3]),
    "kelembapan" => trim($regres[$start+4])
  );
  $print_json['result'][$text] = $data;
}

// printing json from array
echo json_encode($print_json);
?>
