<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('convert');

$filePath = filter_input(INPUT_POST, 'filePath', FILTER_SANITIZE_STRING);
$outPath = filter_input(INPUT_POST, 'outPath', FILTER_SANITIZE_STRING);

$user = \OCP\User::getUser();

$outputMessage = '';

$fullInPath = \OC\Files\Filesystem::getLocalFile($filePath);

$progressFilePath = substr($fullInPath, 0, strpos($fullInPath, 'files')) . 'files/convertlog.txt';

$ffmpegCommand = 'ffmpeg -y -i ' .$fullInPath. ' -threads 1 -strict -2 /var/www/html/convert'.$outPath.' </dev/null 1> ' .$progressFilePath.'  2>&1 &';

$ffmpegOutput = shell_exec($ffmpegCommand);

echo($outPath);

?>
