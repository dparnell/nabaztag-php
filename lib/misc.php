<?php

$multi_instance_apps = [];
$sleepy_instance_apps = [];

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

function process_url($url) {
    $result = preg_replace_callback("/\{([^\}]+)\}/", function($matches) {
        $parts = explode(' ', $matches[1]);
        switch($parts[0]) {
        case "random":
            return rand(0, $parts[1]);
        case "choose":
            return $parts[rand(1, count($parts)-1)];
        case "hour":
            return getdate(time())['hours'];
        }
    }, $url);

    return strval($result);
}

function hex_dump($data)
{
    static $from = '';
    static $to = '';

    static $width = 16; # number of bytes per line

    static $pad = '.'; # padding for non-visible characters

    if ($from==='')
        {
            for ($i=0; $i<=0xFF; $i++)
                {
                    $from .= chr($i);
                    $to .= ($i >= 0x20 && $i <= 0x7E) ? chr($i) : $pad;
                }
        }

    $hex = str_split(bin2hex($data), $width*2);
    $chars = str_split(strtr($data, $from, $to), $width);

    $offset = 0;
    foreach ($hex as $i => $line)
        {
            error_log(sprintf('%6X',$offset).' : '.implode(' ', str_split($line,2)) . ' [' . $chars[$i] . ']');
            $offset += $width;
        }
}

?>