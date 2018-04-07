<?php
include_once '../database/database.php';

$db = new Database();
$dbconn = $db->getConnection();
$host = getenv('HOST');

$abjad = array(
  1 => 'a',
  2 => 'b',
  3 => 'c',
  4 => 'd',
  5 => 'e',
  6 => 'f',
  7 => 'g',
  8 => 'h',
  9 => 'i',
  10 => 'j',
  11 => 'k',
  12 => 'l',
  13 => 'm',
  14 => 'n',
  15 => 'o',
  16 => 'p',
  17 => 'q',
  18 => 'r',
  19 => 's',
  20 => 't',
  21 => 'u',
  22 => 'v',
  23 => 'w',
  24 => 'x',
  25 => 'y',
  26 => 'z'
);

for($a=1; $a<27;$a++){
  // INSERT INTO areas
  $url = 'http://api-bmkg.herokuapp.com/api/core/ins_areaid.php?term='.$abjad[$a];
  file_get_contents($url);
  echo $abjad[$a].'. '.$url.'<br><br>';
  //sleep(1);
}


/*
for($a=1; $a<34; $a++){
  file_get_contents('http://'.$host.'/api/core/ins_regency.php?prov='.$a);
}

for($a=1; $a<34; $a++){
  $province = $a;
  $qry = $dbconn->prepare("SELECT * FROM regency WHERE idprovince='".$province."'");
  $qry->execute();

  $array = $qry->fetchAll();
  $count = count($array);

  for($i=0; $i<$count; $i++){
    // INSERT INTO station_data
    $resloop = file_get_contents('http://'.$host.'/api/core/ins_datastasiun.php?prov='.$array[$i]['idprovince']."&regency=".$array[$i]['idregency']);
    echo $array[$i]['idregency'].". ".$array[$i]['name']." {<br>";
    var_dump($resloop);
    echo "<br>}<br><br>";
  }
  //sleep(1);
}
*/
?>
