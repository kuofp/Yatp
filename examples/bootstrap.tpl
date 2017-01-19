<!-- @html -->
	<!-- @type1 -->
	<html>
		<head>
			<title>{title}</title>
			<meta charset="utf8">
			<!-- @head --><!-- @head -->
		</head>
		<body>
			<div class="container-fluid">
				<div class="row">
					<!-- @banner --><!-- @banner -->
					<!-- @nav --><!-- @nav -->
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-sm-3">
						<!-- @left --><!-- @left -->
					</div>
					<div class="col-sm-9">
						<!-- @right --><!-- @right -->
						
						<div class="col-sm-offset-4">
							<!-- @pagination --><!-- @pagination -->
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>
	<!-- @type1 -->
<!-- @html -->


<!-- @jquery_bootstrap -->
	<!-- @default -->
		<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<!-- @default -->
<!-- @jquery_bootstrap -->


<!-- @dropdown -->
	<!-- @type1 -->
	<div class="dropdown">
		<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			Dropdown<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			<li><a href="#">Action</a></li>
			<li><a href="#">Another action</a></li>
			<li><a href="#">Something else here</a></li>
			<li role="separator" class="divider"></li>
			<li><a href="#">Separated link</a></li>
		</ul>
	</div>
	<!-- @type1 -->
<!-- @dropdown -->


<!-- @nav -->
	<!-- @type1 -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">{brand}</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<!-- @main -->
				<ul class="nav navbar-nav">
					<!-- @left -->
					<!-- @link -->
					<li class="{class}"><a href="{href}">{text}</a></li>
					<!-- @link -->
					<!-- @dropdown -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{dropdown-text} <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<!-- @dropdown-link -->
							<li><a href="{href}">{text}</a></li>
							<!-- @dropdown-link -->
						</ul>
					</li>
					<!-- @dropdown -->
					<!-- @left -->
				</ul>
				<!-- @search -->
				<form class="navbar-form navbar-left" role="search">
					<div class="form-group"><input type="text" class="form-control" placeholder="Search"></div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
				<!-- @search -->
				
				<ul class="nav navbar-nav navbar-right">
					<!-- @right -->
					<!-- the same as left part -->
					<!-- @right -->
				</ul>
				<!-- @main -->
			</div>
		</div>
	</nav>
	<!-- @type1 -->
	<!-- @type2 -->
	<ul class="nav nav-pills nav-stacked">
		<!-- @li -->
		<li role="presentation" class="{class}"><a href="{link}">{text}</a></li>
		<!-- @li -->
	</ul>
	<!-- @type2 -->
<!-- @nav -->


<!-- @banner -->
	<!-- @type1 -->
	<style>
	.center-block {
		display: block;
		margin-left: auto;
		margin-right: auto;
	}
	</style>
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<!-- @li -->
			<li data-target="#carousel-example-generic" data-slide-to="{next}" class="{class}"></li>
			<!-- @li -->
		</ol>
		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			<!-- @item -->
			<div class="item {class}">
				<img class="center-block" src="{src}" alt="...">
				<div class="carousel-caption"></div>
			</div>
			<!-- @item -->
		</div>
		<!-- Controls -->
		<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
	<!-- @type1 -->
<!-- @banner -->

<!-- @thumbnail -->
<div class="col-sm-6 col-md-4">
	<div class="thumbnail">
		<img src="{src}" alt="...">
		<div class="caption">
			<h3>{title}</h3>
			<p>{content}</p>
			<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
		</div>
	</div>
</div>
<!-- @thumbnail -->

<!-- @pagination -->
<nav>
	<ul class="pagination">
		<li><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
		<li><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
	</ul>
</nav>
<!-- @pagination -->