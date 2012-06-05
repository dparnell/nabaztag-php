<?php

function sleep_rabbit_app($db, $rabbit, $app_data, &$data) {
  $now = time();
  $sleep_time = strtotime($app_data['sleep_time']);
  $wake_time = strtotime($app_data['wake_time']);
  if($now > $sleep_time || $now < $wake_time) {
    $flag = 1;
  } else {
    $flag = 0;
  }

  array_push($data, 0x0b, 0x00, 0x00, 0x01, $flag);  
}

?>