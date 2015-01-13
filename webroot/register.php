<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php');


//Create the connection to the database
$db = new CDatabase($zeus['database']);
$content = new CUserAdmin($db);

$name  = isset($_POST['name']) ? strip_tags($_POST['name']) : null;
$acronym  = isset($_POST['acronym']) ? strip_tags($_POST['acronym']) : null;
$pwd  = isset($_POST['pwd']) ? strip_tags($_POST['pwd']) : null;
$create = isset($_POST['create'])  ? true : false;
$output = null;

if($create)
{
	$output = $content->createUser($acronym, $name, $pwd);
}

if($CUse->IsAuthenticated())
{
	header("Location: admin.php");
}


// Do it and store it all in variables in the zeus container.
$zeus['title'] = "Registrera konto";

$zeus['main'] = <<<EOD
<h1>{$zeus['title']}</h1>
<hr></hr>

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
			<input type="text" class="form-control" id="acronym" name="acronym" placeholder="Ditt användarnamn | akronym" required/>
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


EOD;

// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);


