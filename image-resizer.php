<?php
//require_once(Imagick::class);
define('PHOTO_DIR', '/home/tsvetan/Documents/pic_folders/');
define('THUMBNAILS_DIRECTORY', __DIR__ . '/copy_dir/');
define('THUMBNAIL_SIZE_X', 100);
define('THUMBNAIL_SIZE_Y', 100);


function makeThumbnails()
{
    $directoryIterator = new RecursiveDirectoryIterator(PHOTO_DIR);
    $iterator = new RecursiveIteratorIterator($directoryIterator);
    $regexIterator = new RegexIterator($iterator, '/^.+\.jpg/i');
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
            $imagick->writeImage(getThumbnailsDirectory() . "$imageHash.jpg");
        } catch (Exception $e) {
            print "Error: Not able to create thumbnail: {$e->getMessage()}\r\n";
        }
    }
}

function getThumbnailsDirectory()
{
    $thumbnailsDirectory = THUMBNAILS_DIRECTORY;
    if (isset($argv[1])) {
        $thumbnailsDirectory = $argv[1];
    }
    return $thumbnailsDirectory;
}

makeThumbnails();