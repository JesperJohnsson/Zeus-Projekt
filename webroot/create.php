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
$submit = null;

$save   = isset($_POST['save'])  ? true : false;
$title = isset($_POST['title']) ? $_POST['title'] : null; 

// Check that incoming parameters are valid
if($save)
{
	$title = empty($title) ? null : $title;
    $content->create($title);
}

if($CUse->IsAdmin())
{
	$submit = '
	<div class="btn-group btn-group-justified" role="group" aria-label="...">
		<div class="btn-group" role="group">
			<input class="btn btn-default" type="submit" name="save" value="Spara"/>
		</div>
		<div class="btn-group" role="group">
			<input class="btn btn-default" type="reset" value="Återställ"/>
		</div>
	</div>
	';
}


// Prepare content and store it all in variables in the Zeus container.
$zeus['title'] = "Skapa nyhet";

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
				<input type="text" class="form-control" id="title" name="title" placeholder="Titel">
			</div>
		</div>
		{$submit}
	</form>
</div>
EOD;


// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);