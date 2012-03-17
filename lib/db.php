<?php
try {
  $dir = dirname(__FILE__)."/../db";
  $db_file = "sqlite:/$dir/nabaztag.sqlite3";
  $db = new PDO($db_file);
} catch(PDOException $e) {
  $error = "Could not connect to database: $db_file - ".$e->getMessage();
  $db = null;
}
?>