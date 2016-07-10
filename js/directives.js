	myApp.directive('startGame', function(){
		return {
			templateUrl: 'protected/views/frontend/templates/start.html',
			replace: true,
			controller: function($scope){

			}
		}
	})
	.directive('footerGame', function(){
		return {
			templateUrl: 'protected/views/frontend/templates/footer.html',
			replace: true,
		}
	})	
	.directive('game', function(){
		return {
			templateUrl: 'protected/views/frontend/templates/game.html',
			replace: true,
			controller: function($scope, $timeout){
									
				// show categories panel
				$scope.category_show = false;
				// do not permit category click when image should be changed
				var categoryHide = false; 
				$scope.pictureMistakes = 0;
				
				// back to start
				$scope.close = function(){
					$scope.game = false;
					$scope.start = true;
					$scope.fetchPictures();	
					$scope.category_show = false;
					categoryHide = false;		
				}
				
				// show categories panel
				$scope.imageClick = function(){
					if($scope.pictureClickPermitted) {
						$scope.pictureClickPermitted = false;
						angular.element('figure').addClass('move_picture');
						$scope.category_show = true;
						categoryHide = false;
						$scope.pictureMistakes = 0;
					}	
				}		
							
				// choose category
				$scope.caption = false;
				$scope.categoryChose = function($event, chosen){
					
					if(categoryHide) return false;
					var answer;
					$scope.current = $scope.pictures[$scope.showLast]; // correct category
					
					if($scope.current.category_id == chosen) {  // correct anwser
						answer = 'correct';
						nextPicture();
					} else {							// error answer
						answer = 'error';
						$scope.pictureMistakes++;
						if($scope.pictureMistakes == $scope.mistakesAllowed) {
							$scope.gameAnswers--;
							nextPicture();
						}	
					}
					angular.element($event.currentTarget).addClass(answer);
				}
				var nextPicture = function(){
					categoryHide = true;
					$timeout(function(){ 
						// hide categories panel
						$scope.category_show = false; 
						// remove warnings
						angular.element('.categories div').removeClass('correct'); 
						angular.element('.categories div').removeClass('error');
						// show picture's title
						$scope.caption = $scope.current.id; 
						$timeout(function(){ 		
							if(	$scope.showLast > 0) {    // show next picture 
								$scope.showLast--;
								$timeout(function(){ $scope.pictureClickPermitted = true; },1000);								
							} else { 		// game over
								angular.element('figure').removeClass('move_picture'); 
								$scope.result = true;	
								$scope.game = false;
								$scope.showCircle = true;
								// fetch pictures or the next game
								$scope.fetchPictures();									
								$scope.$broadcast('result');	
							}
						},3000);
					},300);	
				}
			
			}
		}
	})
	.directive('result', function(){
		return {
			templateUrl: 'protected/views/frontend/templates/result.html',
			replace: true,
			controller: function($scope, $timeout, $window, getData, diagram){
				// create circle diagram function 	
				$scope.showCircle = true;
				$scope.answers;
				
				$scope.$on('result', function() {
										
					// get all answers array
					$scope.answers = getData.allAnswers($scope.gameAnswers);
					$scope.gameAnswers = $scope.answers[$scope.answers.length-1];	
				
					// come up with answers title
					$scope.gameAnswersTitle = getData.answersText($scope.gameAnswers, $scope.picturesLength);		

					// create circle diagram
					diagram.circle($scope.gameAnswers, $scope.picturesLength, $scope.box.height, $scope.showCircle);	
					$scope.$on('resize', function() { 
						diagram.circle($scope.gameAnswers, $scope.picturesLength, $scope.box.height, $scope.showCircle);
					});
					
					// hide diagram, show chart
					if($scope.answers.length > 1 && $window.innerWidth >= 400) {
						$timeout(function(){
							$scope.showCircle = false;
						}, 5000);				
					}	
				});	

			
			}
		}
	})
	.directive('chart', function(){
		return {
			replace: true,
			transclude: true,
			templateUrl: 'protected/views/frontend/templates/chart.html',
			controller: function($scope, $element, $attrs){ 
				
				$scope.height = $scope.box.height - 130;
				$scope.width = $scope.box.height;
				  
				var	borderWidth = 40;
					
				// chart limit	
				$scope.left = borderWidth;
				$scope.top = $scope.height;
				$scope.right = $scope.width;
				$scope.bottom = $scope.height-borderWidth;
				
				// y points
				this.getY = function(elm){
				
					// point width
					var adjustment = elm.r + elm.strokeWidth; 
					// vertical distance between points 
					var heightSpacer = ($scope.height - borderWidth - adjustment) / $scope.picturesLength;
					
					// y ticks
					$scope.ticks = [];
					for(var i = 0; i <= $scope.picturesLength; i++) {
						$scope.ticks.push({
							text: i,
							value: i * heightSpacer + adjustment
						})
					}
					
					// point coordinates
					var adjustment = elm.r + elm.strokeWidth;
					return ($scope.height-borderWidth) - heightSpacer * elm.y;
					
				}
				
				// x points
				var count = 0;
				this.getX = function(elm){
					if(elm.num == undefined) {
						elm.num = count++;
						$scope.$broadcast('new-width');
					}
					var adjustment = elm.r+elm.strokeWidth;
					return borderWidth + ( ($scope.width-adjustment-borderWidth) / (count-1)) * elm.num;
				}
				
				// array of points
				$scope.points = [];
				this.addPoint = function(elm){
					$scope.points.push(elm);
				}			
			}
		}	
	})
	.directive('datapoint', function(){
		return {
			restrict: 'E',
			replace: true,
			require: '^chart',
			template: '<circle ng-attr-cx="{{cx}}" ng-attr-cy="{{cy}}" ng-attr-r="{{r}}" ng-attr-stroke-width="{{strokeWidth}}" ng-attr-fill="{{fill}}" ng-attr-stroke="{{stroke}}" />',
			scope: {
				length:'@',
				x:'@',
				y:'@'
			},
			link: function(scope, element, attrs, ctrl){
				
				if(scope.length < 2) return false;
				
				scope.r = 4;
				scope.strokeWidth = 2;
				scope.fill = '#e2e7ef';
				scope.stroke = '#babec5';
				
				scope.x = parseInt(scope.x, 10);
				scope.y = parseInt(scope.y, 10);
				
				// coordinates of х
				scope.cx = ctrl.getX(scope);
				scope.$on('new-width', function() { scope.cx = ctrl.getX(scope) } );
				
				// coordinates of у
				scope.cy = ctrl.getY(scope);
				
				// adds a point into $scope.points array for displaying on the x axis
				ctrl.addPoint(scope);
				
			}
		}
	});	
	