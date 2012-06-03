<?php

function reset_rabbit_app($db, $rabbit, $app, &$data) {
  error_log("Sending reset to rabbit: ".$rabbit['mac_id']);

  array_push($data, 0x09, 0x00, 0x00, 0x00);  
}

?>