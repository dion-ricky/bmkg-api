<?php
date_default_timezone_set("Asia/Jakarta");

function today(){
  return date("d-m-Y");
}

function expandDate($str){
  $str = preg_split("/[\s,]+/", $str);
  switch($str[2]){
    case "Jan":
      $bulan = "Januari";
      break;
    case "Feb":
      $bulan = "Februari";
      break;
    case "Mar":
      $bulan = "Maret";
      break;
    case "Apr":
      $bulan = "April";
      break;
    case "Mei":
      $bulan = "Mei";
      break;
    case "Jun":
      $bulan = "Juni";
      break;
    case "Jul":
      $bulan = "Juli";
      break;
    case "Agu":
    case "Ags":
      $bulan = "Agustus";
      break;
    case "Sep":
      $bulan = "September";
      break;
    case "Okt":
      $bulan = "Oktober";
      break;
    case "Nov":
      $bulan = "November";
      break;
    case "Des":
      $bulan = "Desember";
      break;
    default:
      $bulan = $str[2];
  }
  return ($str[1]." ".$bulan." ".date("Y"));
}


?>
