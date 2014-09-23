<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('convert');

$filePath = filter_input(INPUT_POST, 'filePath', FILTER_SANITIZE_STRING);
$outPath = filter_input(INPUT_POST, 'outPath', FILTER_SANITIZE_STRING);

$user = \OCP\User::getUser();


if (OCP\App::isEnabled('files_encryption')) {
	
	$inputPath = '/var/www/html/convert/' .$filePath;
	$outputPath = '/var/www/html/convert/' .$outPath;
	
	$contents = \OC\Files\Filesystem::file_get_contents($filePath);

	$output = file_put_contents($inputPath, $contents);
	
	$ffmpegCommand = 'ffmpeg -y -i ' .$inputPath. ' -strict -2 '.$outputPath.' 
 </dev/null >/dev/null 2>/var/log/ffmpeg.log &';
	
	$ffmpegOutput = shell_exec($ffmpegCommand);
	
	$convertedFile = file_get_contents($outputPath);
	
	$outContents = \OC\Files\Filesystem::file_put_contents($outPath, $convertedFile);
	
	$fileExists = shell_exec('[ -f ' .$outputPath. ' ] && echo "All done!" || echo "Conversion failed. Check file extension input."');
	
/*	$deletedOutput = unlink($outputPath);
	$deletedInput = unlink($inputPath); */
	
	echo $fileExists;
	
}
else {

	echo "Encryption off";
	/*
$inputPath = '/var/www/html/owncloud/data/' .$user. '/files' .$filePath;
$outputPath = '/var/www/html/owncloud/data/' . $user . "/files" . $outPath;

$ffmpegCommand = 'ffmpeg -y -i ' .$inputPath. ' -strict -2 '.$outputPath.' 
 </dev/null >/dev/null 2>/var/log/ffmpeg.log &';

$ffmpegOutput = shell_exec($ffmpegCommand);

$fileExists = shell_exec('[ -f ' .$outputPath. ' ] && echo "All done!" || echo "Conversion failed. Check file extension input."');

echo $fileExists;


//COMPRESSIONS
$ffmpegCommand = 'ffmpeg -y -i ' .$fullInPath. ' -c:v libx264 -preset slow -crf 22 -c:a copy  -strict -2 '.$fullOutPath.'
</dev/null >/dev/null 2>/var/log/ffmpeg.log &';



*/
	
}

?>
