<?php

function tts_rabbit_app($db, $rabbit, $app_data, &$data) {
  // http://translate.google.com/translate_tts?q=<the text we want to hear>

  $code = "ID ".time()."\n";
  $code .= "MU http://translate.google.com/translate_tts?q=".urlencode($app_data['text']);

  $msg = array();
  encode_message($msg, $code);

  array_push($data, 10);
  encode_length($data, count($msg));
  foreach($msg as $e) { array_push($data, $e); }  
}

?>
