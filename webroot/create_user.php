<?php 
/**
 * This is a zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 

if(!$CUse->IsAdmin())
{
	header("Location:admin.php");
}
// Connect to a MySQL database using PHP PDO
$db = new CDatabase($zeus['database']);
$content = new CUserAdmin($db);

// Get parameters 
$name  = isset($_POST['name']) ? strip_tags($_POST['name']) : null;
$acronym2  = isset($_POST['acronym2']) ? strip_tags($_POST['acronym2']) : null;
$pwd  = isset($_POST['pwd']) ? strip_tags($_POST['pwd']) : null;
$create = isset($_POST['create'])  ? true : false;
$output = null;

// Check if form was submitted
if($create) {
	$content->createUser($acronym2, $name, $pwd);
	$output = "<div class='alert alert-success' role='alert'>Konto med acronym: {$acronym2} skapades.</div>";
}


$breadcrumb = "<ul class='breadcrumb'><li><a href='admin.php'>Administration</a></li><li><a href='create_user.php'>Skapa användare</a></li></ul>";
// Do it and store it all in variables in the zeus container.
$zeus['title'] = "Skapa ny användare";

$zeus['main'] = <<<EOD
{$breadcrumb}
<div class="row">
	<div class="col-md-12">
		<h1>{$zeus['title']}</h1>
		<hr></hr>
	</div>
</div>

<div class="row">
	<form class="form-horizontal" method=post>
		<div class="form-group">
			<label class="col-md-2 control-label">Namn</label>
			<div class="col-sm-4">
				<input type="text" name="name" class="form-control" placeholder="Ditt namn" required/>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-md-2 control-label">Användarnamn</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="acronym2" name="acronym2" placeholder="Ditt användarnamn | akronym" required/>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-md-2 control-label">Lösenord</label>
			<div class="col-sm-4">
				<input type="password" class="form-control" id="pwd" name="pwd" required/>
			</div>
		</div>
		
		<div class='form-group'>
			<div class='col-md-offset-2 col-sm-8'>
				<input class='btn btn-default' type='submit' name='create' value='Skapa konto'/> 
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-12">
				<output class='text-center'><b>{$output}</b></output>
			</div>
		</div>
		
	</form>
</div>
EOD;


// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);