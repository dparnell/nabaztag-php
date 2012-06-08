<?php

function message_rabbit_app($db, $rabbit, $app_data, &$data) {
  $msg = array();
  encode_message($msg, $app_data['code']);

  array_push($data, 10);
  encode_length($data, count($msg));
  foreach($msg as $e) { array_push($data, $e); }  
}


?>