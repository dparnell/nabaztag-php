<?php
require_once('config.php');
if(!isset($config)) {
  require_once('setup.php');
  exit();
}

require_once('lib/db.php');
?>