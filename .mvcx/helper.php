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