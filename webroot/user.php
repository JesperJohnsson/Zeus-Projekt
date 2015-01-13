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


$content = new CUserAdmin($db);
$zeus['title'] = "Filmer";


if($id)
{
	is_numeric($id) or die('Check: Id must be numeric.');
	$c = $content->showContent($id);
	$breadcrumb = "<ul class='breadcrumb'><li><a href='user.php'>Användare</a></li><li><a href='user.php?id={$c->id}'>{$c->name}</a></li></ul>";
	
	$zeus['main'] = <<<EOD
	{$breadcrumb}
	<div class="row">
		<div class="col-md-12">
			<h1 style='text-transform:capitalize;'>{$c->acronym}</h1>
		</div>
	</div>
	<p>Användarnamn: {$c->acronym}</p>
	<p>Namn: {$c->name}</p>
	<p>Användartyp: {$c->usertype}</p>
EOD;
	}
else
{
	
	$c = $content->showAllContentInTable();
	$breadcrumb = "<ul class='breadcrumb'><li><a href='user.php'>Användare</a></li></ul>";
	$zeus['main'] = <<<EOD
	{$breadcrumb}
	<table class="table table-bordered table-hover">
	{$c}
	</table>
EOD;
}
// Prepare content and store it all in variables in the zeus container.


// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);