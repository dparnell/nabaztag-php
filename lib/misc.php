<?php

$multi_instance_apps = [];

function logged_in() {
  return isset($_SESSION) and array_key_exists('user', $_SESSION);
}

function is_admin() {
  return logged_in() && $_SESSION['is-admin'] == 'T';
}

function current_user_id() {
  if(logged_in()) {
    return $_SESSION['user'];
  }

  return null;
}

function rabbit_name($rabbit) {
  return $rabbit['name'] ? $rabbit['name'] : $rabbit['mac_id'];
}

function rabbit_is_online($rabbit) {
  return $rabbit['last_seen'] > time() - 600;
}

function rabbit_status($rabbit) {
  if(rabbit_is_online($rabbit)) {
    return 'Online';
  }

  return 'Offline';
}

function timezone_select($name, $value) {
  $zones = timezone_identifiers_list();

  echo '<select name="'.$name.'" id="'.$name.'">';
  foreach($zones as $zone) {
    echo '<option value="'.$zone.'"';
    if($zone == $value) {
      echo ' selected="selected"';
    }
    echo ">$zone</option>";
  }
  echo '</select>';
}

function cache_dir() {
  global $APP_DIR;

  return $APP_DIR."/cache";
}

function is_cache_available() {
  return is_writable(cache_dir());
}

function cache_get($key, $max_age = 3600) {
  $cache_file = cache_dir().'/'.sha1($key);

  if(file_exists($cache_file)) {
    $access_time = filemtime($cache_file);
    if($access_time && time()-$access_time < $max_age) {
      return file_get_contents($cache_file);
    }
  }

  return null;
}

function cache_put($key, $value) {
  $cache_file = cache_dir().'/'.sha1($key);
  file_put_contents($cache_file, $value);
}

function cache_remove($key) {
  $cache_file = cache_dir().'/'.sha1($key);
  if(file_exists($cache_file)) {
    unlink($cache_file);
  }
}

function app_value($key, $default = null) {
  global $app_data;

  if(array_key_exists($key, $app_data)) {
    return $app_data[$key];
  }

  return $default;
}

?>