<?php
$SAMPLE_RATE = 44100;

$id = time()."-".rand(1,100000000);

$wav_file = "/tmp/".$id.".wav";
$mp3_file = "/tmp/".$id.".mp3";

$text = escapeshellarg($_REQUEST['txt']);

exec("pico2wave -w ".$wav_file." ".$text);
if(file_exists($wav_file)) {
    exec("ffmpeg -i ".$wav_file." -ar ".$SAMPLE_RATE." -y ".$mp3_file);
    if(!file_exists($mp3_file)) {
        # try avconv instead
        exec("avconv -i ".$wav_file." -ar ".$SAMPLE_RATE." -y ".$mp3_file);
    }

    if(file_exists($mp3_file)) {
        $mp3_data = file_get_contents($mp3_file);

        unlink($mp3_file);

        header("Content-Type: audio/mpeg");
        echo $mp3_data;
    }
    unlink($wav_file);
}

?>