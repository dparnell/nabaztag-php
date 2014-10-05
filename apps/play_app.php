<?php

array_push($multi_instance_apps, 'play');

function play_rabbit_app($db, $rabbit, $app_data) {
    encode_play_media(process_url($app_data['url']));
}

?>