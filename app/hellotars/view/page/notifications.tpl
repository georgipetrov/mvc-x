[block:header]
[block:nav]

<!-- Begin page content -->
<div class="container">
  <div class="page-header">
    <h1>Contact</h1>
  </div>
  <p class="lead">Test the MVC-X native flash messages executed as 
  <code>$this->session->flashNotification($message,$status,$redirect);</code></p>
  <p>
  	<form method="post">
     	<input type="hidden" name="msg" value="You have successfully clicked the button" />
	   	<input type="hidden" name="status" value="success" />
    	<input type="submit" class="btn btn-success" value="Test Success Flash Message" />
    </form>
  </p>
  <p>
  	<form method="post">
     	<input type="hidden" name="msg" value="You have somehow clicked the button wrong..." />
    	<input type="hidden" name="status" value="warning" />
    	<input type="submit" class="btn btn-warning" value="Test Warning Flash Message" />
    </form>
  </p>
  <p>
  	<form method="post">
     	<input type="hidden" name="msg" value="You have not actually clicked a button, did you?" />
    	<input type="hidden" name="status" value="danger" />
    	<input type="submit" class="btn btn-danger" value="Test Fail Flash Message" />
    </form>
  </p>
  <p>Checkout the <a href="http://mvc-x.com" target="_blank">MVC-X official website</a> where you can write and add your own extensions.</p>
</div>

[block:footer]
[block:debug]