<?php
$pathToFile = "vendor/backup-manager/backup-manager/src/Filesystems/LocalFilesystem.php";
$originalText = "use League\Flysystem\Adapter\Local;";
$textReplace = "use League\Flysystem\Local\LocalFilesystemAdapter as Local;";

$fileChange = file_get_contents(__DIR__.'/../'.$pathToFile);
$textContent = str_replace($originalText, $textReplace, $fileChange);
file_put_contents($pathToFile, $textContent);