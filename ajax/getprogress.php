<?php

$outPath = filter_input(INPUT_POST, 'outPath', FILTER_SANITIZE_STRING);
$fullOutPath = \OC\Files\Filesystem::getLocalFile($outPath);

$content = \OC\Files\Filesystem::file_get_contents('/convertlog.txt');

if($content){
    //get duration of source
    preg_match("/Duration: (.*?), start:/", $content, $matches);

    $rawDuration = $matches[1];

    //rawDuration is in 00:00:00.00 format. This converts it to seconds.
    $ar = array_reverse(explode(":", $rawDuration));
    $duration = floatval($ar[0]);
    if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
    if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;

    //get the time in the file that is already encoded
    preg_match_all("/time=(.*?) bitrate/", $content, $matches);

    $rawTime = array_pop($matches);

    //this is needed if there is more than one match
    if (is_array($rawTime)){$rawTime = array_pop($rawTime);}

    //rawTime is in 00:00:00.00 format. This converts it to seconds.
    $ar = array_reverse(explode(":", $rawTime));
    $time = floatval($ar[0]);
    if (!empty($ar[1])) $time += intval($ar[1]) * 60;
    if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;

    //calculate the progress
    $progress = floor(($time/$duration) * 100);
//    echo "Duration: " . $duration . "<br>";
//    echo "Current Time: " . $time . "<br>";
    
    
    if(preg_match("/muxing overhead/", $content, $matches)) {
        $progress = 101;
        $moveCommand = 'mv /var/www/html/convert'.$outPath.' '.$fullOutPath;
        $moveOutput = shell_exec($moveCommand);
    }
	
    echo $progress;

}
else {
echo -1;
}

?>

