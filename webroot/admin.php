<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 

$db = new CDatabase($zeus['database']);
$content = new CMovieAdmin($db);
$list = $content->showAllContent();
$content2 = new CContent($db);
$list2 = $content2->showAllContent();
$content3 = new CUserAdmin($db);
$list3 = $content3->showAllContent();

$form1 = null;
$form2 = null;
$user = null;


if(!$CUse->IsAuthenticated())
{
	header("Location: login.php");
}

if(isset($_POST['restore_movie']) || isset($_GET['restore_movie'])) {
	$content->resetDatabase();
}

if(isset($_POST['restore_news']) || isset($_GET['restore_news'])) {
	$content2->resetDatabase();
}

if($CUse->IsAdmin())
{
	$form1 = '
	<hr></hr>
	<form method="post">
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<input class="btn btn-danger" type="submit" name="restore_movie" value="Återställ filmdatabas"/>
			</div>
		</div>
	</form>
	';
	$form2 = '
	<hr></hr>
	<form method="post">
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<input class="btn btn-danger" type="submit" name="restore_news" value="Återställ nyhetsdatabas"/>
			</div>
		</div>
	</form>
	';
	
	$user = "
	<div class='row'>
		<div class='col-md-12'>
			<h1>Användare<small> <a href='user.php'>Detaljerad Användarlista</a></small></h1>
		</div>
	</div>
	<div class='row' style='border:1px solid #EEEEEE; padding:20px;'>
			
		<p>Här är en lista på allt innehåll i användardatabasen.</p>
		<hr></hr>

		<ul>
		{$list3}
		</ul>
		<hr></hr>
		<p><a href='user.php'>Visa alla användare.</a></p>
		<hr></hr>
		<p><a href='create_user.php'>Skapa en ny användare</a></p>
	</div>
	";
}


// Do it and store it all in variables in the Zeus container.
$zeus['title'] = "Om mig";
$zeus['main'] = <<<EOD
<div class="row">
	<div class="col-md-12">
		<h1>Filmer</h1>
	</div>
</div>
<div class="row" style="border:1px solid #EEEEEE; padding:20px;">

	<p>Här är en lista på allt innehåll i filmdatabasen.</p>
	<hr></hr>
	
	<ul>
	{$list}
	</ul>
	<hr></hr>
	<p><a href='movies.php?'>Visa alla filmer.</a></p>
	<hr></hr>
	<p><a href="movie_create.php">Skapa en ny film</a></p>
	
	{$form1}
	
</div>
<div class="row">
	<div class="col-md-12">
		<h1>Nyheter</h1>
	</div>
</div>
<div class="row" style="border:1px solid #EEEEEE; padding:20px;">
		
	<p>Här är en lista på allt innehåll i nyhetsdatabasen.</p>
	<hr></hr>

	<ul>
	{$list2}
	</ul>
	<hr></hr>
	<p><a href='blog.php'>Visa alla nyheter.</a></p>
	<hr></hr>
	<p><a href="create.php">Skapa en ny nyhet</a></p>
	
	{$form2}
</div>
{$user}
EOD;


// Finally, leave it all to the rendering phase of Zeus.
include(ZEUS_THEME_PATH);
