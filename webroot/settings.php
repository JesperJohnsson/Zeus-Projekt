<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($zeus['database']);
$content = new CUserAdmin($db);

$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$acronym2  = isset($_POST['acronym2']) ? strip_tags($_POST['acronym2']) : null;
$name  = isset($_POST['name']) ? strip_tags($_POST['name']) : null;
$password  = isset($_POST['password']) ? strip_tags($_POST['password']) : null;
$save   = isset($_POST['save'])  ? true : false;

$settings = $CUse->userSettings();

if($save)
{
	//$CUse->editMyself($acronym2, $name, $password, $id);
	$content->updateUser($acronym2, $name, $id);
	header("Location: settings.php");
}



// Prepare content and store it all in variables in the zeus container.
$zeus['title'] = "Bloggen";
$zeus['main'] = <<<EOD

{$settings}
EOD;


// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);