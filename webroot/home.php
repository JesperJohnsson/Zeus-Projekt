<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php');

$db = new CDatabase($zeus['database']);
$content = new CMovie($db);
$content2 = new CBlog($db);
$content3 = new CMovieSearch($db);

// Do it and store it all in variables in the Zeus container.
$zeus['title'] = "Om mig";

$zeus['main'] = <<<EOD

{$content->ShowHomeMovies()}

<div class='col-md-12 text-center frontpageheader'>
	<h1>Genres</h1>
</div>
<div class='col-md-12' style='margin-bottom:100px;'>
	<h3>{$content3->getActiveGenres()}</h3>
</div>

<div class='row text-center' >
	<div class='col-md-offset-1 col-md-5'>
	{$content->ShowMostPopularMovie()}
	</div>
	<div class='col-md-5'>
	{$content->LastRentedMovie()}
	</div>
</div>

{$content2->showHomePost()}

EOD;


// Finally, leave it all to the rendering phase of Zeus.
include(ZEUS_THEME_PATH);
