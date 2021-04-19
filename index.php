<?php
require_once('vendor/autoload.php');
//require_once('php/autoloader.php');

$show_results = FALSE;
$results_html = "";
$final_result_html = "<hr>";

if(isset( $_GET['q'])) { // if there's a search query, show the results for it
    $query = urlencode($_GET["q"]);
    $show_results = TRUE;
    $search_url = "https://html.duckduckgo.com/html?q=" . $query;
    if(!$results_html = file_get_contents($search_url)) {
        $error_text .=  "Failed to get results, sorry :( <br>";
    }
    $simple_results=$results_html;
    $simple_results = str_replace( 'strong>', 'b>', $simple_results ); //change <strong> to <b>
    $simple_results = str_replace( 'em>', 'i>', $simple_results ); //change <em> to <i>
    $simple_results = clean_str($simple_results);

    $result_blocks = explode('<h2 class="result__title">', $simple_results);
    $total_results = count($result_blocks)-1;

    for ($x = 1; $x <= $total_results; $x++) {
        if(strpos($result_blocks[$x], '<a class="badge--ad">')===false) { //only return non ads
            // result link, redirected through our proxy
            $result_link = explode('class="result__a" href="', $result_blocks[$x])[1];
            $result_topline = explode('">', $result_link);
            $result_link = str_replace( '//duckduckgo.com/l/?uddg=', '/read.php?a=', $result_topline[0]);
            // result title
            $result_title = str_replace("</a>","",explode("\n", $result_topline[1]));
            // result display url
            $result_display_url = explode('class="result__url"', $result_blocks[$x])[1];
            $result_display_url = trim(explode("\n", $result_display_url)[1]);
            // result snippet
            $result_snippet = explode('class="result__snippet"', $result_blocks[$x])[1];
            $result_snippet = explode('">', $result_snippet)[1];
            $result_snippet = explode('</a>', $result_snippet)[0];

            $final_result_html .= "<br><a href='" . $result_link . "'><font size='4'><b>" . $result_title[0] . "</b></font><br><font color='#008000' size='2'>" 
                                . $result_display_url . "</font></a><br>" . $result_snippet . "<br><br><hr>";
        }
    }
}

//replace chars that old machines probably can't handle
function clean_str($str) {
    $str = str_replace( "‘", "'", $str );    
    $str = str_replace( "’", "'", $str );  
    $str = str_replace( "“", '"', $str ); 
    $str = str_replace( "”", '"', $str );
    $str = str_replace( "–", '-', $str );
    $str = str_replace( "&#x27;", "'", $str );

    return $str;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 2.0//EN">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
	<title>NewtFind! Browse webpages on Apple Newton!</title>
</head>
<body>

<?php if($show_results) { // there's a search query in q, so show search results ?>

	<center>
    <form action="/" method="get"><br>
    <a href="/"><font color="#008000">Newt</font><font color="#000000">Find!</font></a><br>
    <table><tbody><tr><td width="10%"></td><td width="80%"><input type="text" size="20" name="q" value="<?php echo urldecode($query) ?>"></td><td width="10%"></td></tr></tbody></table>
    <table><tbody><tr><td width="42%"></td><td width="20%"><input type="submit" value="NewtIt!"></td><td width="38%"></td></tr></tbody></table>
    </form>
	</center>
    <hr>
    <br>
    <center>Search Results for <b><?php echo strip_tags(urldecode($query)) ?></b></center>
    <br>
    <?php echo $final_result_html ?>
    
<?php } else { // no search query, so show new search ?>
    <br><br><center><h1><font size=7><font color="#008000">Newt</font>Find!</font></h1></center>
    <center><h4>The Search Engine for Apple Newton devices.</h4></center>
    <br><br>
    <center>
    <table><tbody><tr><td width="10%"></td><td width="80%"><form action="/" method="get"><input type="text" size="20" name="q"></td><td width="10%"></td></tr></tbody></table>
    <table><tbody><tr><td width="42%"></td><td width="20%"><input type="submit" value="NewtIt!"></td><td width="38%"></td></tr></tbody></table>
    </center>
    <br>
    <small><center>Built by <b><a href="https://youtube.com/ActionRetro">Action Retro</a></b> on YouTube | <a href="http://frogfind.com/about.php">Why build such a thing?</a></center></small>
    <small><center>Modified by <a href="https://youtube.com/AppleNewtonFan">Apple Newton Fan</a> for Apple Newton devices,</center></small>
    <small><center>with programming support by <a href="https://twitter.com/morgant">Morgan.</a></center></small><br>
    <small><center>Powered by DuckDuckGo</center></small>
</form>
</form>

<?php } ?>

</body>
</html>