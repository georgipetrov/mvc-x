<?php

include SITE_PATH . '/.mvcx/' . 'registry.class.php';
include SITE_PATH . '/.mvcx/' . 'base.class.php';
include SITE_PATH . '/.mvcx/' . 'helper.php';
include SITE_PATH . '/.mvcx/' . 'db.class.php';
include SITE_PATH . '/.mvcx/' . 'lib.class.php';
include SITE_PATH . '/.mvcx/' . 'controller.class.php';
include SITE_PATH . '/.mvcx/' . 'xcontroller.class.php';
include SITE_PATH . '/.mvcx/' . 'request.class.php';
include SITE_PATH . '/.mvcx/' . 'session.class.php';
include SITE_PATH . '/.mvcx/' . 'model.class.php';
include SITE_PATH . '/.mvcx/' . 'app.class.php';
include SITE_PATH . '/.mvcx/' . 'view.class.php';

$registry = new Registry();
$app = new App;
$registry->set('app', $app);

include SITE_PATH . '/.mvcx/' . 'config.php';
include SITE_PATH . '/.mvcx/' . 'router.class.php';
include SITE_PATH . '/.mvcx/' . 'load.class.php';
