<?php 
    $type = returnine($type,'text');
    $name = returnine($name,'no_name_set');
	$title = returnine($title,$name);
    $placeholder = returnine($placeholder);
    $help = returnine($help);
    $attr = returnine($attr);
    $value = returnine($value);
    $checked = returnine($checked);
    $selected = returnine($selected);
	$id = $name.'_'.time();
    if (!in_array($type,array("file","radio","checkbox"))) {
        if (strpos($attr,'class') !== -1) {
            $attr .= ' class="form-control"';
        }
    }
    if ($type == 'checkbox' && !empty($value) && $value == 1) {
    	$checked = 'checked';
    }
?>

<div class="form-group">
<label for="<?php echo $id; ?>"><?php echo $title; ?></label>
<?php if ($type == 'textarea') { ?>
    <textarea <?php echo $attr; ?> name="<?php echo $name; ?>" id="<?php echo $id; ?>" placeholder="<?php echoine($placeholder); ?>"><?php echo $value; ?></textarea>
<?php } else { ?>
    <input type="<?php echoine($type); ?>" <?php echo $attr; ?> value="<?php echo $value; ?>" name="<?php echo $name; ?>" id="<?php echo $id; ?>" placeholder="<?php echoine($placeholder); ?>" <?php echo $checked; echo $selected; ?>>
<?php } ?>
    <?php if (!empty($help)): ?><p class="help-block"><?php echo $help; ?></p><?php endif; ?>
</div>

