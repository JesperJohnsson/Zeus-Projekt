<?php 

/** 
 *    This is a Zeus page controller 
 * 
 */
 // Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 

$db = new CDatabase($zeus['database']);

// Get parameters from query string 
$title = isset($_GET['title']) ? $_GET['title'] : null;
if(!$title)
{
	$title = isset($_GET['title2']) ? $_GET['title2'] : null;
}
$genre = isset($_GET['genre']) ? $_GET['genre'] : null; 
$hits = isset($_GET['hits']) ? $_GET['hits'] : 8; 
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
$year1 = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null; 
$year2 = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null; 
$orderby = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id'; 
$order = isset($_GET['order']) ? strtolower($_GET['order']) : 'asc'; 

// Check if parameters are valid 
is_numeric($hits) or die('Check: Hits must be numeric.'); 
is_numeric($page) or die('Check: Page must be numeric.'); 
is_numeric($year1) || !isset($year1) or die('Check: Year must be numeric or not set.'); 
is_numeric($year2) || !isset($year2) or die('Check: Year must be numeric or not set.'); 

// Create a CMovieSearch object with the parameters from the query string 
$moviesearch = new CMovieSearch($db, $title, $genre, $year1, $year2, $hits, $page); 
// Render the search form and set it to a variable 
$form = $moviesearch->RenderSearchForm(); 
// Create the query and set it to the $query and $param variables 
$moviesearch->BuildQuery($orderby, $order); 
$query = $moviesearch->GetQuery(); 
$params = $moviesearch->GetParams(); 
// Execute the query 
$searchQuery = $db->ExecuteSelectQueryAndFetchAll($query, $params); 
// Render the table 
$HTMLTable = new CHTMLTable($hits, $page); 
$table = $HTMLTable->RenderTable($searchQuery, $moviesearch->GetMax(), $moviesearch->GetRows(), $acronym); 
$breadcrumb = "<ul class='breadcrumb'>\n<li><a href='movies.php?'>Filmer</a></li></ul>";


$zeus['title'] = "Komplett sökning"; 

$zeus['main'] =  <<< EOD
{$breadcrumb}
{$form}
{$table}
EOD;


// Finally, leave it all to the rendering phase of Zeus.
include(ZEUS_THEME_PATH);

?> 