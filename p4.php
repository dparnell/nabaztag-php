<?php
require('lib/encode.php');
# Ping request from a rabbit
$data = array(0x7f);

# encode ping interval block
array_push($data, 0x03, 0x00, 0x00, 0x01, 10);

# build up an ambient block
$ambient = array();
encode_set_ambient($ambient, $WEATHER, $SUNNY);
encode_ear_positions($ambient, rand(0, 18), rand(0, 18));
#encode_clear_ambient($ambient, $WEATHER);
array_push($data, 4);
encode_length($data, count($ambient) + 4);
array_push($data, 0, 0, 0, 0);
foreach($ambient as $e) { array_push($data, $e); }

# encode end of data
array_push($data, 0xff, 0x0a);

echo encode_array($data);

?>
