<?php
require_once('lib/encode.php');

array_push($multi_instance_apps, 'tts');

function tts_rabbit_app($db, $rabbit, $app_data) {
  global $ping_result_data;
  $code = process_commands("TTS ".$app_data['text']);

  $msg = array();
  encode_message($msg, $code);

  array_push($ping_result_data, 10);
  encode_length($ping_result_data, count($msg));
  foreach($msg as $e) { array_push($ping_result_data, $e); }
}

?>
