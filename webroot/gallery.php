<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 


// Define the basedir for the gallery
define('GALLERY_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'img');
define('GALLERY_BASEURL', '');


// Get incoming parameters
$path = isset($_GET['path']) ? $_GET['path'] : null;
$pathToGallery = realpath(GALLERY_PATH . DIRECTORY_SEPARATOR . $path);

$galleryObj = new CGallery();

$galleryObj->validateIncArg($pathToGallery);

// Read and present images in the current directory
if(is_dir($pathToGallery)) {
  $gallery = $galleryObj->readAllItemsInDir($pathToGallery);
}
else if(is_file($pathToGallery)) {
  $gallery = $galleryObj->readItem($pathToGallery);
}

// Prepare content and store it all in variables in the Zeus container.
$breadcrumb = $galleryObj->createBreadcrumb($pathToGallery);
 
$zeus['title'] = "Ett galleri";
$zeus['main'] = <<<EOD
<h1>{$zeus['title']}</h1>
 
$breadcrumb
 
$gallery
 
EOD;




// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);