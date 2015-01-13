<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php');

//Connect to the Mysql database
$db = new CDatabase($zeus['database']);
$content = new CContent($db);

//Get the parameters of the form, only valid if the form has been submitted
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

$delete = isset($_POST['delete']);

// Check that incoming parameters are valid
isset($acronym) or die('Check: You must login to edit.');
is_numeric($id) or die('Check: Id must be numeric.');

// Get all content
$c = $content->showContent($id);
$title = htmlentities($c->title, null, 'UTF-8');


$output = null;
if($delete)
{
	$output = $content->restoreContent($id, $title);
	header("Location: admin.php");
}

$breadcrumb = "<ul class='breadcrumb'><li><a href='blog.php'>Nyheter</a></li><li><a href='?id={$id}'><span class='glyphicon glyphicon-remove-sign' aria-hidden='true'></span> {$title}</a></li></ul>";

// Prepare content and store it all in variables in the Zeus container.
$zeus['title'] = "Återställ innehåll";

$zeus['main'] = <<<EOD
{$breadcrumb}
<div class="row">
	<div class="col-md-12">
		<h1>Återställ <small>{$title}</small></h1>
		<form class="form-horizontal" id='edit' role="form" method="post">
			<input type="hidden" name="id" value="{$id}">
			<input class="btn btn-default" type='submit' name='delete' value='Återställ nyhet'/>
		</form>
	</div>
</div>
EOD;

// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);