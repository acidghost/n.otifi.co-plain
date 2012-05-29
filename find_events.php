<?php

require_once("./simplehtmldom/simple_html_dom.php");

$html = file_get_html("http://www.residentadvisor.net/dj/planetaryassaultsystems/dates");
$container = $html->find("div[id=container]", 0);
$t = $container->find("table", 1);
$tr = $t->find("tr", 3);
$td = $tr->find("td", 1);
$tbody2 = $td->find("table", 0);
$tr3 = $tbody2->find("tr", 0);
$td3 = $tr3->find("td", 0);
$table3 = $td3->find("table", 0);
$tr3 = $table3->find("tr", 1);
$td_field_dates = $tr3->find("td", 1);
$next_events = $td_field_dates->find("div[class=pl8]", 1);

if($next_events != null) {
	echo'<ul>';
    foreach($next_events->find("div[class=ptb4]") as $event_div) {
        echo'<li>'.$event_div->plaintext.'</li>';
    }
	echo'</ul>';
}

//echo($td_field_dates->plaintext);

//print_r($td_field_dates);

?>
