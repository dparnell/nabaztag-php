<?php
require('app.php');
require('lib/rabbit.php');
require('lib/encode.php');

if(isset($config)) {
  $rabbit = find_rabbit($db, $_REQUEST['sn']);
  $tag = rfid_tag_for_rabbit($db, $rabbit, $_REQUEST['t']);
  $tag['last_seen'] = time();

  save_rfid_tag($db, $tag);

  $code = process_commands($tag['command'])."\n";

  $app = array('application' => 'message', 'data' => serialize(array('code' => $code)));
  save_rabbit_app($db, $rabbit, $app);
}
?>