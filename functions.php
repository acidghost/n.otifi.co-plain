<?php

$mysql_host = "localhost";
$mysql_user = "root";
$mysql_pass = "monamona";
$mysql_db = "residentadvisorcrawler";

function convert_artist_name($artist_input) {
	$result = trim($artist_input);
	$result = preg_replace('/[^\w.]/','',$result);
	$result = strtolower($result);
	if(strcmp(substr($result, count($result)-1), ".") == 0) {
		$result = substr($result, 0, count($result)-2);
	}
	
	return $result; 
}

function genera_verify_code() {
	return md5(''.time()+$_SERVER['REQUEST_TIME'].''.rand(0, 9999));
}

function send_subscription_email($email, $code, $artist) {
	$subject = "Notification for subscription on $artist";
	$from = "From: noreply@codeworks-eng.com\r\n";
	$body = 'This e-mail address has been subscribed for updates on '.$artist.' events on <a href="http://www.residentadvisor.net">residentadvisor</a>.<br /><br />'.
		'If you want to delete this subscription, follow this <a href="http://ra.codeworks-eng.com/delete.php?c='.$code.'">link</a>';
	if(mail($email, $subject, $body, $from)) {
		return true;
	} else {
		return false;
	}
}

function curl_redir_exec($ch) {
	static $curl_loops = -1;
	static $curl_max_loops = 10;
	if ($curl_loops++ >= $curl_max_loops) {
		$curl_loops = 0;
		return false;
	}
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	
	list($header, $content) = explode(chr(10).chr(13).chr(10), $data);

	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if ($http_code == 301 || $http_code == 302) {
		$matches = array();
		$matches = explode(chr(10),$header);
		foreach( $matches as $value) {
			$pos = strpos($value, "Location:");
			if ($pos === 0) {
				list($variabile,$url) = explode(" ",$value);
			}
		}
		$url = parse_url(trim($url));
		if (!$url) { //couldn't process the url to redirect to
			$data = array($curl_loops,curl_getinfo($ch),$data, curl_error($ch));
			return $data;
		}
		$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
		if (!isset($url['scheme']))
			$url['scheme'] = $last_url['scheme'];
		if (!isset($url['host']))
			$url['host'] = $last_url['host'];
		if (!isset($url['path']))
			$url['path'] = $last_url['path'];
		$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . (isset($url['query'])?'?'.$url['query']:'');
		curl_setopt($ch, CURLOPT_URL, $new_url);
		return curl_redir_exec($ch);
	} else {
		$data = array($curl_loops,curl_getinfo($ch),$data, curl_error($ch));
		return $data;
	}
}

global $config;
$config = array();
$config['appid'] = '387585684626143';
$config['appsecret'] = '5182f2d4cb9942d67748c84102f66690';

?>
