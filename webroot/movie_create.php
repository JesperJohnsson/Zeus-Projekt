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
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$create = isset($_POST['create'])  ? true : false;
$submit = null;

// Check if form was submitted
if($create) {
	$content->createMovie($title);
}

if($CUse->IsAdmin())
{
	$submit = "
	<div class='btn-group btn-group-justified' role='group' aria-label='...'>
		<div class='btn-group' role='group'>
			<input class='btn btn-default' type='submit' name='create' value='Spara'/>
		</div>
		<div class='btn-group' role='group'>
			<input class='btn btn-default' type='reset' value='Återställ'/>
		</div>
	</div>
	";
}


// Do it and store it all in variables in the zeus container.
$zeus['title'] = "Skapa ny film";

$zeus['main'] = <<<EOD
<div class="row">
	<div class="col-md-12">
		<h1>{$zeus['title']}</h1>
		<hr></hr>
	</div>
</div>

<div class="row">
	<form class="form-horizontal" id='edit' role="form" method="post">
		<div class="form-group">
			<label for="title" class="col-sm-2 control-label">Titel</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="title" name="title" placeholder="Titel" required>
			</div>
		</div>
		{$submit}
	</form>
</div>
EOD;


// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);