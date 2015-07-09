<?php foreach ($scripts as $script) { ?>
<script type="<?php echo $script->getAttrib('type'); ?>" src="<?php echo $script->getHref(); ?>"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<?php } ?>

<?php if (!empty($position) && $position == 'footer') { ?>
<script src="asset/js/ie10-viewport-bug-workaround.js"></script>
	<?php if (!empty($_TEMPLATE)) { ?>
		<?php if(file_exists("$_APPPATH/view/template/$_TEMPLATE/asset/js/script.$_CONTROLLER.$_ACTION.js")) { ?>
		<script src="asset/js/script.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.js" type="text/javascript"></script>    
		<?php } ?>
	<?php } else { ?>
		<?php if(file_exists("$_APPPATH/view/asset/js/script.$_CONTROLLER.$_ACTION.js")) { ?>
		<script src="asset/js/script.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.js" type="text/javascript"></script>    
		<?php } ?>
	<?php } ?>
<?php } ?>
