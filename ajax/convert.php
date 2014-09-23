<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('convert');

$filePath = filter_input(INPUT_POST, 'filePath', FILTER_SANITIZE_STRING);
$outPath = filter_input(INPUT_POST, 'outPath', FILTER_SANITIZE_STRING);

$user = \OCP\User::getUser();

$outputMessage = '';

$fullInPath = \OC\Files\Filesystem::getLocalFile($filePath);
$fullOutPath = \OC\Files\Filesystem::getLocalFile($outPath);

$shortPath = substr($fullInPath, strpos($fullInPath, 'data'));

$progressFilePath = substr($shortPath, 0, strpos($shortPath, 'files')) . 'files/convertlog.txt';

//$progressFilePath = '/var/www/html/convert/progress.txt';

unlink($progressFilePath);

$ffmpegCommand = 'ffmpeg -y -i ' .$fullInPath. ' -strict -2 '.$fullOutPath.' </dev/null 1> ' .$progressFilePath.'  2>&1 &';

$ffmpegOutput = shell_exec($ffmpegCommand);

/*
$outputMessage = $outputMessage . shell_exec('[ -f ' .$fullOutPath. ' ] && echo "All done!" || echo "Conversion failed."');
*/

echo $progressFilePath;


?>
