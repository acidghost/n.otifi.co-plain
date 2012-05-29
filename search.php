<?php
session_start();

$artist = isset($_GET["artist"]) ? ($_GET["artist"]) : (null);
$from_search = (isset($_GET["fs"]) && (strcmp($_GET["fs"], "1") == 0 || strcmp($_GET["fs"], "0") == 0)) ? ($_GET["fs"]) : ("0");

$page_title = '';
$title = '';
$content = '';
$result = '';

if($artist!=null) {
	require("simplehtmldom/simple_html_dom.php");
	require("functions.php");
	if(strcmp($from_search, "0")==0) {
		// comes from a search
	
		//$artist = htmlspecialchars($artist);
		$artist_bck = $artist;
		$artist = preg_replace('/&/', '', $artist);
		$artist = preg_replace('/\s/', '+', $artist);
		$url = "http://www.residentadvisor.net/search.aspx?section=djs&searchstr=$artist";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
		//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		//curl_setopt($curl, CURLOPT_TIMEOUT, 120);
		//curl_setopt($curl, CURLOPT_BUFFERSIZE, 1000000000000);
		//curl_setopt($curl, CURLOPT_MAXREDIRS, 120);
		//curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		//curl_setopt($curl, CURLOPT_FAILONERROR, 1);
		$str = curl_redir_exec($curl);
		curl_close($curl);
		//$search_page = file_get_html($url);
		$search_page = str_get_html($str[2]);
		$active_page = $search_page->find("span[class=cat-subnav-on]", 0)->plaintext;
	} 
	if(strcmp($from_search, "1")==0 || strcmp($active_page, 'About')==0) {
		// road to exact match
	
		$active_page = "About";
		if(isset($search_page)) {
			$search_page->clear();
			unset($search_page);
		}
		if($artist_bck) {
			$artist = preg_replace('/\s/', '', $artist_bck);
		}
		$url = "http://www.residentadvisor.net/dj/$artist";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
		$str = curl_exec($curl);
		curl_close($curl);
		//$search_page = file_get_html($url);
		$search_page = str_get_html($str);
	}
	if(strcmp($active_page, "About")==0) {
		// artist found - exact match
		
		$title .= '<span id="n_results">exact</span> match - artist found!';
		$result .= '<hr><div class="row"><div id="results">';
		$result .= '<div class="span3">'.
									'<a target="_blank" href="http://www.residentadvisor.net/dj/'.$artist.'">'.
									'<img id="dj-img-large" src="http://www.residentadvisor.net/images/profiles/'.$artist.'.jpg" />'.
									'</a>'.
							 '</div>';
		$artist_found_name = $search_page->find("h1[class=title2]", 0)->plaintext;
		$result .= '<div class="span2">'.
							 '<a id="ajax-subscribe" class="btn btn-inverse btn-large"><i class="icon-ok icon-white"></i><p>Subscribe to<br />'.$artist_found_name.'</p>';
		if(isset($_SESSION['email'])) {
			$result .= '<span class="as-email">as '.$_SESSION['email'].'</span>';
		}
		$result .= '</a></div>';
		$result .= '<div class="span2" id="ajax-result"></div>';
		$result .= '</div></div>';
		$page_title .= 'Subscribe to '.$artist_found_name.' on n.otifi.co';
	} else {
		$search_res_container = $search_page->find("td[class=white-bg]", 0);
		$search_n_res = $search_res_container->find("b", 0)->plaintext;
		if(strcmp($search_n_res, '0')!=0) {
			// display results
		
			$search_res = $search_res_container->find("span[class=black]", 1);
			$search_res_as = $search_res->find("a");
			$page_title .= sizeof($search_res_as).' result on n.otifi.co';
			$title .= '<span id="n_results">'.sizeof($search_res_as).'</span> artist'.( (sizeof($search_res_as)>1) ? ('s'): ('') ).' found';
			$result .= '<hr><div class="row"><div id="results"><h4>RESULTS</h4><br />';
			$i=0;
			foreach($search_res_as as $a) {
				$link = $a->href;
				$id_artist = explode('/', $link);
				$result .= '<a href="search.php?artist='.$id_artist[2].'&fs=1">'.$a->plaintext.'</a>';
				$i++;
				if($i < sizeof($search_res_as))
					$result .= ' | ';
			}
			$result .= '</div></div>';
		} else {
			$page_title .= 'n.otifi.co';
			$title .= '<span id="n_results">no</span> results were found';
			$content .= 'Searched for <strong>'.$artist.'</strong>';
		}
	}
	if(isset($search_page)) {
		$search_page->clear();
		unset($search_page);
	}
} else {
	$page_title .= 'Search for your artist on n.otifi.co!';
	$title .= 'Search for your artist';
}
?>

<!doctype html>
<!--[if lt IE 7]> <html xmlns:fb="http://ogp.me/ns/fb#" class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html xmlns:fb="http://ogp.me/ns/fb#" class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html xmlns:fb="http://ogp.me/ns/fb#" class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html xmlns:fb="http://ogp.me/ns/fb#" class="no-js" lang="en"> <!--<![endif]-->
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# notifico: http://ogp.me/ns/fb/notifico#">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $page_title; ?></title>
<?php if($artist != null && strcmp($from_search, "1")==0) : ?>
	<meta property="fb:app_id" content="<?php echo $config['appid']; ?>" /> 
	<meta property="og:type"   content="notifico:artist" /> 
	<meta property="og:url"    content="http://<?php echo $_SERVER[SERVER_NAME].$_SERVER[PHP_SELF].'?'.htmlspecialchars_decode($_SERVER[QUERY_STRING]); ?>" /> 
	<meta property="og:title"  content="<?php echo $artist_found_name; ?>" /> 
	<meta property="og:image"  content="http://www.residentadvisor.net/images/profiles/<?php echo $artist; ?>.jpg" />
<?php endif; ?>
	
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width">
	
	<link rel="shortcut icon" href="./favicon.ico">

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
					<h1 id="title"><?php echo $title; ?></h1>
					<div id="fb-login-button"><fb:login-button show-faces="false" width="100" max-rows="0" scope="email,publish_stream">Connect</fb:login-button></div>
					<div>
						<form class="form-inline" method="get">
							<label for="artist"><h6>ARTIST</h6></label>
							&nbsp;&nbsp;
							<input type="text" id="artist" placeholder="artist" name="artist" class="required" />
							&nbsp;&nbsp;
							<input type="hidden" name="fs" value="0" />
							<a id="btn-search" class="btn btn-primary btn-large"><i class="icon-search icon-white"></i> Search</a>
						</form>
					</div>
					
					<p><?php echo $content; ?></p>
					
					<?php	echo $result; ?>
										
					<hr>
					
					<div class="row">
					  
						<footer>
							  	<div class="row">
							  		<span id="fb-facepile" class="span8"><fb:facepile action="notifico:subscribe" max_rows="1" width="650"></fb:facepile></span>
							  	</div>
							  	<div class="row">
							  		<span class="bottom-nav">
								  		<ul class="nav nav-pills">
								  			<li class="item"><a href="./">Subscribe</a></li>
								  			<li class="item active"><a href="search.php">Search</a></li>
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
    
    <div id="nomail-modal" class="modal hide fade">
    	<div class="modal-header">
    		<button class="close" data-dismiss="modal">×</button>
    		<h3>SET YOUR E-MAIL</h3>
    	</div>
    	<div class="modal-body">
    		<p>Enter here your e-mail address, press Save and you've done! :)</p>
    		<form id="modal-form">
    			<input type="email" class="required email" id="modal-email" placeholder="e-mail" />
    		</form>
    	</div>
    	<div class="modal-footer">
    		<a href="#" id="modal-submit" class="btn btn-primary">Save</a>
    		<a href="#" class="btn" data-dismiss="modal">Close</a>
    	</div>
    </div>
    
    <form id="ajax-form">
    	<input type="hidden" id="ajax-artist" name="artist" value="<?php echo $_GET['artist']; ?>" />
    	<input type="hidden" id="ajax-email" name="email" value="<?php echo isset($_SESSION['email']) ? ($_SESSION['email']) : (null); ?>" />
    </form>
    
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
