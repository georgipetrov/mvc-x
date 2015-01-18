<?php

include SITE_PATH . '/.mvcx/' . 'helper.php';

include SITE_PATH . '/.mvcx/' . 'db.class.php';

include SITE_PATH . '/.mvcx/' . 'lib.class.php';


/*** include the controller class ***/
include SITE_PATH . '/.mvcx/' . 'controller.class.php';

/*** include the controller class ***/
include SITE_PATH . '/.mvcx/' . 'xcontroller.class.php';

/*** include the model class ***/
include SITE_PATH . '/.mvcx/' . 'model.class.php';

/*** include the registry class ***/
include SITE_PATH . '/.mvcx/' . 'app.class.php';

/*** a new registry object ***/
$app = new App;

/*** include the config ***/
include SITE_PATH . '/app/' . 'config.php';

/*** include the router class ***/
//include SITE_PATH . '/.mvcx/' . 'app.class.php';

//$app = new app;

/*** include the router class ***/
include SITE_PATH . '/.mvcx/' . 'router.class.php';

/*** include the template class ***/
include SITE_PATH . '/.mvcx/' . 'load.class.php';

/*** create the database registry object ***/
// $registry->db = db::getInstance();