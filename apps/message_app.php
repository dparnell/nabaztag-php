<?php

function message_rabbit_app($db, $rabbit, $app_data) {
  global $ping_result_data;
  $msg = array();
  encode_message($msg, $app_data['code']);

  array_push($ping_result_data, 10);
  encode_length($ping_result_data, count($msg));
  foreach($msg as $e) { array_push($ping_result_data, $e); }  
}


?>