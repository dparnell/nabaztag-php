<?php

$WEATHER = 1;

# weather constants
$SUNNY = 0;
$CLOUDS = 1;
$FOG = 2;
$RAIN = 3;
$SNOW = 4;
$STORMS = 5;


function encode_array($a) {
  $result = "";
  foreach($a as $e) {
    $result .= pack("C", $e);
  }

  return $result;
}

function encode_length(&$a, $length) {
  array_push($a, $length >> 16, ($length >> 8) & 0xff, $length & 0xff);
}

function encode_clear_ambient(&$a, $type) {
  array_push($a, 0, $type);
}

function encode_set_ambient(&$a, $type, $value) {
  array_push($a, $type, $value);
}

?>
