[block:header]
[block:nav]
[widget:breadcrumb]

<div class="container masterx-fields">
	<div class="row">
        <div class="col-sm-12 col-md-12 col-xs-12">
            <h1>Success!</h1>
            <hr>		
            <div class="row row-step">  
                <div class="col-sm-12 col-md-12 col-xs-12">
                <h3>This is your new MVC: <a href="/<?php echo $table; ?>"><b><?php echo $table; ?></b></a></h3>
                </div>
            </div>
         </div>
    </div>
</div>
<script>
var table = '';
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$('input[name=table]').click(function() {
	table = $(this).val();
	$( ".choose-fields" ).load( "/masterx/mvc_generator_tablefields/"+table );
	$('#mvcname').val(table);
})
$('#btngenerate').click(function() {
	$(this).attr('disabled','disabled').val('Please wait...');
	$.ajax({
		type: "POST",
		url: "/masterx/mvc_generator_generate",
		data: $('.masterx-fields input,.masterx-fields select').serialize(),
		success: function(data) {
			if (data) {
				alert(data);
			} else {
				document.location = '/masterx/mvc_generator_success/'+table;	
			}
		},
		error: function(){
			  alert('Error');
		}
	});
});
</script>
[block:footer]
[block:debug]