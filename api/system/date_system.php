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

function normalDate($str){
  preg_match("/[\w,]+\s*(\d+)\s*(\w+)/", $str, $regstr);
  switch($regstr[2]) {
    case "Jan":
      $bulan = "1";
      break;
    case "Feb":
      $bulan = "2";
      break;
    case "Mar":
      $bulan = "3";
      break;
    case "Apr":
      $bulan = "4";
      break;
    case "Mei":
      $bulan = "5";
      break;
    case "Jun":
      $bulan = "6";
      break;
    case "Jul":
      $bulan = "7";
      break;
    case "Agu":
    case "Ags":
      $bulan = "8";
      break;
    case "Sep":
      $bulan = "9";
      break;
    case "Okt":
      $bulan = "10";
      break;
    case "Nov":
      $bulan = "11";
      break;
    case "Des":
      $bulan = "12";
      break;
  }
  return $regstr[1].'-'.$bulan.'-'.date("Y");
}
?>
