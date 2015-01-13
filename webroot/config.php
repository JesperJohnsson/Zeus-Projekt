<?php
/**
 * Config-file for Zeus. Change settings here to affect installation.
 *
 */
 
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly
 
 
/**
 * Define Zeus paths.
 *
 */
define('ZEUS_INSTALL_PATH', __DIR__ . '/..');
define('ZEUS_THEME_PATH', ZEUS_INSTALL_PATH . '/theme/render.php');
 
 
/**
 * Include bootstrapping functions.
 *
 */
include(ZEUS_INSTALL_PATH . '/src/bootstrap.php');
 
 
/**
 * Start the session.
 *
 */
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();
 
 
/**
 * Create the Zeus variable.
 *
 */
$zeus = array();
 
 /**
 * Settings for the database.
 *
 */
// settings for student-server
define('DB_PASSWORD', 's6O?#L7c');
$zeus['database']['dsn']            = 'mysql:host=blu-ray.student.bth.se;dbname=jejd14;';
$zeus['database']['username']       = 'jejd14';
$zeus['database']['password']       = DB_PASSWORD;
$zeus['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"); 
 // settings for local server
/* $zeus['database']['dsn']            = 'mysql:host=localhost;dbname=Movie;';
$zeus['database']['username']       = 'root';
$zeus['database']['password']       = '';
$zeus['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"); */
 
 
 /** 
/ Load login class to have access to user 
*/ 
$CUse = new CUser($zeus['database']); 
$acronym = $CUse->GetAcronym();
 
 
/**
 * Site wide settings.
 *
 */
$zeus['lang']         = 'sv';
$zeus['title_append'] = ' | oophp kursmaterial | Powered by Zeus';

$zeus['topnav'] = <<<EOD
<div class="navbar navbar-default navbar-static-top hidden-sm hidden-xs" style="margin-bottom:0;" >
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-6">
					<div class="navbar-header">
						<a href='home.php'><img src="img/hipsterlogogenerator_1419078339287.png" height="100"></a>
						<a href='home.php'><img src="img/hipsterlogogenerator_1419078183456.png" height="100"></a>
					</div>
				</div>
				<div class="col-sm-6" style="margin-top:35px;">
					<form method="get" action="movies.php">
						<div id="imaginary_container"> 
							<div class="input-group stylish-input-group">
								<input type="text" class="form-control" name="title2"  placeholder="Sök med titel (delsträng, använd % som *)" >
								<span class="input-group-addon">
									<button type="submit">
										<span class="glyphicon glyphicon-search"></span>
									</button>
								</span>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div> 
EOD;

$zeus['carousel'] = <<<EOD
<div class="carousel fade-carousel slide hidden-xs hidden-sm" data-ride="carousel" data-interval="4000" id="bs-carousel">
  <!-- Overlay -->
  <div class="overlay"></div>

  <!-- Indicators --><!-- 
  <ol class="carousel-indicators">
	<li data-target="#bs-carousel" data-slide-to="0" class="active"></li>
	<li data-target="#bs-carousel" data-slide-to="1"></li>
	<li data-target="#bs-carousel" data-slide-to="2"></li>
  </ol>-->
  
  <!-- Wrapper for slides -->
  <div class="carousel-inner">
	<div class="item slides active">
	  <div class="slide-1"></div>
	  <div class="hero">
		<hgroup>
			<h3>Hyr film online</h3>
		</hgroup>
	  </div>
	</div>
	<div class="item slides">
	  <div class="slide-2"></div>
	  <div class="hero">
	  </div>
	</div>
	<div class="item slides">
	  <div class="slide-3"></div>
	  <div class="hero">
	  </div>
	</div>
  </div> 
</div>
EOD;

$zeus['header'] = <<<EOD
<div class="navbar navbar-default navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			
			<button class="navbar-toggle" data-toggle="collapse" data-target = ".navHeaderCollapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
EOD;

$zeus['footer'] = <<<EOD
<div class="navbar navbar-default navbar-fixed-bottom your_class">
		
	<div class="container">
		<p class="hidden-xs navbar-text text-center">Copyright (c) RM Rental Movies | <a href='https://github.com/JesperJohnsson/zeus'>Zeus på GitHub</a> | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></p>
	
		<nav class=" navbar-text your_class">
			<ul class='icons'>
				<li><a href='#'><img src='http://dbwebb.se/img/glyphicons/png/glyphicons_362_google+_alt.png' alt='google+-icon' title='Jesper på Google+' width='24' height='24'/></a></li>
				<li><a href='#'><img src='http://dbwebb.se/img/glyphicons/png/glyphicons_377_linked_in.png' alt='linkedin-icon' title='Jesper på LinkedIn' width='24' height='24'/></a></li>
				<li><a href='#'><img src='http://dbwebb.se/img/glyphicons/png/glyphicons_390_facebook.png' alt='facebook-icon' title='Jesper på Facebook' width='24' height='24'/></a></li>
				<li><a href='#'><img src='http://dbwebb.se/img/glyphicons/png/glyphicons_392_twitter.png' alt='twitter-icon' title='Jesper på Twitter' width='24' height='24'/></a></li>
				<li><a href='#'><img src='http://dbwebb.se/img/glyphicons/png/glyphicons_382_youtube.png' alt='youtube-icon' title='Jesper på YouTube' width='24' height='24'/></a></li>
				<li><a href='#'><img src='http://dbwebb.se/img/glyphicons/png/glyphicons_395_flickr.png' alt='flickr-icon' title='Jesper på Flickr' width='24' height='24'/></a></li>
				<li><a href='https://github.com/JesperJohnsson/zeus'><img src='http://dbwebb.se/img/glyphicons/png/glyphicons_381_github.png' alt='github-icon' title='Jesper på GitHub' width='24' height='24'/></a></li>
				<li><a href='#'><img src='http://dbwebb.se/img/glyphicons/png/glyphicons_412_instagram.png' alt='instagram-icon' title='Jesper på Instagram' width='24' height='24'/></a></li>
			</ul>
		</nav>
	</div>
</div>
EOD;

$zeus['byline'] = <<<EOD
<div class="row">
	<div class="col-md-12 text-center">
		<hr></hr>
		<footer class="byline">
			<figure><img src="http://www.student.bth.se/~jejd14/oophp/kmom02/zeus/webroot/img/me.jpg" alt="Bild Jesper" height="150">
				<figcaption>En glad Jesper.</figcaption>
			</figure>
			<p>Jag går just nu mitt första år på BTH, på programmet webbprogrammering.</p>
		</footer>
		
	</div>
</div>
EOD;


/* $zeus['navlog'] = array(
	'class' => 'nb-plain',
	
		'items' => array(
	
			'loggain'  => array(
			   'text'  => '<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Account',   
			   'url'   => 'movie_login.php',  
			   'title' => 'Logga in',
		),
	),
	// This is the callback tracing the current selected menu item base on scriptname
  'callback' => function($url) {
    if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      return true;
    }
  }
); */

/**
 * The navbar
 *
 */
//$zeus['navbar'] = null; // To skip the navbar

if($acronym)
{
$zeus['navbar'] = array(
  // Use for styling the menu
  'class' => 'nb-plain',
 
  // Here comes the menu strcture
  'items' => array(
    // This is a menu item
    'hem'  => array(
      'text'  =>'<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Hem',   
      'url'   =>'home.php',  
      'title' => 'Hem'
    ),
	
	// This is a menu item
    'filmer'  => array(
      'text'  =>'<span class="glyphicon glyphicon-film" aria-hidden="true"></span> Filmer <b class ="caret"></b>',   
      'url'   =>'movie_view.php',   
      'title' => 'Filmer',
	  'class2' => 'dropdown-toggle',
	  'data' => 'dropdown',
 
      // Here we add the submenu, with some menu items, as part of a existing menu item
      'submenu' => array(
		
        'items' => array(
          // This is a menu item of the submenu
          'item 1'  => array(
            'text'  => 'Alla filmer',   
            'url'   => 'movies.php',  
            'title' => 'Alla filmer'
          ),
 
          // This is a menu item of the submenu
          'item 2'  => array(
            'text'  => 'Populäraste filmen',
            'url'   => 'movie.php?id=12',  
            'title' => 'De populäraste filmerna',
          ),
        ),
      ),
    ),
	
	// This is a menu item
	'nyheter'  => array(
      'text'  =>'<span class="glyphicon glyphicon-globe" aria-hidden="true"></span> Nyheter',   
      'url'   =>'blog.php',  
      'title' => 'Nyheter'
    ),
	
	'about'  => array(
	   'text'  => '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Om företaget',   
	   'url'   => 'about.php',  
	   'title' => 'Om företaget',
	),
	
	'users'  => array(
	   'text'  => '<span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> Användare',   
	   'url'   => 'user.php',  
	   'title' => 'Användare',
	),
	
	
	'account'  => array(
      'text'  =>'<span class="glyphicon glyphicon-user" aria-hidden="true"></span> ' . $_SESSION['user']->acronym . ' <b class ="caret"></b>',   
      'url'   =>'#',   
      'title' => $_SESSION['user']->acronym,
	  'class2' => 'dropdown-toggle',
	  'data' => 'dropdown',
 
      // Here we add the submenu, with some menu items, as part of a existing menu item
      'submenu' => array(
		
        'items' => array(
          // This is a menu item of the submenu
          'item 1'  => array(
            'text'  => 'Inställningar',   
            'url'   => 'settings.php',  
            'title' => 'Inställningar'
          ),
 
          // This is a menu item of the submenu
          'item 2'  => array(
            'text'  => 'Logga ut',   
            'url'   => 'logout.php',  
            'title' => 'Logga ut',
          ),
        ),
      ),
    ),
	
	'admin'  => array(
	   'text'  => '<span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> Administration',   
	   'url'   => 'admin.php',  
	   'title' => 'Administration',
	),
  ),
 
  // This is the callback tracing the current selected menu item base on scriptname
  'callback' => function($url) {
    if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      return true;
    }
  }
);
}
else
{
$zeus['navbar'] = array(
  // Use for styling the menu
  'class' => 'nb-plain',
 
  // Here comes the menu strcture
  'items' => array(
    // This is a menu item
    'hem'  => array(
      'text'  =>'<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Hem',   
      'url'   =>'home.php',  
      'title' => 'Hem'
    ),
	
    // This is a menu item
    'filmer'  => array(
      'text'  =>'<span class="glyphicon glyphicon-film" aria-hidden="true"></span> Filmer <b class ="caret"></b>',   
      'url'   =>'movie_view.php',   
      'title' => 'Filmer',
	  'class2' => 'dropdown-toggle',
	  'data' => 'dropdown',
 
      // Here we add the submenu, with some menu items, as part of a existing menu item
      'submenu' => array(
		
        'items' => array(
          // This is a menu item of the submenu
          'item 1'  => array(
            'text'  => 'Alla filmer',   
            'url'   => 'movies.php',  
            'title' => 'Alla filmer'
          ),
 
          // This is a menu item of the submenu
          'item 2'  => array(
            'text'  => 'Populäraste filmen',   
            'url'   => 'movie.php?id=12',  
            'title' => 'De populäraste filmerna',
          ),
        ),
      ),
    ),
		
	// This is a menu item
	'nyheter'  => array(
      'text'  =>'<span class="glyphicon glyphicon-globe" aria-hidden="true"></span> Nyheter',   
      'url'   =>'blog.php',  
      'title' => 'Nyheter'
    ),
	
	'about'  => array(
	   'text'  => '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Om företaget',   
	   'url'   => 'about.php',  
	   'title' => 'Om företaget',
	),
	
	'users'  => array(
	   'text'  => '<span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> Användare',   
	   'url'   => 'user.php',  
	   'title' => 'Användare',
	),
	
	'loggain'  => array(
	   'text'  => '<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Logga in',   
	   'url'   => 'login.php',  
	   'title' => 'Logga in',
	),
	
	'register'  => array(
	   'text'  => '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Registrera konto',   
	   'url'   => 'register.php',  
	   'title' => 'Registrera konto',
	),
  ),
 
  // This is the callback tracing the current selected menu item base on scriptname
  'callback' => function($url) {
    if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      return true;
    }
  }
);
}
/**
 * Theme related settings.
 *
 */
//$zeus['stylesheet'] = 'css/style.css';
$zeus['stylesheets'] = array('css/style.css','css/bootstrap.min.css','css/styles.css');
$zeus['favicon']    = 'favicon.ico';

/**
 * Settings for JavaScript.
 *
 */
$zeus['modernizr'] = 'js/modernizr.js';
$zeus['jquery'] = 'http://code.jquery.com/jquery-1.11.0.min.js';
$zeus['jquery2'] = 'http://code.jquery.com/jquery-migrate-1.2.1.min.js';

//$zeus['jquery'] = null; // To disable jQuery

$zeus['javascript_include'] = array('js/bootstrap.js'); // To add extra javascript files

if(!$CUse->IsAdmin())
{
	array_push($zeus['javascript_include'], 'js/test.js');
}


/**
 * Google analytics.
 *
 */
$zeus['google_analytics'] = 'UA-56629130-1'; // Set to null to disable google analytics
