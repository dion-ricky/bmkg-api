<?php
include_once "../database/database.php";

//Catch GET argument
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : die("Masukkan tahun!");
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : die("Masukkan bulan!");
$parameter = isset($_GET['param']) ? $_GET['param'] : die("Masukkan parameter!");
$province = isset($_GET['prov']) ? $_GET['prov'] : die("Masukkan provinsi!");
$regency = isset($_GET['regency']) ? $_GET['regency'] : die("Masukkan regency!");

$database = new Database();
$dbcon = $database->getConnection();

$url = 'http://dataonline.bmkg.go.id/ketersediaan_data/get_ketersediaan_data_tabel';
$data = array('province' => $province, 'regency' => $regency, 'parameter' => $parameter, 'dari_bulan' => $bulan, 'sampai_bulan' => $bulan, 'dari_tahun' => $tahun, 'sampai_tahun' => $tahun, 'station_name' => 'Stasiun Meteorologi Maritim Perak II');
//province=15&regency=260&parameter=temp_min&dari_bulan=03&sampai_bulan=03&dari_tahun=2018&sampai_tahun=2018

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { /* Handle error */ }

$result = substr($result, 26, -2);
$res = preg_split('/\,/',$result);

for ($x=3; $x<count($res)-1; $x++) {
	preg_match('/(check|times|\" - \")/', $res[$x], $regres[$x-3]);
}

$values = "";
for ($x=0; $x<count($regres); $x++) {
	
	if($x == count($regres)-1) {
		$comma = "";
	} else {
		$comma = ", ";
	}
	
	switch ($regres[$x][1]) {
		case "check":
			$val = '1';
			break;
		
		case "times":
			$val = '0';
			break;
		
		default:
			$val = '-1';
	}
	
	$values = $values.$val.$comma;
}

$query = "INSERT INTO ketersediaan(tahun, bulan, values) VALUES ('".$tahun."', '".$bulan."', '{".$values."}')";
$stmt = $dbcon->prepare($query);
$stmt->execute();
?> 
