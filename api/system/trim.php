<?php
function advTrim($str) {
  $pattern = "/([\w\s]+)\(\s([\w\s\)]+)/";
  $replace = "$1($2";
  return preg_replace($pattern, $replace, $str);
}

function dblSpace($str) {
  $pattern = "/(.*)\s{2,}(.*)/";
  $replace = "$1 $2";
  return preg_replace($pattern, $replace, $str);
}

?>
