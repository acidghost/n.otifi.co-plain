<?php session_start();
			require("functions.php");
?>
<!doctype html>
<!--[if lt IE 7]> <html xmlns:fb="http://ogp.me/ns/fb#" class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html xmlns:fb="http://ogp.me/ns/fb#" class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html xmlns:fb="http://ogp.me/ns/fb#" class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html xmlns:fb="http://ogp.me/ns/fb#" class="no-js" lang="en"> <!--<![endif]-->
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# notifico: http://ogp.me/ns/fb/notifico#">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>n.otifi.co - Subscribe to your favourite artists!</title>
	
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width">
	
	<link rel="shortcut icon" href="favicon.ico">

	<link rel="stylesheet/less" href="less/style.less">
	<script src="js/libs/less-1.3.0.min.js"></script>
	
	<!-- Use SimpLESS (Win/Linux/Mac) or LESS.app (Mac) to compile your .less files
	to style.css, and replace the 2 lines above by this one:

	<link rel="stylesheet" href="less/style.css">
	 -->

	<!--[if lt IE 9]>
	<script src="js/libs/html5-3.4-respond-1.1.0.min.js"></script>
	<![endif]-->
</head>
<body>
<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

	<div id="fb-root"></div>

    <div class="container">
    
    	<div id="container-wrapper">

				<div id="content" class="span8 offset2">
					<h1 id="title">Follow your artist!</h1>
					<div id="fb-login-button"><fb:login-button show-faces="false" width="100" max-rows="0" scope="email,publish_stream">Connect</fb:login-button></div>
					<div class="row">
					<div class="span5">
						<form class="form-horizontal" method="post">
							<fieldset>
								<div class="control-group">
									<label for="email" class="control-label"><h6>E-MAIL</h6></label>
									<div class="controls"><input class="required email" type="email" placeholder="e-mail" name="email" id="email" value="<?php echo $_SESSION['email']; ?>" /></div>
								</div>
								<div class="control-group">
									<label for="artist" class="control-label"><h6>ARTIST</h6></label>
									<div class="controls"><input class="required" type="text" placeholder="artist" name="artist" id="artist" /></div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="span2">
						<a id="btn-submit" class="btn btn-primary btn-large"><i class="icon-ok icon-white"></i> Subscribe</a>
					</div>
					</div>
					
					<hr class="hidden">
					
					<div id="subscription-results" class="row">
						<div class="span3 offset1">
							<div class="content-container">
								
							</div>
						</div>
					</div>
					
					<hr>
					
					<div class="row">
					  
						<footer>
							  	<div class="row">
							  		<span id="fb-facepile" class="span5"><fb:facepile app_id="<?php echo $config['appid']; ?>" max_rows="1" width="550"></fb:facepile></span>
							  		<span id="fb-like-box" class="span2"><fb:like-box href="http://www.facebook.com/n.otifi.co" width="150" show_faces="false" stream="false" header="true"></fb:like-box></span>
							  	</div>
							  	<div class="row">
							  		<span class="bottom-nav">
								  		<ul class="nav nav-pills">
								  			<li class="item active"><a href="./">Subscribe</a></li>
								  			<li class="item"><a href="search.php">Search</a></li>
								  			<li class="item"><a href="help.html">Help</a></li>
								  			<li class="item"><a href="ideas.php">Submit ideas!</a></li>
								  		</ul>
								  	</span>
							  		<span class="credits">&copy; <a href="http://codeworks-eng.com/">Codeworks</a> <?php echo date("Y"); ?></span>
							  	</div>
						</footer>
						  
					</div><!-- /row -->
					
				</div><!-- /#content -->
				
		</div><!-- /#container-wrapper -->

    </div> <!-- /container -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<script src="js/libs/bootstrap/bootstrap.min.js"></script>

<script src="js/libs/fbExec.js"></script>

<script src="js/script.js"></script>
<script>
	var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));
</script>

</body>
</html>
