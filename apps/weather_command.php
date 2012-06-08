<?php

function weather_command($db, $rabbit) {
  $app = app_for_rabbit($db, $rabbit, 'weather');

  if($result['data']) {
    $error = null;

    require('weather_app.php');

    $app_data = unserialize($app['data']);
    $xml = weather_data_for_location($app_data['city']);
    
    $base = config_value('app-media-base');
    if($base) {
      $code = "ID ".time()."\n";
      
    
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