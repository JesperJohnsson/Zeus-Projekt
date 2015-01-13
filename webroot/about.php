<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 

// Do it and store it all in variables in the Zeus container.
$zeus['title'] = "Om mig";




$zeus['main'] = <<<EOD
<h1>Om företaget RM Rental Movies</h1>
<p>Vi skapades år 2014.</p>
EOD;


// Finally, leave it all to the rendering phase of Zeus.
include(ZEUS_THEME_PATH);
