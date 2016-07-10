<!DOCTYPE html>
<html ng-app="MyApp">
	<head>
		<title>Game</title>
		<base href="/">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="css/style.css" rel="stylesheet" type="text/css">		
		<link href="img/favicon.ico" rel="SHORTCUT ICON" type="image/x-icon">
	</head>
	<body ng-cloak ng-controller="MainController">
		
		<div class="wrapper"  ng-style="{ 'width' : box.width, 'height' : box.height }"  >
			<start_game ng-show="start"></start_game>
			<game ng-show="game"></game>
			<result ng-show="result"></result>
		</div>
		

		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="https://code.angularjs.org/1.4.8/angular.js"></script>
		<script type="text/javascript" src="https://code.angularjs.org/1.4.8/angular-animate.js"></script>
		<script type="text/javascript" src="https://code.angularjs.org/1.4.8/angular-cookies.min.js"></script>
		<script type="text/javascript" src="js/circles.js"></script>
		<script type="text/javascript" src="js/app.js"></script>
		<script type="text/javascript" src="js/directives.js"></script>
		<script type="text/javascript" src="js/factories.js"></script>

	
	</body>
</html>