<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Zeus container.
$zeus['title'] = "404";
$zeus['header'] = "";
$zeus['main'] = "This is a Zeus 404. Document is not here.";
$zeus['footer'] = "";
 
// Send the 404 header 
header("HTTP/1.0 404 Not Found");
 
 
// Finally, leave it all to the rendering phase of Zeus.
include(ZEUS_THEME_PATH);