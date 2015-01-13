<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($zeus['database']);

// Get parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
is_numeric($id) or die('Check: Id must be numeric.');

$content = new CMovie($db);
$html = $content->showMovie($id, $acronym);

// Prepare content and store it all in variables in the zeus container.
$zeus['title'] = "Filmer";
$zeus['main'] = <<<EOD
{$html}
EOD;


// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);