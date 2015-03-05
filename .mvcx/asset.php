<?php
$config = array();
foreach(glob('../app/*', GLOB_ONLYDIR) as $dir) {
    if (file_exists($dir.'/config.php')) {
        include $dir.'/config.php';
    }
}
include 'app.class.php';
$app = new App;
$app->config = $config;
$app_info =$app->getAppByUrl();
$template = '';
if ($app_info['template'] !== false && !empty($app_info['template'])) {
    $template = '/template/'. $app_info['template'];
}

$asset = '../app/'.$app_info['dir'].'/view'.$template.'/asset/'.$_GET['file'];

if (!file_exists($asset)){
    $asset = '../app/'.$app_info['dir'].'/view/asset/'.$_GET['file'];
}

if (!file_exists($asset)){
    $asset = '../.mvcx/mvc/view/asset/'.$_GET['file'];
}



if (!file_exists($asset)){
    header('HTTP/1.0 404 Not Found');
    echo 'RESOURCE NOT FOUND';exit;
}

$contentType = '';
$ext = strtolower(pathinfo($asset, PATHINFO_EXTENSION));

switch ($ext) {
case 'css':
    $contentType = 'text/css';
    break;
case 'map':
case 'js':
    $contentType = 'text/javascript';
    break;
case 'png':
    $contentType = 'image/png';
    break;
case 'jpg':
case 'jpeg':
    $contentType = 'image/jpeg';
    break;
case 'gif':
    $contentType = 'image/gif';
    break;
case 'ico':
    $contentType = 'image/ico';
    break;
case 'bmp':
    $contentType = 'image/bmp';
    break;
case 'tiff':
    $contentType = 'image/tiff';
    break;
case 'svg':
    $contentType = 'image/svg+xml';
    break;
}

if (empty($contentType)) {
    try {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $contentType = finfo_file($finfo, $asset);
        finfo_close($finfo);
    } catch (Exception $e) {

    }
}

if (empty($contentType)) {
    $contentType = 'text/plain';	
}

header('content-type:'.$contentType);
readfile($asset);
