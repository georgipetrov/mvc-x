<style>
.fields-table {
	margin-bottom:30px;	
}
.fields-table .input-sm {
	height:26px;
	padding-top: 3px;
}
.fields-table td,.fields-table th {
	padding-left:10px;
}
</style>
<?php foreach ($views as $view): ?>
    <h3><input type="checkbox" value="1" name="field[<?php echo $view; ?>][_status_]" checked="checked" />&nbsp; <?php echo ucfirst($view); ?> &nbsp;<small><?php echo $view; ?>.tpl</small></h3>
    <hr>
    <label for="title">Title of the view (as shown only in the HTML title)</label>
    <input type="text" class="form-control input-lg field-title" name="field[<?php echo $view; ?>][_title_]" value="<?php echo ucfirst($view); ?>" />     
    <hr>
    <table class="fields-table">
        <tr>
            <th>Field</th>
            <th>Field form name</th>
            <th>Input box</th>
            <th>Attributes</th>
            <th>Validation</th>
        </tr>
    <?php foreach( $tablefields as $field) { ?>
        <?php 
        $checked = ' checked="checked"';
        $desired_name = ucwords(str_replace('_',' ',$field['Field']));
        
        if (in_array($field['Field'],array('created','modified','ua','ip','id'))) {
                $checked = '';
                $desired_name = '';
            }
         ?>
        <tr>
            <td>
            <div class="checkbox" style="margin:6px 0">
              <label><input type="checkbox" name="field[<?php echo $view; ?>][<?php echo $field['Field']; ?>][field]" value="<?php echo $field['Field']; ?>" data-value="<?php echo $desired_name; ?>" <?php echo $checked; ?> />&nbsp;&nbsp;<b><?php echo $field['Field']; ?></b> <?php echo $field['Type']; ?></label>
            </div>
            </td>
            <td>
                <input type="text" class="form-control input-sm field-name" data-field="<?php echo $field['Field']; ?>" data-view="<?php echo $view; ?>" name="field[<?php echo $view; ?>][<?php echo $field['Field']; ?>][name]" value="<?php echo $desired_name; ?>" />       
            </td>
            <td>
              <select class="form-control input-sm" name="field[<?php echo $view; ?>][<?php echo $field['Field']; ?>][inputtype]">
              <optgroup label="Input Type">
                <option value="text" selected>Text</option>
                <option value="password">Password</option>
                <option value="search">Search</option>
                <option value="checkbox">Checkbox</option>
                <option value="url">Url</option>
                <option value="tel">Tel</option>
                <option value="radio">Radio</option>
                <option value="date">Date</option>
                <option value="datetime">Datetime</option>
                <option value="email">Email</option>
                <option value="file">File</option>
                <option value="number">Number</option>
                <option value="color">color</option>
                <option value="hidden">Hidden</option>
              </optgroup>
              <optgroup label="Textarea">
                <option value="textarea">Textarea</option>
              </optgroup>
              <optgroup label="Select">
                <option value="select">Select</option>
              </optgroup>
              </select>
            </td>
            <td>
              <input type="text" class="form-control input-sm field-attributes" name="field[<?php echo $view; ?>][<?php echo $field['Field']; ?>][attributes]" value='class="form-control input-lg"' />       
            </td>
            <td>
              <select class="form-control input-sm" name="field[<?php echo $view; ?>][<?php echo $field['Field']; ?>][validation]">
                <option value="" selected>Not set</option>
                <option value="required">Required</option>
                <option value="email">Email</option>
                <option value="number">Number</option>
              </select>
            </td>
        </tr>
    <?php } ?>
    </table>
<?php endforeach; ?>
<script>
$('.field-name').change(function() {
	if ($(this).val().length > 0) {
		$(this).parents('tr').find('input[type=checkbox]').attr('checked','checked');
	} else {
		$(this).parents('tr').find('input[type=checkbox]').removeAttr('checked');

	}
});

//Real-time update the other views with the value of the same field
$('.field-name').keyup(function() {
	var views = ['index','add','view','edit'];
	var dataView = $(this).data('view');
	for (var key in views) {
		if (views[key] == dataView) {
			views.splice(key, 1);
		}
	}
	for (var key in views) {
		$('input[name="field['+views[key]+']['+$(this).data('field')+'][name]"]').val($(this).val());
	}
});
$('.fields-table input[type=checkbox]').click(function() {
	if ($(this).is(':checked')) {
		$(this).parents('tr').find('.field-name').val($(this).data('value'));
	} else {
		$(this).parents('tr').find('.field-name').val('');
	}
});
</script>