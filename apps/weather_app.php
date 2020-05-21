<?php

function weather_data_for_location($id, $key, $city) {
	$url = "http://api.weatherunlocked.com/api/forecast/".urlencode($city)."?app_id=".urlencode($id)."&app_key=".urlencode($key);

    $data = cache_get($url);
    if($data == null) {
        # error_log("Fetching weather data for: ".$city);
        # error_log($url);

        $session = curl_init($url);
        curl_setopt($session, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_HTTPHEADER, ['Accept: application/xml']);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($session);
        curl_close($session);

        cache_put($url, $data);
    } else {
        //    error_log("Using cached weather data for: ".$city);
    }

    return $data;
}

function weather_temp_for_doc($doc, $scale = 'c') {
    if(!$doc) {
        return 0;
    }

	if($scale == 'c') {
		$value = (string)($doc->xpath("//Forecast/Days/descendant::Day[1]/temp_max_c/text()")[0]);
	} else {
		$value = (string)($doc->xpath("//Forecast/Days/descendant::Day[1]/temp_max_f/text()")[0]);
	}

    return intval($value);
}

function weather_code_for_doc($doc) {
    if(!$doc) {
        return 0;
    }

	$weather = (string)($doc->xpath("//Forecast/Days/descendant::Day[1]/Timeframes/descendant::Timeframe[1]/wx_code/text()")[0]);

    # error_log("weather = $weather");

    # weather constants
    $SUNNY = 0;
    $CLOUDS = 1;
    $FOG = 2;
    $RAIN = 3;
    $SNOW = 4;
    $STORMS = 5;

	$codes = array(
		'0'  =>	$SUNNY,	// Clear
		'1'  =>	$CLOUDS,	// Partly cloudy
		'2'  =>	$CLOUDS,	// Cloudy
		'3'  =>	$CLOUDS,	// Overcast
		'10' =>	$FOG,	// Mist
		'21' =>	$RAIN,	// Patchy rain possible
		'22' =>	$SNOW,	// Patchy snow possible
		'23' =>	$SNOW,	// Patchy sleet possible
		'24' =>	$RAIN,	// Patchy freezing drizzle possible
		'29' =>	$STORMS,	// Thundery outbreaks possible
		'38' =>	$SNOW,	// Blowing snow
		'39' =>	$SNOW,	// Blizzard
		'45' =>	$FOG,	// Fog
		'49' =>	$FOG,	// Freezing fog
		'50' =>	$RAIN,	// Patchy light drizzle
		'51' =>	$RAIN,	// Light drizzle
		'56' =>	$RAIN,	// Freezing drizzle
		'57' =>	$RAIN,	// Heavy freezing drizzle
		'60' =>	$RAIN,	// Patchy light rain
		'61' =>	$RAIN,	// Light rain
		'62' =>	$RAIN,	// Moderate rain at times
		'63' =>	$RAIN,	// Moderate rain
		'64' =>	$RAIN,	// Heavy rain at times
		'65' =>	$RAIN,	// Heavy rain
		'66' =>	$RAIN,	// Light freezing rain
		'67' =>	$RAIN,	// Moderate or heavy freezing rain
		'68' =>	$SNOW,	// Light sleet
		'69' =>	$SNOW,	// Moderate or heavy sleet
		'70' =>	$SNOW,	// Patchy light snow
		'71' =>	$SNOW,	// Light snow
		'72' =>	$SNOW,	// Patchy moderate snow
		'73' =>	$SNOW,	// Moderate snow
		'74' =>	$SNOW,	// Patchy heavy snow
		'75' =>	$SNOW,	// Heavy snow
		'79' =>	$RAIN,	// Ice pellets
		'80' =>	$RAIN,	// Light rain shower
		'81' =>	$RAIN,	// Moderate or heavy rain shower
		'82' =>	$RAIN,	// Torrential rain shower
		'83' =>	$SNOW,	// Light sleet showers
		'84' =>	$SNOW,	// Moderate or heavy sleet showers
		'85' =>	$SNOW,	// Light snow showers
		'86' =>	$SNOW,	// Moderate or heavy snow showers
		'87' =>	$SNOW,	// Light showers of ice pellets
		'88' =>	$SNOW,	// Moderate or heavy showers of ice pellets
		'91' =>	$STORMS,	// Patchy light rain with thunder
		'92' =>	$STORMS,	// Moderate or heavy rain with thunder
		'93' =>	$STORMS,	// Patchy light snow with thunder
		'94' =>	$STORMS	// Moderate or heavy snow with thunder	
    );

    if(array_key_exists($weather, $codes)) {
        $code = $codes[$weather];
    }  else {
        $code = $SUNNY;
    }

    return $code;
}


function weather_code_for_location($id, $key, $city) {
    $xml = weather_data_for_location($id, $key, $city);
    $doc = simplexml_load_string($xml);
    //    error_log($doc);
    return weather_code_for_doc($doc);
}

function weather_rabbit_app($db, $rabbit, $app_data) {
    global $ping_result_data;
    $code = weather_code_for_location($app_data['id'], $app_data['key'], $app_data['city']);

    $ambient = array();
    encode_set_ambient($ambient, 1, $code);

    array_push($ping_result_data, 4);
    encode_length($ping_result_data, count($ambient) + 4);
    array_push($ping_result_data, 0, 0, 0, 0);
    foreach($ambient as $e) { array_push($ping_result_data, $e); }
}

?>
