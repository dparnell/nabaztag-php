<?php

function weather_command($db, $rabbit) {
  $app = app_for_rabbit_by_name($db, $rabbit, 'weather');

  if($app['data']) {
    $error = null;

    require('weather_app.php');

    $app_data = unserialize($app['data']);

    $base = config_value('app-media-base');
    if($base) {
      $xml = weather_data_for_location($app_data['id'], $app_data['key'], $app_data['city']);
      $doc = simplexml_load_string($xml);

      $lang = "us"; // TODO: make this configurable
      $scale = "c"; // TODO: make this configurable

      $weather_code = weather_code_for_doc($doc);
      $weather_temp = weather_temp_for_doc($doc, $scale);

      // error_log("code = $weather_code, temp = $weather_temp");

      $code = "ID ".time()."\n";
      $code .= "MU ".$base."weather/".$lang."/signature.mp3\n";
      $code .= "MW\n";
      $code .= "MU ".$base."weather/".$lang."/today.mp3\n";
      $code .= "MW\n";
      $code .= "MU ".$base."weather/".$lang."/sky/".$weather_code.".mp3\n";
      $code .= "MW\n";
      $code .= "MU ".$base."weather/".$lang."/temp/".$weather_temp.".mp3\n";
      $code .= "MW\n";
      $code .= "MU ".$base."weather/".$lang."/degree.mp3\n";
      $code .= "MW\n";

      $app = array('application' => 'message', 'data' => serialize(array('code' => $code)));
      save_rabbit_app($db, $rabbit, $app);
    } else {
      $error = 'Media base not set up';
    }
  } else {
    $error = 'Weather app not configured';
  }

  if($error) {
    $app = array('application' => 'tts', 'data' => serialize(array('text' => $error)));
    save_rabbit_app($db, $rabbit, $app);
  }

}


?>
