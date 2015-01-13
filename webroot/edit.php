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
$save   = isset($_POST['save'])  ? true : false;
$submit = null;

// Check that incoming parameters are valid
is_numeric($id) or die('Check: Id must be numeric.');

if($save)
{
	$content->updateContent();
}

if($CUse->IsAdmin())
{
	$submit = '
	<div class="col-sm-offset-2 col-sm-8">
		<input type="submit" name="save" class="btn btn-default" value="Spara"><input type="reset" class="btn btn-default" value="Återställ">
	</div>
	';
}

// Get all content
$c = $content->showContent($id);

// Sanitize content before using it.
$title 		= htmlentities($c->title, null, 'UTF-8');
$slug   	= htmlentities($c->slug, null, 'UTF-8');
$url    	= htmlentities($c->url, null, 'UTF-8');
$data   	= htmlentities($c->data2, null, 'UTF-8');
$type   	= htmlentities($c->type2, null, 'UTF-8');
$filter 	= htmlentities($c->filter, null, 'UTF-8');
$published 	= htmlentities($c->published, null, 'UTF-8');


$breadcrumb = "<ul class='breadcrumb'><li><a href='blog.php'>Nyheter</a></li><li><a href='blog.php?slug={$slug}'>{$title}</a></li><li><a href='?id={$id}'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span> {$title}</a></li></ul>";
// Prepare content and store it all in variables in the Zeus container.
$zeus['title'] = "Uppdatera innehåll";

$zeus['main'] = <<<EOD
{$breadcrumb}
<div class="row">
	<div class="col-md-12 text-center">
		<h3>{$zeus['title']}</h3>
		<hr></hr>
	</div>
</div>

<div class="row">
	
	<form class="form-horizontal" id='edit' role="form" method="post">
		<input type="hidden" class="form-control" id="type2" name="type2" value='post'/>
		<input type='hidden' name='id' value='{$id}'/>
		<div class="form-group">
			<label for="title" class="col-sm-2 control-label">Titel</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="title" name="title" placeholder="Titel" value='{$title}'>
			</div>
		</div>
		
		<div class="form-group">
			<label for="slug" class="col-sm-2 control-label">Slug</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="slug" name="slug" placeholder="Slug" value='{$slug}'>
			</div>
		</div>

		<div class="form-group">
			<label for="url" class="col-sm-2 control-label">Url</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="url" name="url" placeholder="Url" value='{$url}'>
			</div>
		</div>
		
		<div class="form-group">
			<label for="data2" class="col-sm-2 control-label">Data</label>
			<div class="col-sm-8">
				<textarea class="form-control" rows="5" id="data2" name="data2" placeholder="Text">{$data}</textarea>
			</div>
		</div>

		<div class="form-group">
			<label for="filter" class="col-sm-2 control-label">Filter</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="filter" name="filter" placeholder="Filter" value='{$filter}'>
			</div>
		</div>
		
		<div class="form-group">
			<label for="published" class="col-sm-2 control-label">Pubdatum</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="published" name="published" placeholder="Publiseringsdatum" value='{$published}'>
			</div>
		</div>

		{$submit}
	</form>
</div>
EOD;

// Finally, leave it all to the rendering phase of zeus.
include(ZEUS_THEME_PATH);
