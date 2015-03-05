<?php if (isset($this->session->data['flash'])): ?>
<div class="flash btn-<?php echo $this->session->data['flash']['status']; ?>" onclick="$(this).fadeOut();">
    <div class="container">
		    <h3><?php echo $this->session->data['flash']['msg']; unset($_SESSION['flash']); ?></h3>
    </div>
</div>

<?php endif;  ?>
