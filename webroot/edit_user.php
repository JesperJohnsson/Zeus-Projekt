<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php');

if(!$CUse->IsAdmin())
{
	header("Location: admin.php");
}

//Connect to the Mysql database
$db = new CDatabase($zeus['database']);
$content = new CUserAdmin($db);

//Get the parameters of the form, only valid if the form has been submitted
$id     = isset($_GET['id'])    ? strip_tags($_GET['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$acronym2  = isset($_POST['acronym2']) ? strip_tags($_POST['acronym2']) : null;
$name  = isset($_POST['name']) ? strip_tags($_POST['name']) : null;
$save   = isset($_POST['save'])  ? true : false;

// Check that incoming parameters are valid
is_numeric($id) or die('Check: Id must be numeric.');


if($save)
{
	$content->updateUser($acronym2, $name, $id);
}

// Get all content
$c = $content->showContent($id);


//$breadcrumb = "<ul class='breadcrumb'><li><a href='blog.php'>Nyheter</a></li><li><a href='blog.php?slug={$slug}'>{$title}</a></li><li><a href='?id={$id}'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span> {$title}</a></li></ul>";
$breadcrumb = "<ul class='breadcrumb'><li><a href='admin.php'>Administration</a></li><li><a href='edit_user.php'>Uppdatera användare</a></li></ul>";
// Prepare content and store it all in variables in the Zeus container.
$zeus['title'] = "Uppdatera konto";

$zeus['main'] = <<<EOD
{$breadcrumb}
<div class="row">
	<div class="col-md-12">
		<h1>{$zeus['title']}</h1> 
		<hr></hr>
	</div>
</div>

<div class="row">
	
	<form class="form-horizontal" id='edit' role="form" method="post">
		<input type='hidden' name='id' value='{$c->id}'/>
		<div class="form-group">
			<label class="col-md-2 control-label">Namn</label>
			<div class="col-sm-4">
				<input type="text" id="name" name="name"class="form-control" placeholder="Ditt namn" value='{$c->name}' required/>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-md-2 control-label">Användarnamn</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="acronym2" name="acronym2" placeholder="Ditt användarnamn | akronym" value='{$c->acronym}' required/>
			</div>
		</div>
		
		<div class='form-group'>
			<div class='col-md-offset-2 col-sm-8'>
				<input class='btn btn-default' type='submit' name='save' value='Ändra konto'/> 
			</div>
		</div>
	</form>
</div>
EOD;

// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);
