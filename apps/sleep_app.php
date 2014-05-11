<?php

function sleep_rabbit_app($db, $rabbit, $app_data) {
  global $ping_result_data;

  $now = strtotime("now");
  $sleep_time = strtotime($app_data['sleep_time']);
  $wake_time = strtotime($app_data['wake_time']);
  if($now > $sleep_time || $now < $wake_time) {
    $flag = 1;
  } else {
    $flag = 0;
  }

  if(app_value('flag', 0) != $flag) {
    $app_data['flag'] = $flag;

    if($flag) {
      $to_play = app_value('sleep_sound', '');
    } else {
      $to_play = app_value('wake_sound', '');
    }

    $result = $app_data;
  } else {
    $to_play = '';
    $result = false;
  }

  if($to_play != '') {
    encode_play_media($to_play);
  }

  array_push($ping_result_data, 0x0b, 0x00, 0x00, 0x01, $flag);

  return $result;
}

?>
