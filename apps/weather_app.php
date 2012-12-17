<?php

function weather_data_for_location($city) {
  // weather condition codes - http://www.worldweatheronline.com/feed/wwoConditionCodes.xml
  // weather query - http://free.worldweatheronline.com/feed/weather.ashx?q=<city here>&format=xml&num_of_days=1&key=<api key here>

  $url = "http://free.worldweatheronline.com/feed/weather.ashx?q=".urlencode($city)."&format=xml&num_of_days=1&key=".config_value('app-weather-api-key');

  $xml = cache_get($url);
  if($xml == null) {
//    error_log("Fetching weather data for: ".$city);

    $xml = "";
    if ($fp = fopen($url, 'r')) {
      while ($line = fread($fp, 1024)) {
	$xml .= $line;
      }
      fclose($fp);
    }

    cache_put($url, $xml);
  } else {
//    error_log("Using cached weather data for: ".$city);
  }

  return $xml;
}

function weather_temp_for_doc($doc, $scale = 'C') {
  $weather = $doc->xpath("//weather/tempMax".$scale."/text()");
  return (string)$weather[0];
}

function weather_code_for_doc($doc) {
  $weather = $doc->xpath("//weather/weatherCode/text()");
  $weather = (string)$weather[0];

  # weather constants
  $SUNNY = 0;
  $CLOUDS = 1;
  $FOG = 2;
  $RAIN = 3;
  $SNOW = 4;
  $STORMS = 5;

  $codes = array(
		 '395' => $SNOW, // Moderate or heavy snow in area with thunder
		 '392' => $SNOW, // Patchy light snow in area with thunder
		 '389' => $RAIN, // Moderate or heavy rain in area with thunder
		 '386' => $RAIN, // Patchy light rain in area with thunder
		 '377' => $SNOW, // Moderate or heavy showers of ice pellets
		 '374' => $SNOW, // Light showers of ice pellets
		 '371' => $SNOW, // Moderate or heavy snow showers
		 '368' => $SNOW, // Light snow showers
		 '365' => $RAIN, // Moderate or heavy sleet showers
		 '362' => $RAIN, // Light sleet showers
		 '359' => $RAIN, // Torrential rain shower
		 '356' => $RAIN, // Moderate or heavy rain shower
		 '353' => $RAIN, // Light rain shower
		 '350' => $SNOW, // Ice pellets
		 '338' => $SNOW, // Heavy snow
		 '335' => $SNOW, // Patchy heavy snow
		 '332' => $SNOW, // Moderate snow
		 '329' => $SNOW, // Patchy moderate snow
		 '326' => $SNOW, // Light snow
		 '323' => $SNOW, // Patchy light snow
		 '320' => $RAIN, // Moderate or heavy sleet
		 '317' => $RAIN, // Light sleet
		 '314' => $RAIN, // Moderate or Heavy freezing rain
		 '311' => $RAIN, // Light freezing rain
		 '308' => $RAIN, // Heavy rain
		 '305' => $RAIN, // Heavy rain at times
		 '302' => $RAIN, // Moderate rain
		 '299' => $RAIN, // Moderate rain at times
		 '296' => $RAIN, // Light rain
		 '293' => $RAIN, // Patchy light rain
		 '284' => $RAIN, // Heavy freezing drizzle
		 '281' => $RAIN, // Freezing drizzle
		 '266' => $RAIN, // Light drizzle
		 '263' => $RAIN, // Patchy light drizzle
		 '260' => $FOG, // Freezing fog
		 '248' => $FOG, // Fog
		 '230' => $SNOW, // Blizzard
		 '227' => $SNOW, // Blowing snow
		 '200' => $STORMS, // Thundery outbreaks in nearby
		 '185' => $RAIN, // Patchy freezing drizzle nearby
		 '182' => $RAIN, // Patchy sleet nearby
		 '179' => $SNOW, // Patchy snow nearby
		 '176' => $RAIN, // Patchy rain nearby
		 '143' => $FOG, // Mist
		 '122' => $CLOUDS, // Overcast
		 '119' => $CLOUDS, // Cloudy
		 '116' => $CLOUDS, // Partly Cloudy
		 '113' => $SUNNY, // Clear/Sunny
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

  return weather_code_for_doc($doc);
}

function weather_rabbit_app($db, $rabbit, $app_data, &$data) {
  $code = weather_code_for_location($app_data['city']);

  $ambient = array();
  encode_set_ambient($ambient, 1, $code);

  array_push($data, 4);
  encode_length($data, count($ambient) + 4);
  array_push($data, 0, 0, 0, 0);
  foreach($ambient as $e) { array_push($data, $e); }

}

?>
