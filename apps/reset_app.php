<?php

function reset_rabbit_app($db, $rabbit, $app_data) {
  global $ping_result_data;
  error_log("Sending reset to rabbit: ".$rabbit['mac_id']);

  array_push($ping_result_data, 0x09, 0x00, 0x00, 0x00);  
}

?>