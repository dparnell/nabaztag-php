<?php
require('app.php');
require('lib/rabbit.php');

$temp_file = tempnam(sys_get_temp_dir(), 'nabaztag');
file_put_contents($temp_file.".wav", $HTTP_RAW_POST_DATA);
exec("ffmpeg -i ".$temp_file.".wav -ar 16000 -y ".$temp_file.".flac");
try {
  if(isset($config)) {
    $rabbit = find_rabbit($db, $_REQUEST['sn']);
    
    $url = "http://www.google.com/speech-api/v1/recognize?xjerr=1&client=nabaztag-php&lang=en-US&maxresults=10";
    $params = array('http' => array(
				    'method' => 'POST',
				    'header' => "Content-Type: audio/x-flac; rate=16000\r\n",
				    'content' => file_get_contents($temp_file.".flac")
				    ));
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp) {
      $error = "Could not send data for recognition: ".$php_errormsg;
    } else {
      $response = @stream_get_contents($fp);

      if ($response === false) {
	$error = "Could not read response: ".$php_errormsg;
      } else {
	$error = null;

	$data = json_decode($response);
	$hypotheses = $data->{'hypotheses'};
	$utterance = $hypotheses[0]->{'utterance'};
	
	try {
	  include('apps/'.$name.'_command.php');
	  
	  call_user_func($utterance."_command", $db, $rabbit);
	} catch (Exception $e) {
	  $error = "Error processing command: ".$e->getMessage();
	}
	
      }
    }
  } 

  if($error) {
    $app = array('application' => 'tts', 'data' => serialize(array('text' => $error)));
    save_rabbit_app($db, $rabbit, $app);
  }
} finally {
  unlink($temp_file.".wav");
  unlink($temp_file.".flac");
}

?>