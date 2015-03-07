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
<link href="asset/css/dropzone.css" type="text/css" rel="stylesheet" />
<div action="?hafur" class="dropzone" id="my-awesome-dropzone" enctype="multipart/form-data">
</div>

<script src="asset/js/dropzone.js" type="text/javascript"></script>
<script type="text/javascript">
Dropzone.options.myAwesomeDropzone = {
  dictDefaultMessage: '<?php echoine($defaultMessage,'Click or drop here to upload files'); ?>',
  maxFiles: <?php echoine($maxFiles,1); ?>,
  params: {
	'uploadDir':'<?php echoine($uploadDir); ?>',
	'useTimestamp':'<?php echoine($useTimestamp); ?>',
	'allowedFormats':'<?php echoine($allowedFormats,"png,jpg,jpeg,gif,zip,pdf"); ?>'  
  },
  init: function() {
    this.on("success", function(e,result) { 
		var field = '<?php echoine($field); ?>';
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