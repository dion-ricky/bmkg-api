<?php

$dbcon = pg_pconnect("host=localhost user=postgres password=postgres dbname=api_test");

if (!$dbcon) {
    echo "An error occurred.\n";
    exit;
}
$province = $_GET['prov'];
$result = pg_query($dbcon, "SELECT * FROM regency WHERE idprovince='".$province."'");

if (!$result) {
    echo "An error occurred.\n";
    exit;
}

$array = pg_fetch_all($result);

//print_r($array);

for ($i=0; $i<count($array); $i++){
  $resloop = file_get_contents('http://127.0.0.1/api/core/ins_datastasiun.php?prov='.$array[$i]['idprovince']."&regency=".$array[$i]['idregency']);
  //echo $i.". ".$array[$i]['idprovince']."regency=".$array[$i]['idregency'];
  echo $array[$i]['idregency'].". ";
  var_dump($resloop);
  echo "<br><br>";
}


?>
