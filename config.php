<?php

session_start();

$APP_DIR = dirname(__FILE__);
$CONFIG_FILE = $APP_DIR."/app_config.php";

function save_config($config) {
  global $CONFIG_FILE;

  $data  = "<?php\n";
  $data .= "$"."config = ".var_export($config, true).";\n";
  $data .= "?>";

  $f = fopen($CONFIG_FILE, 'w') or die("Could not open config file '$CONFIG_FILE' for writing");
  fwrite($f, $data);
  fclose($f);
}

if(file_exists($CONFIG_FILE)) {
  require_once($CONFIG_FILE);
}

function config_value($key, $default = null) {
  global $config;


  if(isset($config) && array_key_exists($key, $config)) {
    return $config[$key];
  }

  return $default;
}

?>