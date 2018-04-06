<?php
include_once '../database/database.php';

$db = new Database();
$dbconn = $db->getConnection();

$host = getenv('HOST');
$province = $_GET['prov'];
$qry = $dbconn->prepare("SELECT * FROM regency WHERE idprovince='".$province."'");
$qry->execute();

$array = $qry->fetchAll();
$count = count($array);

for($i=0; $i<$count; $i++){
  $resloop = file_get_contents('http://.'$host'./api/core/ins_datastasiun.php?prov='.$array[$i]['idprovince']."&regency=".$array[$i]['idregency']);
  echo $array[$i]['idregency'].". ".$array[$i]['name']." {<br>";
  var_dump($resloop);
  echo "<br>}<br><br>";
}

?>
