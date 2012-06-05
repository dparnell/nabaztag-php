<?php
require('app.php');
require('lib/rabbit.php');

// TODO: add code here to perform Speech to text and then perform the appropriate response

if(isset($config)) {
  $rabbit = find_rabbit($db, $_REQUEST['sn']);

  $app = array('application' => 'tts', 'data' => serialize(array('text' => 'not yet implemented')));
 
  save_rabbit_app($db, $rabbit, $app);
}

?>