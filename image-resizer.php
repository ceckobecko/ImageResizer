<?php

define('PHOTO_DIR' , '/home/tsvetan/Documents/Pic folders');
define('COPY_DIR', '');
$thumbnails_size = ['x' => 100, 'y' => 100];

function loadImage(){};
function resizeImage(){};
function saveImage(){};

function loadRecursively(){
    $jpgs = glob(PHOTO_DIR . '/' . '*.{jpg}');
    print json_encode($jpgs);
}

loadRecursively();