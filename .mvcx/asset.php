<?php
$config = array();
foreach(glob('../app/*', GLOB_ONLYDIR) as $dir) {
    if (file_exists($dir.'/config.php')) {
		include $dir.'/config.php';
	}
}
include('app.class.php');
$app = new App;
$app_info =$app->getAppByUrl($config);
$asset = '../app/'.$app_info['dir'].'/view/asset/'.$_GET['file'];

if (!file_exists($asset)){
	header('HTTP/1.0 404 Not Found');
	echo 'RESOURCE NOT FOUND';exit;
}

$contentType = 'text/plain';

try {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$contentType = finfo_file($finfo, $asset);
	finfo_close($finfo);
} catch (Exception $e) {
	
}

header('content-type:'.$contentType);
readfile($asset);
exit;
?>