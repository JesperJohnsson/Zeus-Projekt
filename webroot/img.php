<?php 
/**
 * This is a PHP skript to process images using the class CImage
 *
 */

//
// Ensure error reporting is on
//
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly


include(__DIR__.'/config.php'); 

//
// Define some constant values, append slash
// Use DIRECTORY_SEPARATOR to make it work on both windows and unix.
//
define('IMG_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR);
define('CACHE_PATH', __DIR__ . '/cache/');

//
// Get the incoming arguments
//
$src        = isset($_GET['src'])     ? $_GET['src']      : null;
$verbose    = isset($_GET['verbose']) ? true              : null;
$saveAs     = isset($_GET['save-as']) ? $_GET['save-as']  : null;
$quality    = isset($_GET['quality']) ? $_GET['quality']  : 60;
$ignoreCache = isset($_GET['no-cache']) ? true           : null;
$newWidth   = isset($_GET['width'])   ? $_GET['width']    : null;
$newHeight  = isset($_GET['height'])  ? $_GET['height']   : null;
$cropToFit  = isset($_GET['crop-to-fit']) ? true : null;
$sharpen    = isset($_GET['sharpen']) ? true : null;
$pathToImage = realpath(IMG_PATH . $src);

$imageObj = new CImage($src, $verbose, $saveAs, $quality, $ignoreCache, $newWidth, $newHeight, $cropToFit, $sharpen, $pathToImage);

$imageObj->outputResult();