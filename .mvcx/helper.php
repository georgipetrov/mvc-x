<?php

function pr($v='') { // Print
	echo '<pre>';
	var_dump($v);
	echo '</pre>';
}

function echoine(&$v,$v_if_empty='',$v_if_not_empty='') { // Echo If Not Empty
	if (!empty($v)) {
		if (empty($v_if_not_empty)) {
			echo $v;
		} else {
			echo $v_if_not_empty;	
		}
	} else {
		echo $v_if_empty;
	}
}

function returnine(&$v,$v_if_empty='',$v_if_not_empty='') { // Return If Not Empty
	if (!empty($v)) {
		if (empty($v_if_not_empty)) {
			return $v;
		} else {
			return $v_if_not_empty;
		}
	} else {
		return $v_if_empty;
	}
}

function obfuscate($text) {
	return $text;
	 $key = ('ahatovaekluchazasklada'); 
	 $outText = '';
	 // Iterate through each character
	 for($i=0;$i<strlen($text);)
	 {
		 for($j=0;($j<strlen($key) && $i<strlen($text));$j++,$i++)
		 {
			 $outText .= $text{$i} ^ $key{$j};
			 //echo 'i='.$i.', '.'j='.$j.', '.$outText{$i}.'<br />'; //for debugging
		 }
	 }  
	 

	 return $outText;
}

function deobfuscate($text) {	
	return obfuscate($text);	
}

function timeago($time)
{
    $time = time() - strtotime($time);
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'min',
        1 => 'second'
    );
	
	if ($time < 60) {
		return 'Just now';	
	}

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
    }
}

function bytesToHuman($bytes) {
    $kb = 1024;
    $mb = $kb * 1024;
    $gb = $mb * 1024;
    $tb = $gb * 1024;

    $ranges = array(
        $tb => ' TB',
        $gb => ' GB',
        $mb => ' MB',
        $kb => ' KB'
    );

    foreach ($ranges as $limit=>$human_str) {
        if ($bytes >= $limit) {
            return number_format($bytes / $limit, 2) . $human_str;
        }
    }

    return $bytes . $human_str;
}

function logerror($errno, $errstr, $errfile='', $errline=0) {
	$filename='error.txt';
	$append = '';
	if (!empty($errfile) && !empty($errline)) {
	 $append = " - Error on line $errline in $errfile";	
	}
	$log = "[".date("F j, Y, g:i a")."] Error $errno: $errstr".$append.PHP_EOL;
	file_put_contents($filename,$log,FILE_APPEND);
	chmod($filename, 0640);
}
set_error_handler("logerror");
