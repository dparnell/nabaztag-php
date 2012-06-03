<?php
require('app.php');

require('lib/encode.php');
require('lib/rabbit.php');

if(isset($config)) {
  $rabbit = find_rabbit($db, $_REQUEST['sn']);

  $apps = apps_for_rabbit($db, $rabbit);
} else {
  $rabbit = false;
}

# Ping request from a rabbit
$data = array(0x7f);

# encode ping interval block
array_push($data, 0x03, 0x00, 0x00, 0x01, 10);

foreach($apps as $app) {
  $name = $app['application'];

  require_once('apps/'.$name.'.php');

  $app_data = unserialize($app['data']);
  
  call_user_func($name."_rabbit_app", $db, $rabbit, $app_data, &$data);
  
  if($app['reschedule_interval']) {
    reschedule_rabbit_app($db, $app);
  } else {
    remove_rabbit_app($db, $app);
  }
}

# build up an ambient block
#$ambient = array();
#encode_set_ambient($ambient, $WEATHER, $SUNNY);
#encode_ear_positions($ambient, rand(0, 18), rand(0, 18));
#encode_clear_ambient($ambient, $WEATHER);

# now encode the actual ambient block
#array_push($data, 4);
#encode_length($data, count($ambient) + 4);
#array_push($data, 0, 0, 0, 0);
#foreach($ambient as $e) { array_push($data, $e); }

# encode end of data
array_push($data, 0xff, 0x0a);

echo encode_array($data);

if($rabbit) {
  finished_rabbit($db, $rabbit);
}



?>
