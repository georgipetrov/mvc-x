<?php if (!empty($_TEMPLATE) && $_TEMPLATE !== false) { ?>
<?php if(file_exists("$_APPPATH/view/template/$_TEMPLATE/asset/js/script.$_CONTROLLER.$_ACTION.js")) { ?>
<script src="asset/js/script.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.js" type="text/javascript"></script>    
<?php } ?>
<?php } else { ?>
<?php if(file_exists("$_APPPATH/view/asset/js/script.$_CONTROLLER.$_ACTION.js")) { ?>
<script src="asset/js/script.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.js" type="text/javascript"></script>    
<?php } ?>
<?php } ?>
