<?php

/*	 DO NOT MODIFY BELOW THIS LINE
 * 	 Not a configuration snippet. This just loads all other apps config files. Leave it untouched.
 */

$config = array();
foreach(glob(SITE_PATH.'/app/*', GLOB_ONLYDIR) as $dir) {
    if (file_exists($dir.'/config.php')) {
		include $dir.'/config.php';
	}
}
$app->configs = $config;

