<?php
/**
*This is a Zeus pagecontroller
*
*/
//include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php');

$start = null;
$p = null;
$html = null;
$html2 = null;

$dice = new CDice();

$zeus['title'] = "Tärningsspel";

if(isset($_SESSION['game']))
{
	$game = $_SESSION['game'];
}
else
{
	$game = new CDiceGame();
	$_SESSION['game'] = $game;
}

if(isset($_GET['p']))
{
	$p = $_GET['p'];
	
	switch($p)
	{
	case "throw":
		$html2 = $game->setPoints($dice->Roll());
	break;	
	case "save":
		$game->savePoints();
		$html2 = '<div style="margin-top:10px;" class="alert alert-success text-center" role="alert"><p>Dina poäng har sparats!</p></div>';
	break;	
	case "reset":
		$game->resetPoints();
	break;
	
	}
}

$html = $dice->RenderDice();
$html .= "<p>Runda: ". $game->getIndex() ."</p>";
$html .= "<p>Poäng denna runda: " . $game->getPoints() . "</p>";
$html .= "<p>Poäng totalt: " . $game->getTotalPoints() . "</p>";

if($game->getTotalPoints()>=100)
{
	$html2 = '<div style="margin-top:10px;" class="alert alert-success text-center" role="alert"><p>Du har vunnit! Det tog '. $game->getIndex() .' rundor! Tryck på börja om för att spela igen och se ifall du kan slå ditt resultat. </p></div>';
	$game->resetPoints();
}

$zeus['main'] = <<<EOD
<div class="row">
	<div class="col-md-12 text-center">
		<h3>Tärningsspelet 100</h3>
		<hr></hr>
	</div>
</div>
<div class="row">
	<div class="col-md-9 text-center">
		{$html}
		<a class="btn btn-primary" href="?p=throw">Kasta tärning</a>
		<a class="btn btn-primary" href="?p=save">Spara poäng</a>
		<a class="btn btn-primary" href="?p=reset">Börja om</a>
		{$html2}
	</div>
	<div style="margin-top:21px;" class="col-md-3">
		<div class="panel panel-danger">
			<div class="panel-heading">Instruktioner</div>
			<div class="panel-body">
				<p>Tärningsspelet 100 går ut på att samla ihop poäng genom att rulla en tärning. För varje kast får spelaren så många poäng som tärningen anger, förutom om man rullar en etta - då förlorar man alla poäng som man inte har sparat. Får man 100 poäng har man vunnit.</p>
			</div>
		</div>
	</div>
</div>
EOD;
include(ZEUS_THEME_PATH);
?>