<script src="asset/js/jquery.min.js"></script>
<script src="asset/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="asset/js/ie10-viewport-bug-workaround.js"></script>
<?php if (!empty($_TEMPLATE) && $_TEMPLATE !== false) { ?>
<?php if(file_exists("$_APPPATH/view/template/$_TEMPLATE/asset/js/script.$_CONTROLLER.$_ACTION.js")) { ?>
<script src="asset/js/script.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.js" type="text/javascript"></script>    
<?php } ?>
<?php } else { ?>
<?php if(file_exists("$_APPPATH/view/asset/js/script.$_CONTROLLER.$_ACTION.js")) { ?>
<script src="asset/js/script.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.js" type="text/javascript"></script>    
<?php } ?>
<?php } ?>
