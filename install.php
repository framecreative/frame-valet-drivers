#!/usr/local/bin/php
<?php

$home = getenv('HOME');
$path = dirname(__FILE__);
$driverFile = 'FrameWordPressValetDriver.php';
$herdDriverPath = $home . DIRECTORY_SEPARATOR . 'Library' . DIRECTORY_SEPARATOR . 'Application Support' .DIRECTORY_SEPARATOR . 'Herd' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'valet' . DIRECTORY_SEPARATOR . 'Drivers' ;
$targetDriverFullPath = $path . DIRECTORY_SEPARATOR . $driverFile;
$herdDriverFullPath = $herdDriverPath . DIRECTORY_SEPARATOR . $driverFile;

if ( ! is_dir( $herdDriverPath ) ){
    echo "Creating directory at " . $herdDriverPath . PHP_EOL;
    mkdir( $herdDriverPath, 0755, true );
} else {
    echo "Correct folder exists at " . $herdDriverPath . PHP_EOL;
}

if ( file_exists( $herdDriverFullPath ) or is_link( $herdDriverFullPath ) ){
    echo 'Driver already exists, removing' . PHP_EOL;
    unlink( $herdDriverFullPath );
}

echo 'Creating symlink to driver...' . PHP_EOL;
symlink( $targetDriverFullPath, $herdDriverFullPath );
$link = readlink( $herdDriverFullPath ) . PHP_EOL;
if ( !$link ){
    echo "Error creating link: readlink() returned " . $link . PHP_EOL;
    return 1;
}

echo "Success! Symbolic link created" . PHP_EOL;
echo $targetDriverFullPath . ' -> ' . $herdDriverFullPath . PHP_EOL;

echo PHP_EOL . 'Install completed. To update the driver just run "git pull origin master" in this repo';
