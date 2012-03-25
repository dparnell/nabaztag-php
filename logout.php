<?php
require_once('app.php');
session_destroy();
$_SESSION = array();
$info = "Logout successful";

require('index.php');
?>