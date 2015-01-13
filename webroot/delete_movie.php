<?php 
/**
 * This is a zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 

if(!$CUse->IsAuthenticated())
{ 
	header("Location: login.php");
}

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($zeus['database']);
$content = new CMovieAdmin($db);

// Get parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$delete = isset($_POST['delete'])  ? true : false;
$submit = null;


// Check if form was submitted
if($delete) {
	$content->removeMovie($id);
}

// Select information on the movie 
$sql = 'SELECT * FROM VMovie WHERE id = ?';
$params = array($id);
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);

if(isset($res[0])) {
  $movie = $res[0];
}
else {
  die('Failed: There is no movie with that id');
}

if($CUse->IsAdmin())
{
	$submit = "
	<form class='form-horizontal' id='edit' role='form' method='post'>
		<input type='hidden' name='id' value={$movie->id}>
		<input class='btn btn-default' type='submit' name='delete' value='Radera film'/>
	</form>
	";
}



// Do it and store it all in variables in the zeus container.
$zeus['title'] = "Radera film";

$breadcrumb = "<ul class='breadcrumb'>\n<li><a href='movies.php?'>Filmer</a></li><li><a href='?id={$movie->id}'><span class='glyphicon glyphicon-remove-sign' aria-hidden='true'></span> {$movie->title}</a></li></ul>";

$zeus['main'] = <<<EOD
{$breadcrumb}
<div class="row">
	<div class="col-md-12">
		<h1>Radera <small>{$movie->title}</small></h1>
		{$submit}
	</div>
</div>
EOD;


// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);
