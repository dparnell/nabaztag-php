<?php

array_push($multi_instance_apps, 'play');

function play_rabbit_app($db, $rabbit, $app_data) {
  global $ping_result_data;

  $code = "ID ".time()."\n";
  $code .= "MU ".process_url($app_data['url'])."\n";
  $code .= "MW\n";

  $msg = array();
  encode_message($msg, $code);

  array_push($ping_result_data, 10);
  encode_length($ping_result_data, count($msg));
  foreach($msg as $e) { array_push($ping_result_data, $e); }
}


?>