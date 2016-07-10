
	myApp.factory('changeData', function($window){	
		return {
			shuffle: function(array){
				var m = array.length, t, i;
				// While there remain elements to shuffle
				while (m) {
					// Pick a remaining element…
					i = Math.floor(Math.random() * m--);
					// And swap it with the current element.
					t = array[m];
					array[m] = array[i];
					array[i] = t;
				}
			  return array;			
			},
			boxSize: function(box, initBox){
			
				box.width = initBox.width;
				box.height = initBox.height;
				
				var windowHeight = $window.innerHeight-1;  
				var windowWidth = $window.innerWidth-1;
				if((initBox.width / windowWidth) < (initBox.height / windowHeight)) {
					// change height
					if(initBox.height > windowHeight) {
						box.height = windowHeight;
						box.width = initBox.width * windowHeight / initBox.height;
					}					
				} else {
					// change width
					if(initBox.width > windowWidth) {
						box.width = windowWidth;
						box.height = initBox.height * windowWidth / initBox.width;
					}					
				}
				return box;
			},		
		}
	})
	.factory('diagram', function($window){	
		return {
			circle: function(gameAnswers, picturesLength, height, showCircle){
				var circleObj = false;
				function createCircles() {
					if($window.innerWidth < 400) return false;
					circleObj = Circles.create({
						id:         'result-circle',
						value:      gameAnswers,
						maxValue:   picturesLength,
						radius:     (height - 150) / 2,
						width:      50,
						duration: 	100,
						colors:     ['#babec5', '#e17314']
					});
				}
				// create circle diagram
				createCircles();
				// change diagram size
				$window.onresize = function(e) {
					if(showCircle == false) return false;
					var new_radius = (height - 150) / 2;
					
					if(circleObj && $window.innerWidth >= 400) {
						circleObj.updateRadius(new_radius);
					} else { 
						createCircles();
					}	
				};				
			}
		}
	})
	.factory('getData', function($http, $cookies, $window){	
		var hash = Math.floor((Math.random()*9999)+1000);
		return {
			// fetch pictures from xml
			pictures: function(result){
				$http.get('/json_'+hash+'/pictures.json')
					.success(function(data, status, headers, config){
						result(data);
					})
					.error(function(data, status, headers, config){
						$log.warn(data, status, headers, config);
					});	
			},
			// fetch categories from xml
			categories: function(result){
				$http.get('/json_'+hash+'/categories.json')
					.success(function(data, status, headers, config){
						result(data);
					})
					.error(function(data, status, headers, config){
						$log.warn(data, status, headers, config);
					});
			},	
			// correct answers titles in result page
			answersText: function(correct_answers, length){
				var decOfNum = function(number, titles) {
					cases = [2, 0, 1, 1, 1, 2];  
					return titles[ (number%100>4 && number%100<20) ? 2 : cases[(number%10<5) ? number%10:5] ]; 				
				}			
				var answers_text = ['правильна відповідь', 'правильні відповіді', 'правильних відповідей'];
				gameAnswersTitle = correct_answers + ' '+ decOfNum(correct_answers, answers_text) + ' з ' + length;
				if(correct_answers == length) 
					gameAnswersTitle = 'Ура! Помилок немає!';
				if(correct_answers == 0) 
					gameAnswersTitle = 'Жодної правильної відповіді';			
				return gameAnswersTitle;		
			},
			// fetch all answers from cookie
			allAnswers: function(gameAnswers){
			
				var cookie_value = $cookies.get('gameAnswers');
				var answers = cookie_value ? JSON.parse(cookie_value) : [];
				answers.push(gameAnswers);
				answers = answers.slice(-10);
				$cookies.put('gameAnswers', JSON.stringify(answers));
				
				// test result history (url add: #test2,3,5)
				if($window.location.hash.substring(0,5) == '#test') {
					var hash = $window.location.hash.substring(5).split(',');
					var answers = [];
					hash.forEach(function(item, i){	
						answers.push(+item); 
					});				
				}
				return answers;
			}			
		}	
	});	