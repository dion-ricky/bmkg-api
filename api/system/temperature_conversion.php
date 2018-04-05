<?php
function temperatureConvert($unit, $temp){
  $temp = (int)$temp;
  $convt = -1;
  switch($unit){
    case "F":
    case "f":
      $convt = ($temp*9/5)+32;
      break;
    case "K":
    case "k":
      $convt = $temp+273;
      break;
    case "C":
    case "c":
      $convt = $temp;
      break;
  }
  return $convt;
}
?>
