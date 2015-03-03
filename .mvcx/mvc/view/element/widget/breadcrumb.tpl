<?php if (!($_CONTROLLER == 'page' && $_ACTION == 'index')) { ?>
    <div class="breadcrumb-holder">
        <ol class="breadcrumb container">
          <li><a href="<?php echo $_BASEHREF ?>">Home</a></li>
          
          <?php if ($_ACTION != 'index') { ?>
          <li><a href="<?php echo $_BASEHREF.$_CONTROLLER; ?>"><?php echo ucfirst($_CONTROLLER); ?></a></li>
          <?php $title = (!empty($_ACTION)) ? $_ACTION : 'Page';  ?>
          <?php $title = (!empty($persistence['name'])) ? $persistence['name'] : '';  ?>
          <?php $title = (!empty($persistence['title'])) ? $persistence['title'] : '';  ?>
          <li class="active"><?php echo ucfirst($title); ?></li>
          <?php } else {  ?>
          <li class="active"><?php echo ucfirst($_CONTROLLER); ?></li>
          <?php } ?>
        </ol>
    </div>
<?php } ?>