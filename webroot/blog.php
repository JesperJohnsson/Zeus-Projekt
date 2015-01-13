<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 
//include(__DIR__.'/filter.php'); 


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($zeus['database']);


// Get parameters 
$slug    = isset($_GET['slug']) ? $_GET['slug'] : null;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

$filter = new CTextFilter(); 
$content = new CBlog($db, $filter);
$html = $content->showPost($slug, $acronym);
$breadcrumb = $content->createBreadcrumb($slug);


// Prepare content and store it all in variables in the zeus container.
$zeus['title'] = "Bloggen";
$zeus['main'] = <<<EOD
{$breadcrumb}
{$html}
EOD;


// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);