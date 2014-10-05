<?php
require('app.php');
require('lib/rabbit.php');

$SAMPLE_RATE = 44100;
$KEY = "AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBXqWBgw";

$temp_file = tempnam(sys_get_temp_dir(), 'nabaztag');
file_put_contents($temp_file.".wav", $HTTP_RAW_POST_DATA);

exec("ffmpeg -i ".$temp_file.".wav -ar ".$SAMPLE_RATE." -y ".$temp_file.".flac");
if(!file_exists($temp_file.".flac")) {
  # try avconv instead
  exec("avconv -i ".$temp_file.".wav -ar ".$SAMPLE_RATE." -y ".$temp_file.".flac");
}

if(isset($config)) {
  $rabbit = find_rabbit($db, $_REQUEST['sn']);

  $url = "http://www.google.com/speech-api/v2/recognize?output=json&lang=en_US&key=".$KEY;
  $flac_file = file_get_contents($temp_file.".flac");
  $flac_size = filesize($temp_file.".flac");

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER => array("Content-Type: audio/x-flac; rate=".$SAMPLE_RATE, "Content-Length: $flac_size", "Expect:"),
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $flac_file
  ));
  $response = curl_exec($curl);
  curl_close($curl);

  if (!$response) {
    $error = "Could not send data for recognition: ".$php_errormsg;
  } else {
#    error_log($response);

    if ($response === false) {
      $error = "Could not read response: ".$php_errormsg;
    } else {
      $error = null;

      $parts = split("\n", $response);

      $data = json_decode($parts[1]);
      $result = $data->{'result'};
      $alternatives = $result[0]->{'alternative'};
      $transcript = $alternatives[0]->{'transcript'};
      $words = explode(' ', $transcript);
      $found = false;
      foreach($words as $word) {
        try {
          $to_load = 'apps/'.$word.'_command.php';
          if(file_exists($APP_DIR.'/'.$to_load)) {
            $found = true;
            require_once($to_load);

            call_user_func($word."_command", $db, $rabbit);
          }
        } catch (Exception $e) {
          $error = "Error processing command: ".$e->getMessage();
        }
      }

      if(!$found) {
        $error = "I don't know how to: ".$transcript;
      }
    }
  }
}

if($error) {
  $app = array('application' => 'tts', 'data' => serialize(array('text' => $error)));
  save_rabbit_app($db, $rabbit, $app);
}

unlink($temp_file);
unlink($temp_file.".wav");
unlink($temp_file.".flac");

?>
