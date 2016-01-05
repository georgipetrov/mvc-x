<?php

function pr($v='') { // Print
	echo '<pre>';
	var_dump($v);
	echo '</pre>';
}

function echoine(&$v,$v_if_empty='') { // Echo If Not Empty
	if (!empty($v)) {
		echo $v;
	} else {
		echo $v_if_empty;
	}
}

function returnine(&$v,$v_if_empty='') { // Return If Not Empty
	if (!empty($v)) {
		return $v;
	} else {
		return $v_if_empty;
	}
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
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
    }
}