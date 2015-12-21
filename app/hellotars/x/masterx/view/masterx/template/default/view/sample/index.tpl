[block:header]
[block:nav]

<div class="container">
	<div class="row">
        <div class="col-sm-12 col-md-12 col-xs-12">
    	<h1>{ViewTitle} <a href="/{base}/add" class="btn btn-success btn-lg pull-right">&nbsp; <span class="glyphicon glyphicon-plus"></span>&nbsp; Add New</a></h1>
            <table class="table">
                <thead><tr>
{table-heading.tpl}                
				<th></th>
				</tr></thead>            
                <tbody>
                <?php foreach($persistence as $p): ?>
                <tr>
{rows}                
				<td style="text-align:right">
                	<a href="/{base}/edit/<?php echo $p['id']; ?>" class="btn btn-default btn-sm">Edit</a>
                	<a href="/{base}/view/<?php echo $p['id']; ?>" class="btn btn-default btn-sm">View</a>
                </td>
				</tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
[block:footer]
[block:debug]
