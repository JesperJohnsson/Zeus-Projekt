<?php 
/**
 * This is a Zeus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $zeus variable with its defaults.
include(__DIR__.'/config.php'); 

// Check if user is authenticated.
//$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
 
if($acronym) {
  $output = "Du är inloggad som: $acronym ({$CUse->GetName()})";
  $output = "<div class='alert alert-success' role='alert'>{$output}</div>";
}
else {
  $output = "Du är INTE inloggad.";
  $output = "<div class='alert alert-danger' role='alert'>{$output}</div>";
}

// Check if user and password is okey
if(isset($_POST['login'])) {
	$CUse->Login($_POST['acronym'], $_POST['password']);
	header('Location: admin.php');
}

// Do it and store it all in variables in the Zeus container.
$zeus['title'] = "Logga in";

$zeus['main'] = <<<EOD
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>{$zeus['title']}</h1>
			<hr></hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form class="form-horizontal" method=post>
				<div class="form-group">
					<label class="col-md-2 control-label">Användarnamn</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="acronym" name="acronym"/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-2 control-label">Lösenord</label>
					<div class="col-sm-4">
						<input type="password" class="form-control" id="password" name="password"/>
					</div>
				</div>
				
				<div class='form-group'>
					<div class='col-md-offset-2 col-sm-8'>
						<input class='btn btn-default' type='submit' name='login' value='Logga in'/> 
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-12">
						<output class='text-center'><b>{$output}</b></output>
					</div>
				</div>
				
				
			</form>
		</div>
	</div>
</div>
EOD;

// Finally, leave it all to the rendering phase of Zeus.
include(ZEUS_THEME_PATH);