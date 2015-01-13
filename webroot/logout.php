<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 

unset($_SESSION['user']);
header("Location: login.php");

// Finally, leave it all to the rendering phase of Zeus.
include(ZEUS_THEME_PATH);