<?php 
/**
 * This is a zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 

if(!$CUse->IsAdmin())
{ 
	header("Location: login.php");
}

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($zeus['database']);
$content = new CUserAdmin($db);

// Get parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$delete = isset($_POST['delete'])  ? true : false;
$submit = null;


// Check if form was submitted
if($delete) {
	$content->removeUser($id);
}

// Select information on the movie 
$sql = 'SELECT * FROM user WHERE id = ?';
$params = array($id);
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);

if(isset($res[0])) {
  $user = $res[0];
}
else {
  die('Failed: There is no user with that id');
}


$breadcrumb = "<ul class='breadcrumb'><li><a href='admin.php'>Administration</a></li><li><a href='delete_user.php'>Ta bort användare</a></li></ul>";

// Do it and store it all in variables in the zeus container.
$zeus['title'] = "Radera användare";
$zeus['main'] = <<<EOD
{$breadcrumb}
<div class="row">
	<div class="col-md-12">
		<h1>Radera <small>{$user->acronym}</small></h1>
		<form class='form-horizontal' id='edit' role='form' method='post'>
			<input type='hidden' name='id' value={$user->id}>
			<input class='btn btn-default' type='submit' name='delete' value='Radera användare'/>
		</form>
	</div>
</div>
EOD;


// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);
