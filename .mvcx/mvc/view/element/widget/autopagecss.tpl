<?php if (!empty($_TEMPLATE) && $_TEMPLATE !== false) { ?>
<?php if(file_exists("$_APPPATH/view/template/$_TEMPLATE/asset/css/stylesheet.$_CONTROLLER.$_ACTION.css")) { ?>
<link href="asset/css/stylesheet.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.css" rel="stylesheet">
<?php } ?>
<?php } else { ?>
<?php if(file_exists("$_APPPATH/view/asset/css/stylesheet.$_CONTROLLER.$_ACTION.css")) { ?>
<link href="asset/css/stylesheet.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.css" rel="stylesheet">
<?php } ?>
<?php } ?>