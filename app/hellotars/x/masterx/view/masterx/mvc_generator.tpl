[block:header]
[block:nav]
[widget:breadcrumb]
<style>
.row-step {
	margin-top:20px;	
}
.row-step h3 {
	margin-top:0;	
}
</style>

<div class="container masterx-fields">
	<div class="row">
        <div class="col-sm-12 col-md-12 col-xs-12">
            <h1>MVC Files Generator<br><small>Generate Model-View-Controllers based from table schema</small></h1>
            <hr>		
            <div class="row row-step">  
                <div class="col-sm-3 col-md-3 col-xs-3">
                    <h3>Step 1</h3>
                    Choose MySQL table
                </div>
            	<div class="col-sm-9 col-md-8 col-xs-9 choose-table">      
                    <?php foreach( $dbtables as $table) { ?>
                    <div class="radio">
                      <label><input type="radio" value="<?php echo current( $table); ?>" name="table" /><span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;<?php echo current( $table); ?></label>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <hr>
            <div class="row row-step">  
                <div class="col-sm-3 col-md-3 col-xs-3">
                    <h3>Step 2</h3>
                    Choose Views and Fields
                </div>
            	<div class="col-sm-9 col-md-9 col-xs-9 choose-fields">      
                </div>
            </div>
             <hr>
           <div class="row row-step">
                <div class="col-sm-3 col-md-3 col-xs-3">
                    <h3>Step 3</h3>
                    Choose name of the MVC
                </div>
            	<div class="col-sm-9 col-md-9 col-xs-9 choose-name">
                    <input type="text" name="mvcname" id="mvcname" class="form-control input-lg" value="MyMVC" />  
                </div>
            </div>
           <div class="row row-step">
                <div class="col-sm-3 col-md-3 col-xs-3">
                </div>
            	<div class="col-sm-9 col-md-9 col-xs-9 generate">
                    <input type="button" id="btngenerate" class="btn btn-primary btn-lg" value="Generate MVC" />  
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