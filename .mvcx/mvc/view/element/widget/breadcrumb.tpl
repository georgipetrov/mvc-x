<?php if (!($_CONTROLLER == 'page' && $_ACTION == 'index')) { ?>
    <div class="breadcrumb-holder">
        <ol class="breadcrumb container">
          <li><a href="<?php echo $_BASEHREF ?>">Home</a></li>
          
          <?php if ($_ACTION != 'index') { ?>
          <li><a href="<?php echo $_BASEHREF.$_CONTROLLER; ?>"><?php echo ucfirst($_CONTROLLER); ?></a></li>
          <?php
          if (stripos($_ACTION,'-') !== false) {
          		$_ACTION = str_replace('-',' ',$_ACTION);
            }
           ?>
          <?php $title = (!empty($_ACTION)) ? $_ACTION : 'Page';  ?>
          <?php $title = (!empty($persistence['name'])) ? $persistence['name'] : $title ;  ?>
          <?php $title = (!empty($persistence['title'])) ? $persistence['title'] : $title ;  ?>
          <li class="active"><?php echo ucfirst($title); ?></li>
          <?php } else {  ?>
          <li class="active"><?php echo ucfirst($_CONTROLLER); ?></li>
          <?php } ?>
        </ol>
    </div>
<?php } ?>