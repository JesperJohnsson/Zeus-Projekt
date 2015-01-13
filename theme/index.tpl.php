<!doctype html>
<html class='no-js' lang='<?=$lang?>'>
<head>
<meta charset='utf-8'/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=get_title($title)?></title>
<?php if(isset($favicon)): ?><link rel='shortcut icon' href='<?=$favicon?>'/><?php endif; ?>
<?php foreach($stylesheets as $val): ?>
<?php if(isset($inlinestyle)): ?><style><?=$inlinestyle?></style><?php endif; ?>
<link rel='stylesheet' type='text/css' href='<?=$val?>'/>
<?php endforeach; ?>
<script src='<?=$modernizr?>'></script>
</head>
<body>
  <div id='wrapper'>
  <div id='header'>
	<?php if(isset($topnav)): ?><div class="navbar navbar-default navbar-static-top hidden-sm hidden-xs" style="margin-bottom:0;"><?=($topnav)?></div><?php endif; ?>
    <?php if(isset($carousel)): ?><?=($carousel)?><?php endif; ?>
	<?=$header?><?php if(isset($navbar)): ?><div class="collapse navbar-collapse navHeaderCollapse"><?=get_navbar($navbar)?></div><?php endif; ?>
  </div>
  
  
    <div class="container" style="margin-top:25px;"><?=$main?></div>
    <div id='footer'><?=$footer?></div>
    <?php if(isset($debug)): ?><div id='debug'><?=$debug?></div><?php endif; ?>
  </div>

<?php if(isset($jquery)):?><script src='<?=$jquery?>'></script><?php endif; ?>
<?php if(isset($jquery2)):?><script src='<?=$jquery2?>'></script><?php endif; ?>

<?php if(isset($javascript_include)): foreach($javascript_include as $val): ?>
<script src='<?=$val?>'></script>
<?php endforeach; endif; ?>

<?php if(isset($google_analytics)): ?>
<script>
  var _gaq=[['_setAccount','<?=$google_analytics?>'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php endif; ?>

</body>
</html>