<?php

function tts_rabbit_app($db, $rabbit, $app_data) {
  global $ping_result_data;
  // http://translate.google.com/translate_tts?q=<the text we want to hear>

  $code = "ID ".time()."\n";
  $code .= "MU http://translate.google.com/translate_tts?ie=utf-8&tl=en&q=".urlencode($app_data['text']);

  $msg = array();
  encode_message($msg, $code);

  array_push($ping_result_data, 10);
  encode_length($ping_result_data, count($msg));
  foreach($msg as $e) { array_push($ping_result_data, $e); }  
}

?>
