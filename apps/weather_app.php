<?php

function weather_data_for_location($key, $city) {
	$url = "https://api.apixu.com/v1/forecast.xml?key=".urlencode($key)."&q=".urlencode($city)."&days=1"; 
    $data = cache_get($url);
    if($data == null) {
        # error_log("Fetching weather data for: ".$city);
        # error_log($url);

        $session = curl_init($url);
        curl_setopt($session, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($session);
        curl_close($session);

        cache_put($url, $data);
    } else {
        //    error_log("Using cached weather data for: ".$city);
    }

    return $data;
}

function weather_temp_for_doc($doc, $scale = 'C') {
    if(!$doc) {
        return 0;
    }

	if($scale == 'C') {
		$value = (string)($doc->xpath("//root/forecast/forecastday/day/maxtemp_c/text()")[0]);
	} else {
		$value = (string)($doc->xpath("//root/forecast/forecastday/day/maxtemp_f/text()")[0]);
	}

    return intval($value);
}

function weather_code_for_doc($doc) {
    if(!$doc) {
        return 0;
    }

	$weather = (string)($doc->xpath("//root/forecast/forecastday/day/condition/code/text()")[0]);

    # error_log("weather = $weather");

    # weather constants
    $SUNNY = 0;
    $CLOUDS = 1;
    $FOG = 2;
    $RAIN = 3;
    $SNOW = 4;
    $STORMS = 5;

	$codes = array(
		'1000' =>	$SUNNY,	// Clear
		'1003' =>	$CLOUDS,	// Partly cloudy
		'1006' =>	$CLOUDS,	// Cloudy
		'1009' =>	$CLOUDS,	// Overcast
		'1030' =>	$FOG,	// Mist
		'1063' =>	$RAIN,	// Patchy rain possible
		'1066' =>	$SNOW,	// Patchy snow possible
		'1069' =>	$SNOW,	// Patchy sleet possible
		'1072' =>	$RAIN,	// Patchy freezing drizzle possible
		'1087' =>	$STORMS,	// Thundery outbreaks possible
		'1114' =>	$SNOW,	// Blowing snow
		'1117' =>	$SNOW,	// Blizzard
		'1135' =>	$FOG,	// Fog
		'1147' =>	$FOG,	// Freezing fog
		'1150' =>	$RAIN,	// Patchy light drizzle
		'1153' =>	$RAIN,	// Light drizzle
		'1168' =>	$RAIN,	// Freezing drizzle
		'1171' =>	$RAIN,	// Heavy freezing drizzle
		'1180' =>	$RAIN,	// Patchy light rain
		'1183' =>	$RAIN,	// Light rain
		'1186' =>	$RAIN,	// Moderate rain at times
		'1189' =>	$RAIN,	// Moderate rain
		'1192' =>	$RAIN,	// Heavy rain at times
		'1195' =>	$RAIN,	// Heavy rain
		'1198' =>	$RAIN,	// Light freezing rain
		'1201' =>	$RAIN,	// Moderate or heavy freezing rain
		'1204' =>	$SNOW,	// Light sleet
		'1207' =>	$SNOW,	// Moderate or heavy sleet
		'1210' =>	$SNOW,	// Patchy light snow
		'1213' =>	$SNOW,	// Light snow
		'1216' =>	$SNOW,	// Patchy moderate snow
		'1219' =>	$SNOW,	// Moderate snow
		'1222' =>	$SNOW,	// Patchy heavy snow
		'1225' =>	$SNOW,	// Heavy snow
		'1237' =>	$RAIN,	// Ice pellets
		'1240' =>	$RAIN,	// Light rain shower
		'1243' =>	$RAIN,	// Moderate or heavy rain shower
		'1246' =>	$RAIN,	// Torrential rain shower
		'1249' =>	$SNOW,	// Light sleet showers
		'1252' =>	$SNOW,	// Moderate or heavy sleet showers
		'1255' =>	$SNOW,	// Light snow showers
		'1258' =>	$SNOW,	// Moderate or heavy snow showers
		'1261' =>	$SNOW,	// Light showers of ice pellets
		'1264' =>	$SNOW,	// Moderate or heavy showers of ice pellets
		'1273' =>	$STORMS,	// Patchy light rain with thunder
		'1276' =>	$STORMS,	// Moderate or heavy rain with thunder
		'1279' =>	$STORMS,	// Patchy light snow with thunder
		'1282' =>	$STORMS	// Moderate or heavy snow with thunder	
    );

    if(array_key_exists($weather, $codes)) {
        $code = $codes[$weather];
    }  else {
        $code = $SUNNY;
    }

    return $code;
}


function weather_code_for_location($key, $city) {
    $xml = weather_data_for_location($key, $city);
    $doc = simplexml_load_string($xml);
    //    error_log($doc);
    return weather_code_for_doc($doc);
}

function weather_rabbit_app($db, $rabbit, $app_data) {
    global $ping_result_data;
    $code = weather_code_for_location($app_data['key'], $app_data['city']);

    $ambient = array();
    encode_set_ambient($ambient, 1, $code);

    array_push($ping_result_data, 4);
    encode_length($ping_result_data, count($ambient) + 4);
    array_push($ping_result_data, 0, 0, 0, 0);
    foreach($ambient as $e) { array_push($ping_result_data, $e); }
}

?>
