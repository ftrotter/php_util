<?php

// Globally accessible php functions 

function mylogger($message){
	
	if($GLOBALS['debug']){
		syslog(LOG_WARNING, "Record: $message");
	}

}

/** {{{ http://code.activestate.com/recipes/576894/ (r5) */

/**
 * This function generates a password salt as a string of x (default = 15) characters
 * in the a-zA-Z0-9!@#$%&*? range.
 * @param $max integer The number of characters in the string
 * @return string
 * @author AfroSoft <info@afrosoft.tk>
 */
function generateSalt($max = 15) {
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
        $i = 0;
        $salt = "";
        while ($i < $max) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
}

/** end of http://code.activestate.com/recipes/576894/ }}} */



// IE6 apparently does not like "Refresh"
//so we are going to have to replace ALL of our
//refresh calls to not use them...
//by creating a function, we can ensure further problems
//can be dealt with system wide.
function bounce($url,$time_in_seconds = 0){
	//If there is a 0 time, then lets use location, since it always seems to work...

	$debug = false;
	if($debug){
                        echo '<html><head></head><body>';
                        echo "bouncing to <a href='$url'>$url</a> from functions.php";
                        echo '</body></html>';
	}else{	

		if($time_in_seconds <= 0){
			header("Location: $url");
		}else{
			header("Refresh: $time_in_seconds; URL=$url");
		}
	}
}


function bounce_once($url,$time_in_seconds = 0){
	//this function will check to see if the user has been bounced somewhere one time
	//if not it sends them to that location..
	//if so then it simply returns 

	$user = $_SESSION['user_id'];

	mylogger("starting bounce_once to $url");

	if(isset($_SESSION['bounced'][$user][$url])){
		mylogger("already set");
		
		if($_SESSION['bounced'][$user][$url]){
			mylogger("already bounced");
			//then we have already displayed this prompt once, and the user has decided...
			//moving along...
			return;
		}
		//if we get here, then we should bounce again...
	}
	mylogger("bouncing");
	$_SESSION['bounced'][$user][$url] = true;

	if($time_in_seconds <= 0){
		header("Location: $url");
		exit();
	}else{
		header("Refresh: $time_in_seconds; URL=$url");
		exit();
	}
}

function prompt($prompt_name,$prompt_message){
	//this function will check to see if the user has been given a particular prompt yet...
	//if they have... then it simple returns
	//if they have not it forwards the user to the prompt controller with info in the session
	//to properly prompt the user... who can then make choices based on the links in the message

	if(isset($_SESSION['prompted'][$prompt_name])){
		if($_SESSION['prompted'][$prompt_name]){
			//then we have already displayed this prompt once, and the user has decided...
			//moving along...
			return;
		}
		//if we get here, then we should show the prompt again...
	}

	//this is what will actually be displayed to the user...
	$_SESSION['prompt_message'] = $prompt_message;
	//which prompt we are working on...
	$_SESSION['prompt_name'] = $prompt_name;
	$_SESSION['prompted'][$prompt_name] = true;
	$_SESSION['prompt_displayed'][$prompt_name] = false;
	//ok now we forward the user to the prompt controller
	header("Refresh: 0; URL=/index.php/xhtmlsimple/prompt/prompt");
	//lets not send anything else...
	exit();
}

function detect_iphone(){

	$iphone = false;
	if(isset($_SERVER['HTTP_USER_AGENT'])){
	$container = $_SERVER['HTTP_USER_AGENT'];

	$useragents = array ( "iPhone","iPod");
	foreach ( $useragents as $useragent ) {
		if (eregi($useragent,$container)){
			$iphone = true;
		}
	}
	}
	
	return($iphone);

}

function detect_ipad(){

	$ipad = false;
	if(isset($_SERVER['HTTP_USER_AGENT'])){
	$container = $_SERVER['HTTP_USER_AGENT'];

	$useragents = array ( "iPad");
	foreach ( $useragents as $useragent ) {
		if (eregi($useragent,$container)){
			$ipad = true;
		}
	}
	}
	return($ipad);

}

function generatePassword($length=9,$strength = 0) {
	// do not use strength, generate a VistA password
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvwxz';
	// not second case b/c vista cannot tell the difference..
	$numbers = '23456789';
	// removed $ because it could have meaning in the context of a bash script...
	//
	$special = '@#%&*()_-+=';
	$consonants .= $numbers;
	$consonants .= $special;
 
	$length = $length - 2; //we add two at the end no matter what.

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	//add one number and one special character at the end to make a valid vista
	//password....
	$password .= $special[(rand() % strlen($special))];
	$password .= $numbers[(rand() % strlen($numbers))];
	


	return $password;
}
 


    function shuffle_assoc(&$array) {
        $keys = array_keys($array);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }



	function pretty_date($date,$unix = false)
	{

	if(!$unix){// this is from SQL
		$time_stamp = strtotime($date);
	}else{
		$time_stamp = $date;
	}

	$today_midnight = mktime(0, 0, 0, date("m") , date("d") , date("Y"));

	if($time_stamp > $today_midnight){
		//date is today just display the time
		$date_string = date( 'g:i a',$time_stamp);
	}else{// before today
		$jan_first = mktime(0,0,0,1,1,date("Y"));	
		if($time_stamp > $jan_first){
			$date_string = date('M j',$time_stamp);
		
		}else{ // last years dates
		
			$date_string = date('F j, Y',$time_stamp);
		}
	}

	return $date_string;
	}


	function getorpost($term){

		if(isset($_GET[$term])){
			return $_GET[$term];
		}

		if(isset($_POST[$term])){
			return $_POST[$term];
		}
		
		if(isset($_SESSION[$term])){	
			return $_SESSION[$term];
		}
		return false;		

	}

	function curPageURL() {
 		$pageURL = 'http';
	        if(isset($_SERVER["HTTPS"])){
                        if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
                }	
 		$pageURL .= "://";
 		if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
  			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 		} else {
  			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 		}
 		return $pageURL;
	}

/** Prettifies an XML string into a human-readable and indented work of art
 *  @param string $xml The XML as a string
 *  @param boolean $html_output True if the output should be escaped (for use in HTML)
 */
function xmlpp($xml, $html_output=true) {
  $xml_obj = new SimpleXMLElement($xml);
  $level = 4;
  $indent = 0; // current indentation level
  $pretty = array();

  // get an array containing each XML element
  $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));

  // shift off opening XML tag if present
  if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {
    $pretty[] = array_shift($xml);
  }

  foreach ($xml as $el) {
    if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {
      // opening tag, increase indent
      $pretty[] = str_repeat(' ', $indent) . $el;
      $indent += $level;
    } else {
      if (preg_match('/^<\/.+>$/', $el)) {
        $indent -= $level;  // closing tag, decrease indent
      }
      if ($indent < 0) {
        $indent += $level;
      }
      $pretty[] = str_repeat(' ', $indent) . $el;
    }
  }
  $xml = implode("\n", $pretty);
  return ($html_output) ? nl2br(htmlentities($xml)) : $xml;
}


function file_upload_error_message($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'File upload stopped by extension';
        default:
            return 'Unknown upload error';
    }
} 


function wpautop($pee, $br = 1) {

        if ( trim($pee) === '' )
                return '';
        $pee = $pee . "\n"; // just to make things a little easier, pad the end
        $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
        // Space things out a little
        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|option|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
        $pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);        $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
        $pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
        if ( strpos($pee, '<object') !== false ) {
                $pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embe
                $pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
        }
        $pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates        // make paragraphs, including one at the end
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
        $pee = '';
        foreach ( $pees as $tinkle )
                $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
        $pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
        $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
        if ($br) {
                $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', create_function('$matches', 'return str_replace("\n", "<WPPreserveNewline />", $matches[0]);'), $pee);
                $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
                $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
        }
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        if (strpos($pee, '<pre') !== false)
                $pee = preg_replace_callback('!(<pre[^>]*>)(.*?)</pre>!is', 'clean_pre', $pee );
        $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

        return $pee;
}







?>
