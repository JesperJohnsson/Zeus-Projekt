<?php 
/**
 * This is a Zeus pagecontroller.
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

// Get parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$director  = isset($_POST['director']) ? strip_tags($_POST['director']) : null;
$length  = isset($_POST['length']) ? strip_tags($_POST['length']) : null;
$year2   = isset($_POST['year2'])  ? strip_tags($_POST['year2'])  : null;
$plot   = isset($_POST['plot'])  ? strip_tags($_POST['plot'])  : null;
$image  = isset($_POST['image']) ? strip_tags($_POST['image']) : null;
$subtext  = isset($_POST['subtext']) ? strip_tags($_POST['subtext']) : null;
$speech  = isset($_POST['speech']) ? strip_tags($_POST['speech']) : null;
$quality  = isset($_POST['quality']) ? strip_tags($_POST['quality']) : null;
$price  = isset($_POST['price']) ? strip_tags($_POST['price']) : null;
$imdb  = isset($_POST['imdb']) ? strip_tags($_POST['imdb']) : null;
$youtube  = isset($_POST['youtube']) ? strip_tags($_POST['youtube']) : null;
$genre  = isset($_POST['genre']) ? $_POST['genre'] : array();
$save   = isset($_POST['save'])  ? true : false;


is_numeric($id) or die('Check: Id must be numeric.');
$content = new CMovieAdmin($db, $title, $director, $length, $year2, $plot, $image, $subtext, $speech, $quality, $price, $imdb, $youtube, $id, $genre);
$submit = null;

// Check if form was submitted
if($save) {
	$content->editMovie();
}

if($CUse->IsAdmin())
{
	$submit = "
	<div class='form-group'>
		<div class='col-md-offset-2 col-sm-8'>
			<input class='btn btn-default' type='submit' name='save' value='Spara'/> 
			<input class='btn btn-default' type='reset' value='Återställ'/>
		</div>
	</div>
	";
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

$breadcrumb = "<ul class='breadcrumb'>\n<li><a href='movies.php?'>Filmer</a></li><li><a href='movie.php?id={$movie->id}'>{$movie->title}</a></li><li><a href='?id={$movie->id}'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span> {$movie->title}</a></li></ul>";

// Do it and store it all in variables in the zeus container.
$zeus['title'] = "Uppdatera info om film";

$zeus['main'] = <<<EOD
{$breadcrumb}
<h1>{$zeus['title']}</h1>
<hr></hr>
<div class="row">
	<form class="form-horizontal" id='edit' role="form" method=post>
		<input type='hidden' name='id' value='{$movie->id}'/>
		
		<div class="form-group">
			<label for="title" class="col-sm-2 control-label">Titel</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="title" name="title" placeholder="Titel på filmen" value='{$movie->title}'>
			</div>
			<label for="director" class="col-sm-2 control-label">Regissör</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="director" name="director" placeholder="Regissör" value='{$movie->director}'>
			</div>
		</div>
		
		<div class="form-group">
			<label for="length" class="col-sm-2 control-label">Längd</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="length" name="length" placeholder="Längd i minuter" value='{$movie->length}'>
			</div>
			<label for="year2" class="col-sm-2 control-label">År</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="year2" name="year2" placeholder="Årtal filmen kom ut" value='{$movie->year2}'>
			</div>
		</div>
		
		<div class="form-group">
			<label for="image" class="col-sm-2 control-label">Sökväg till bild</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="image" name="image" placeholder="Bild sökväg" value='{$movie->image}'>
			</div>
			<label for="subtext" class="col-sm-2 control-label">Text</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="subtext" name="subtext" placeholder="Vilket språk filmen är textad på" value='{$movie->subtext}'>
			</div>
		</div>
		
		<div class="form-group">
			<label for="speech" class="col-sm-2 control-label">Språk</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="speech" name="speech" placeholder="Vilket språk som talas i filmen" value='{$movie->speech}'>
			</div>
			<label for="quality" class="col-sm-2 control-label">Kvalitet</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="quality" name="quality" placeholder="Kvalitet" value='{$movie->quality}'>
			</div>
		</div>
		
		<div class="form-group">
			<label for="price" class="col-sm-2 control-label">Pris</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="price" name="price" placeholder="Pris i kr" value='{$movie->price}'>
			</div>
		</div>
		
		<div class="form-group">
			<label for="imdb" class="col-sm-2 control-label">Imdb länk</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="imdb" name="imdb" placeholder="Internet movie database http länk" value='{$movie->imdb}'>
			</div>
		</div>

		<div class="form-group">
			<label for="youtube" class="col-sm-2 control-label">Youtube länk</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="youtube" name="youtube" placeholder="Youtube http länk" value='{$movie->youtube}'>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-md-2 control-label">Tillgängliga genres</label>
			<div class="col-sm-8">
				<p class="form-control-static"><small>comedy, romance, college, crime, drama, thriller, science-fiction, animation, adventure, family, svenskt, action, horror, war</small></p>
			</div>
		</div>
		
		<div class="form-group">
			<label for="genre" class="col-sm-2 control-label">Genres</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="genre" name="genre" placeholder="Genres som filmen tillhör separeras med ett komma tecken ( , )" value='{$movie->genre}' required>
			</div>
		</div>

		<div class="form-group">
			<label for="plot" class="col-sm-2 control-label">Handling</label>
			<div class="col-sm-8">
				<textarea class="form-control" rows="10" id="plot" name="plot" placeholder="En kort beskrivning om vad filmen handlar om">{$movie->plot}</textarea>
			</div>
		</div>
		
		{$submit}
	</form>
	
	<hr></hr>
	<h1>Ladda upp bild till server</h1>
	<hr></hr>
	
	<form class="form-horizontal" id='edit' role="form" method=post action='upload.php' enctype="multipart/form-data">
		<input type='hidden' name='id' value='{$movie->id}'/>
		<div class="form-group">
			<label for="fileupload" class="col-sm-2 control-label">Välj bild</label>
			<div class="col-sm-8">
				<input type="file" class="form-control" id="fileupload" name="fileupload" placeholder="Genres som filmen tillhör separeras med ett komma tecken ( , )" required>
			</div>
		</div>
		
		{$submit}
	</form>
</div>
EOD;



// Finally, leave it all to the rendering phase of Anax.
include(ZEUS_THEME_PATH);