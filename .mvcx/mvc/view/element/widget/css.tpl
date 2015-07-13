<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style->getHref();?>" rel="stylesheet" type="<?php echo $style->getAttrib('type'); ?>">
<?php } ?>
<?php if (!empty($position) && $position == 'header') {?>
	<?php if (!empty($_TEMPLATE)) { ?>
		<?php if(file_exists("$_APPPATH/view/template/$_TEMPLATE/asset/css/stylesheet.$_CONTROLLER.$_ACTION.css")) { ?>
		<link href="asset/css/stylesheet.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.css" rel="stylesheet">
		<?php } ?>
	<?php } else { ?>
		<?php if(file_exists("$_APPPATH/view/asset/css/stylesheet.$_CONTROLLER.$_ACTION.css")) { ?>
		<link href="asset/css/stylesheet.<?php echo $_CONTROLLER; ?>.<?php echo $_ACTION; ?>.css" rel="stylesheet">
		<?php } ?>
	<?php } ?>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<?php } ?>
