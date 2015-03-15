<?php

function weather_data_for_location($city) {
    $select = "select units, item.forecast from weather.forecast where woeid in (select woeid from geo.places(1) where text=\"".$city."\") limit 1";
    $url = "https://query.yahooapis.com/v1/public/yql?q=".urldecode($select)."&format=xml&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
    $xml = cache_get($url);
    if($xml == null) {
        # error_log("Fetching weather data for: ".$city);
        # error_log($url);

        $session = curl_init($url);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $xml = curl_exec($session);
        curl_close($session);

        cache_put($url, $xml);
    } else {
        //    error_log("Using cached weather data for: ".$city);
    }

    return $xml;
}

function weather_temp_for_doc($doc, $scale = 'C') {
    $data_scale = (string)($doc->xpath("//query/results/channel/*[local-name()='units']/@temperature")[0]);
    $value = (string)($doc->xpath("//query/results/channel/item/*[local-name()='forecast']/@high")[0]);
    if($scale == $data_scale) {
        return $value;
    }

    if($scale == 'C' && $data_scale == 'F') {
        return intval((intval($value) - 32) / 1.8);
    }

    if($scale == 'F' && $data_scale == 'C') {
        return intval(intval($value)*1.8 + 32);
    }

    return 0;
}

function weather_code_for_doc($doc) {
    $weather = (string)($doc->xpath("//query/results/channel/item/*[local-name()='forecast']/@code")[0]);

    # error_log("weather = $weather");

    # weather constants
    $SUNNY = 0;
    $CLOUDS = 1;
    $FOG = 2;
    $RAIN = 3;
    $SNOW = 4;
    $STORMS = 5;

    $codes = array(
        '0' => $STORMS, // tornado
        '1' => $STORMS, // tropical storm
        '2' => $STORMS, // hurricane
        '3' => $STORMS, // severe thunderstorms
        '4' => $STORMS, // thunderstorms
        '5' => $SNOW, // mixed rain and snow
        '6' => $SNOW, // mixed rain and sleet
        '7' => $SNOW, // mixed snow and sleet
        '8' => $RAIN, // freezing drizzle
        '9' => $RAIN, // drizzle
        '10' => $RAIN, // freezing rain
        '11' => $RAIN, // showers
        '12' => $RAIN, // showers
        '13' => $SNOW, // snow flurries
        '14' => $SNOW, // light snow showers
        '15' => $SNOW, // blowing snow
        '16' => $SNOW, // snow
        '17' => $RAIN, // hail
        '18' => $SNOW, // sleet
        '19' => $FOG, // dust
        '20' => $FOG, // foggy
        '21' => $FOG, // haze
        '22' => $FOG, // smoky
        '23' => $STORMS, // blustery
        '24' => $SUNNY, // windy
        '25' => $SUNNY, // cold
        '26' => $CLOUDS, // cloudy
        '27' => $CLOUDS, // mostly cloudy (night)
        '28' => $CLOUDS, // mostly cloudy (day)
        '29' => $CLOUDS, // partly cloudy (night)
        '30' => $CLOUDS, // partly cloudy (day)
        '31' => $SUNNY, // clear (night)
        '32' => $SUNNY, // sunny
        '33' => $SUNNY, // fair (night)
        '34' => $SUNNY, // fair (day)
        '35' => $RAIN, // mixed rain and hail
        '36' => $SUNNY, // hot
        '37' => $STORMS, // isolated thunderstorms
        '38' => $STORMS, // scattered thunderstorms
        '39' => $STORMS, // scattered thunderstorms
        '40' => $RAIN, // scattered showers
        '41' => $SNOW, // heavy snow
        '42' => $SNOW, // scattered snow showers
        '43' => $SNOW, // heavy snow
        '44' => $CLOUDS, // partly cloudy
        '45' => $STORMS, // thundershowers
        '46' => $SNOW, // snow showers
        '47' => $SNOW, //isolated thundershowers
        '3200' => $SUNNY // not available
    );

    if(array_key_exists($weather, $codes)) {
        $code = $codes[$weather];
    }  else {
        $code = $SUNNY;
    }

    return $code;
}

function weather_code_for_location($city) {
    $xml = weather_data_for_location($city);
    $doc = simplexml_load_string($xml);
    //    error_log($doc);
    return weather_code_for_doc($doc);
}

function weather_rabbit_app($db, $rabbit, $app_data) {
    global $ping_result_data;
    $code = weather_code_for_location($app_data['city']);

    $ambient = array();
    encode_set_ambient($ambient, 1, $code);

    array_push($ping_result_data, 4);
    encode_length($ping_result_data, count($ambient) + 4);
    array_push($ping_result_data, 0, 0, 0, 0);
    foreach($ambient as $e) { array_push($ping_result_data, $e); }

}

?>
