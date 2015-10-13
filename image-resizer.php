<?php

define('THUMBNAILS_DIRECTORY', __DIR__ . '/thumbnails/');
define('THUMBNAIL_SIZE_X', 100);
define('THUMBNAIL_SIZE_Y', 100);

$options = getopt('s:d::');
$photoDir = null;
if (isset($options['s'])) {
    $photoDir = $options['s'];
} else {
    $photoDir = print "Error: Please provide at least -s attribute(The source of the images)\r\n";
    return 0;
}

function makeThumbnails()
{
    try {
        global $photoDir;
        $directoryIterator = new RecursiveDirectoryIterator($photoDir);
        $iterator = new RecursiveIteratorIterator($directoryIterator);
        $regexIterator = new RegexIterator($iterator, '/^.+\.jpg/i');
        $thumbnailsDirectory = getThumbnailsDirectory();
        /**
         * @var SplFileInfo $image
         */
        foreach ($regexIterator as $image) {
            try {
                print "Creating thumbnail for image: {$image->getPathname()}\r\n";
                $imageName = $image->getPathname();
                $imageHash = crc32($imageName);
                $imagick = new Imagick();
                $imagick->readImage($imageName);
                $imagick->thumbnailImage(THUMBNAIL_SIZE_X, THUMBNAIL_SIZE_Y);
                $imagick->setImageFormat('jpg');
                $imagick->writeImage($thumbnailsDirectory . "$imageHash.jpg");
            } catch (Exception $e) {
                print "Error: Not able to create thumbnail: {$e->getMessage()}\r\n";
            }
        }
    } catch (UnexpectedValueException $ex) {
        print "Error: cannot find directory: {$ex->getMessage()}\r\n";
    } catch (Exception $ex) {
        print "Error while trying to read file names:{$ex->getMessage()}\r\n";
    }

}

function getThumbnailsDirectory()
{
    global $options;
    $thumbnailsDirectory = THUMBNAILS_DIRECTORY;
    if (isset($options["d"])) {
        $thumbnailsDirectory = $options["d"];
    }
    makeDirectory($thumbnailsDirectory);
    return $thumbnailsDirectory;
}

function makeDirectory($directoryPath)
{
    if (!file_exists($directoryPath) && !mkdir($directoryPath)) {
        print "Error: Not able to create the directory\r\n";
    }
}

makeThumbnails();