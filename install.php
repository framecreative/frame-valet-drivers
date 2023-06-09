#!/usr/local/bin/php
<?php

$home = getenv('HOME');
$path = dirname(__FILE__);
$driverFile = 'FrameWordPressValetDriver.php';
$valetDriverPath = $home . DIRECTORY_SEPARATOR . '.config' . DIRECTORY_SEPARATOR . 'valet' .DIRECTORY_SEPARATOR . 'Drivers';
$targetDriverFullPath = $path . DIRECTORY_SEPARATOR . $driverFile;
$valetDriverFullPath = $valetDriverPath . DIRECTORY_SEPARATOR . $driverFile;

if ( file_exists( $valetDriverFullPath ) or is_link( $valetDriverFullPath ) ){
    echo 'Driver already exists, removing' . PHP_EOL;
    unlink( $valetDriverFullPath );
}

echo 'Creating symlink to driver...' . PHP_EOL;
symlink( $targetDriverFullPath, $valetDriverFullPath );
$link = readlink( $valetDriverFullPath ) . PHP_EOL;
if ( !$link ){
    echo "Error creating link: readlink() returned " . $link . PHP_EOL;
    return 1;
}

echo "Success! Symbolic link created" . PHP_EOL;
echo $targetDriverFullPath . ' -> ' . $valetDriverFullPath . PHP_EOL;

echo PHP_EOL . 'Install completed. To update the driver just run "git pull origin master" in this repo';
