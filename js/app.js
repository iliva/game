var myApp = angular.module('MyApp', ['ngCookies', 'ngAnimate']);
	
	myApp.controller('MainController', function($scope, $window, $timeout, changeData, getData){
	
		// preload icons
		var preloads = ['correct', 'error', 'magic'];
		preloads.forEach(function(item, i){
			var preLoad = new Image(); preLoad.src = "/img/"+item+".png";
		});
		
		// quantity of pictures per one game	
		$scope.picturesLength = 10;
		
		$timeout(function(){ angular.element('.magic').removeClass('active'); },2000);	
		
		// box size
		$scope.InitBox = { width: 1000, height: 660 }

		$scope.box = Object.create($scope.InitBox);
		changeData.boxSize($scope.box, $scope.InitBox);
		angular.element($window).bind('resize', function(){
			changeData.boxSize($scope.box, $scope.InitBox);
			$scope.$apply();
		});	
		
	
		// fetch pictures
		$scope.fetchPictures = function(){		
			getData.pictures(function(data){
				$scope.pictures = data;	
				changeData.shuffle($scope.pictures);
				$scope.pictures = $scope.pictures.slice(0, $scope.picturesLength);
				$scope.showLast = $scope.picturesLength-1;
				// preload
				for (var i = 0; i < $scope.picturesLength; i++) {
					var preLoad = new Image(); 
					preLoad.src = "/img/pictures/big/"+$scope.pictures[i].image;  
				} 
			});		
		}
			
		// fetch categories
		$scope.fetchCategories = function(){	
			getData.categories(function(data){
				$scope.categories = data;	
				// preload
				for (var i = 0; i < $scope.categories.length; i++) {
					var preLoad = new Image(); 
					preLoad.src = "/img/categories/small/"+$scope.categories[i].image;
					
				}				
			});		
		}
		
		$scope.fetchPictures();
		$scope.fetchCategories();
		
		// game type
		$scope.start = true;
		$scope.result = false;
		$scope.game = false;
		$scope.mistakesAllowed = 3;
		$scope.gameAnswers = $scope.picturesLength;
		
		
		// test result history (url add: #test2,3,5)
		if($window.location.hash.substring(0,5) == '#test') {
			$scope.result = true;   
			$scope.start = false; 	
			$timeout(function(){ $scope.$broadcast('result'); },300);	
		}
				
		$scope.gameType = function(n){
			$scope.mistakesAllowed = n;
			$scope.gameAnswers = $scope.picturesLength;
			$scope.start = false;
			$scope.game = true;
			$scope.result = false;
			$scope.pictureClickPermitted = true;
		}		
		
	});
	
