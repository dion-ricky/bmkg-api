<?php
include_once '../database/database.php';

$database = new Database();
$dbconn = $database->getConnection();

$province = $_GET['prov'];

$url = 'http://dataonline.bmkg.go.id/mcstation_metadata/get_regency';

// set content data to be sent over HTTP POST request
$data = array('idrefprovince' => $province);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

// send data and fetch the response
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) {
  die("Error fetching data!");
}

// remove unnecessary data
$result = substr($result, 83, -21);
// split response data
$res = preg_split('/\",\"/',$result);

for ($x=0; $x<count($res); $x++) {
	preg_match('/\=\'(\d+)[\'\s>*]+([\w\s\.*\-*]+)[<\\\\\/\w>\"]+/', $res[$x], $regres[$x]);
}

$values = "";
$count = count($regres);
for ($x=0; $x<$count; $x++) {

	if($x == $count-1) {
		$comma = "";
	} else {
		$comma = ", ";
	}

	$idregency = $regres[$x][1];
  $idprovince = $province;
  $name = $regres[$x][2];

	$values = $values."('".$idregency."', '".$province."', '".$name."')".$comma;
}

$query = "INSERT INTO regency(idregency, idprovince, name) VALUES ".$values;
$stmt = $dbconn->prepare($query);
$stmt->execute();
?>
