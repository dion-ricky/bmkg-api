<?php
include_once '../database/database.php';

$database = new Database();
$dbconn = $database->getConnection();
$province = $_GET['prov'];
$regen = $_GET['regency'];

$url = 'http://dataonline.bmkg.go.id/mcstation_metadata/get_data';

$data = array('idrefprovince' => $province, 'idrefregency' => $regen, 'type' => '');

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) {
  die("Error fetching data!");
}

if (substr($result, 16, 17) == 0) {
	die("Tidak ada data!");
}

$result = substr($result, 27, -2);
$res = preg_split('/\],\[/',$result);

for ($x=0; $x<count($res); $x++) {
	preg_match('/"([\d\w\\\\*]+)","([\(\)\'*\-*\w\s\/*\\\\*\.*,*\+`\;\d]+)","([\w_*]+)","([\w\s]+)","([\w\s\.*]+)","([\w\s\.*]+)","(-*[\d\.*]+)","(-*[\d\.*]+)","*([-\d]+)"*,"*([\w\d\.*]+)"*,"*([\w\d\.*]+)"*,"*([\w\d\.*]+)"*,"*([\+*\w\d\.*:*]+)"*/', $res[$x], $regres[$x]);
}

$values = "";
for ($x=0; $x<count($regres); $x++) {

	if($x == count($regres)-1) {
		$comma = "";
	} else {
		$comma = ", ";
	}

	$station_no = $regres[$x][1];
	$station_name = preg_replace('/\'/','`',$regres[$x][2]);
	$type = $regres[$x][3];
	$region = $regres[$x][4];
	$province = $regres[$x][5];
	$regency = $regres[$x][6];
	$latitude = $regres[$x][7]=='null' ? -1 : $regres[$x][7];
	$longitude = $regres[$x][8]=='null' ? -1 : $regres[$x][8];
	$altitude = $regres[$x][9]=='null' ? -1 : $regres[$x][9];
	$soil = $regres[$x][10]=='null' ? -1 : $regres[$x][10];
	$exposure = $regres[$x][11]=='null' ? -1 : $regres[$x][11];
	$land_use = $regres[$x][12]=='null' ? -1 : $regres[$x][12];
	$timezone = $regres[$x][13]=='null' ? -1 : $regres[$x][13];

	$values = $values."('".$station_no."', '".$station_name."', '".$type."', '".$region."', '".$province."', '".$regency."', '".$latitude."', '".$longitude."', '".$altitude."', '".$soil."', '".$exposure."', '".$land_use."', '".$timezone."')".$comma;
}

$query = "INSERT INTO station_data(station_no, station_name, type, region, province, regency, latitude, longitude, altitude, soil, exposure, land_use, timezone) VALUES ".$values;
$stmt = $dbconn->prepare($query);
$stmt->execute();
?>
