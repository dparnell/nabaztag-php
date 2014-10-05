<?php
require('app.php');

require('lib/encode.php');
require('lib/rabbit.php');

if(isset($config)) {
  $rabbit = find_rabbit($db, $_REQUEST['sn']);
  date_default_timezone_set($config['server-timezone']);
  $apps = scheduled_apps_for_rabbit($db, $rabbit);
} else {
  $rabbit = false;

  $apps = array();
}

$seen_apps = array();

# Ping request from a rabbit
$ping_result_data = array(0x7f);

# encode ping interval block
array_push($ping_result_data, 0x03, 0x00, 0x00, 0x01, 10);

foreach($apps as $app) {
  $name = $app['application'];
  if(!array_key_exists($name, $seen_apps)) {
      $seen_apps[$name] = true;
      $success = true;
      $app_ran = false;
      try {
          require_once('apps/'.$name.'_app.php');

          if(($rabbit['asleep'] == 0) || in_array($name, $sleepy_instance_apps)) {
              $app_ran = true;
              $app_data = unserialize($app['data']);

              $result = call_user_func($name."_rabbit_app", $db, $rabbit, $app_data);

              if($result) {
                  $app['data'] = serialize($result);
                  save_rabbit_app($db, $rabbit, $app);
              }
          }
      } catch (Exception $e) {
          $success = false;
          error_log("Something went wrong in an app: ".$e->getMessage());
      }

      if($app_ran) {
          if($app['reschedule_interval'] && $success) {
              reschedule_rabbit_app($db, $app);
          } else {
              remove_rabbit_app($db, $app);
          }
      }
  }
}

# encode end of data
array_push($ping_result_data, 0xff, 0x0a);

#hex_dump(encode_array($ping_result_data));
echo encode_array($ping_result_data);

if($rabbit) {
  finished_rabbit($db, $rabbit);
}

?>