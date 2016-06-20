<?php if ($last_page != 1): ?>
<style>
.btn-pagination {
	margin-top:60px;
	text-align:left;	
}
.btn-page-info {
    color: #666;
    margin-right: 30px;
}
.btn-page-info h4  {
    line-height: 28px;
	font-weight:600;
}
.btn-toolbar .btn-group, .btn-toolbar .input-group {
	float:none;
}
</style>
<div class="btn-toolbar btn-pagination" role="toolbar">
<div class="btn-group btn-page-info" role="group" ><h4>Page <?php echo $current_page; ?> of <?php echo $last_page; ?></h4></div>
<div style="float:right">
  <?php if ($current_page != 1): ?> 
      <div class="btn-group" role="group" aria-label="Pagination">
      <a type="button" class="btn btn-default" href="<?php echo $pages[0]['link']; ?>" >First Page</a>
      </div>
  <?php endif; ?>
  
  <div class="btn-group" role="group" aria-label="Pagination">
  <?php foreach ($filteredpages as $page): ?>
    <a type="button" class="btn btn-default <?php echo ($current_page == $page['number']) ? 'active' : ''; ?>" href="<?php echo $page['link'] ?>" ><?php echo $page['number'] ?></a>
  <?php endforeach; ?>
  </div>
  <?php if ($current_page != $last_page): ?> 
  <div class="btn-group" role="group" aria-label="Pagination">
  <a type="button" class="btn btn-default" href="<?php echo $pages[count($pages)-1]['link']; ?>" >Last Page</a>
  </div>
  <?php endif; ?>
</div>
</div>
<?php endif; ?>