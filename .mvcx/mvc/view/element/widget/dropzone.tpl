<?php 
 /*
 	ACCEPTED PARAMS:
    uploadDir - the directory you like to get uploaded in asset/uploads/
    useTimestamp - if you want to get the upload prefixed with a timestamp
    maxFiles - the max number of files you allow to be uploaded
    field - the ID or name of the HTML field you like to get updated when the file(s) are uploaded
    defaultMessage - the upload default message
 */
?>
<?php 
	$dropzone_id = rand(100,99999);
?>
<link href="asset/css/dropzone.css" type="text/css" rel="stylesheet" />
<div action="?hafur" class="dropzone" id="my-awesome-dropzone-<?php echo $dropzone_id;?>" enctype="multipart/form-data">
</div>
<script>window.Dropzone || document.write('<script src="asset/js/dropzone.js"><\/script>')</script>
<script type="text/javascript">
Dropzone.options.myAwesomeDropzone<?php echo $dropzone_id;?> = {
  dictDefaultMessage: '<?php echoine($defaultMessage,'Click or drop here to upload files'); ?>',
  maxFiles: <?php echoine($maxFiles,1); ?>,
  params: {
	'uploadDir':'<?php echoine($uploadDir); ?>',
	'useTimestamp':'<?php echoine($useTimestamp); ?>',
	'allowedFormats':'<?php echoine($allowedFormats,"png,jpg,jpeg,gif,zip,pdf"); ?>'  
  },
  init: function() {
    this.on("success", function(e,result) { 
		var field = '<?php echoine($field); ?>'.trim();
		if (field && field != '') {
			var val = $('input[name="'+field+'"],input[id="'+field+'"]').val();
			if (val && val != '' && result && result != '') {
				val = val + ',' + result;
			} else {
				val = result;	
			}
			$('input[name="'+field+'"],input[id="'+field+'"]').val(val);
		}
		
	 });
  }
};
</script>