[block:header]
[block:nav]

<style>
h1 {
	margin-top:60px;
	margin-bottom:50px;	
}
h1 small {
    margin-top: 25px;
    display: block;
    font-weight: 300;	
}
.well {
    text-align: center;
    border: none;
    box-shadow: 0 1px 1px rgba(0,0,0,.35);
    background-color: #fafafa;
	opacity:0.8;
	border-top:5px solid #E91E63;
}
.well .icon {
	font-size:62px;
    color: #E91E63;
	-webkit-font-smoothing: antialiased;
}

.well h4 {
	font-weight:700;
	color:#333;
	text-transform:uppercase;
	letter-spacing:1px;
	font-size:15px;
}
.well a:hover{
	text-decoration:none;

}
.well:hover {
	opacity:1;
}
@-webkit-keyframes rotating /* Safari and Chrome */ {
  from {
    -ms-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  to {
    -ms-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -webkit-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes rotating {
  from {
    -ms-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  to {
    -ms-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -webkit-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
.rotating {
  -webkit-animation: rotating 2s linear infinite;
  -moz-animation: rotating 2s linear infinite;
  -ms-animation: rotating 2s linear infinite;
  -o-animation: rotating 2s linear infinite;
  animation: rotating 2s linear infinite;
}
</style>

<div class="container masterx-apps">
	<div class="row">
        <div class="col-sm-12 col-md-12 col-xs-12">
            <h1>Master-X tools collection<small>Create and manage mvcx apps efficiently with the following tools</small></h1>
            <div class="row row-step">  
                <div class="col-sm-3 col-md-3 col-xs-3">
                	<div class="well">
                    <a href="/masterx/mvc_generator">
                        <div class="icon"><span class="glyphicon glyphicon-cog"></span></div>
                        <h4>MVC Files Generator</h4>
                    </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
$('.well a').hover(function(e) {
	if (!$(this).find('.icon span').hasClass('rotating')) {
		$(this).find('.icon span').addClass('rotating');
	}
},
function(e) {
	$(this).find('.icon span').removeClass('rotating');
	
});
</script>

[block:footer]
[block:debug]
