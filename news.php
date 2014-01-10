<?php

include("../scraper/scraper.php");
$url = "http://www.bbc.co.uk/news/world-south-asia-12651483";

$html = get_page($url);
echo "<pre>";
preg_match_all('/<p>(.*?)<\/p>/is', $html, $matches, PREG_SET_ORDER);

$content = '<?xml version="1.0" encoding="utf-8"?>'
        . '<rows>';
$lines = array();
foreach ($matches as $match) {
    if (isset($match[1])) {
        $str = trim($match[1]);
        
        if (stripos($str, "<strong>") !== false) {
            //echo $str ;
            if (1 === preg_match('~[0-9]~', $str)) {
                $year = trim(substr($str, stripos($str, "<strong>"), stripos($str, "</strong>")));
                $year = trim(str_replace(array("<strong>","</strong>"),"",$year));
                
                    //echo "<h1>$year</h1>";
                    
                    $event = trim(substr($str, stripos($str, "</strong>") + strlen("</strong>"), strlen($str)));
                    $event = str_replace('"','',$event);
                    $event = trim(str_replace(array("-","<strong>","</strong>"),"",$event));
                    //echo '<div>' . $event . '</div>';
                    //$lines[] = $year.','.'"'.$event.'"';
                    
                    $lines[] = "<row><year>$year</year><event>$event</event></row>";
                    
            }
        }
    }
}

if(count($lines) != 0){
    print_r($lines);
    $content .= implode('',$lines);
    $content .= '</rows>';
    file_put_contents("xml/bangladesh_timeline.xml",$content);
}
?>