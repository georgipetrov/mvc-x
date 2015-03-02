<?php if (!($_CONTROLLER == 'page' && $_ACTION == 'index')) { ?>
    <div class="breadcrumb-holder">
        <ol class="breadcrumb container">
          <li><a href="<?php echo $_BASEHREF ?>">Home</a></li>
          
          <?php if ($_ACTION != 'index') { ?>
          <li><a href="<?php echo $_BASEHREF.$_CONTROLLER; ?>"><?php echo ucfirst($_CONTROLLER); ?></a></li>
          <li class="active"><?php echo ucfirst($_ACTION); ?></li>
          <?php } else {  ?>
          <li class="active"><?php echo ucfirst($_CONTROLLER); ?></li>
          <?php } ?>
        </ol>
    </div>
<?php } ?>