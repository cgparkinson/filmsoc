var Filmsoc = {
	
	init: function() {
		if(location.pathname.indexOf('/admin') > -1) {
			Filmsoc.admin.init()
		} else if(document.body.id === 'home') {
			Filmsoc.initHome()
		}
		
		Filmsoc.initParallax()
		
	},
	
	initHome: function() {
		//Style Maps
		google.maps.visualRefresh = true;
		var latlng = new google.maps.LatLng(54.777147, -1.565535)
			map = new google.maps.Map(document.getElementById('map-canvas'), {
				center: latlng,
				zoom: 17,
				type: google.maps.MapTypeId.HYBRID,
				disableDefaultUI: true
			}),
			marker = new google.maps.Marker({
			    position: latlng,
			    map: map
			})
			
		google.maps.event.addDomListener(window, 'resize', function() {
		    map.setCenter(latlng)
		    map.setZoom(17)
		});
		
		map.set('styles', [
		  {
		    "featureType": "landscape",
		    "elementType": "geometry",
		    "stylers": [
		      { "color": "#eeeeee" }
		    ]
		  },{
		    "featureType": "poi.school",
		    "stylers": [
		      { "visibility": "on" },
		      { "hue": "#11ff00" }
		    ]
		  },{
		  }
		]);
		
		//Make the nav bar slow scroll
		$('nav a').on('click', function(e) {
			var elID = this.href.split('#')[1]
			if(!!elID) {
				e.stopPropagation()
				e.preventDefault()
				$('html, body').animate({
					scrollTop: $('#'+elID).offset().top
				}, 2000)
			}
		})
		
		//Apply the highlight class to these things when they are fully on screen
		var highlightables = document.querySelectorAll(['#priceinfo', '.enjoy', '#membershipinfo'].join(', '))
		$.each(highlightables, function(ind, el) {
			var $el = $(el),
				elH = el.clientHeight,
				scrollY = window.scrollY,
				h = window.innerHeight,
				activated = false
			
			function doCalc() {
				scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;;
				h = window.innerHeight || document.documentElement.clientHeight;
				elY = $el.offset().top;
				elH = el.offsetHeight;
				//Check if on screen and highlight if it is
				if(scrollY+h > elY+elH) {
					$el.addClass('highlight')
					activated = true
				}
			}
			
			$(window).on('scroll', function(e) {
				if(!activated) {
					doCalc()
				}
			})
			
			$(window).on('resize', function(e) {
				if(!activated) {
					doCalc()
				}
			})
			
		})
		
	},
	
	initParallax: function() {
		var parallaxwidgets = document.querySelectorAll('.parallax-inner');
		var paralaxWidgets = $.each(parallaxwidgets, function(ind, el) {
		
			var elY = $(el).offset().top,
				elH = el.clientHeight,
				elW = el.clientWidth,
				scrollY = window.scrollY,
				h = window.innerHeight,
				img = new Image(),
				imgW = 0,
				imgH = 0,
				ticking = false,
				transform = (function(y) {
					
					function getTransformProperty(element) {
						// Note that in some versions of IE9 it is critical that
						// msTransform appear in this list before MozTransform
						var properties = [
							'transform',
							'WebkitTransform',
							'msTransform',
							'MozTransform',
							'OTransform'
						];
						var p;
						while (p = properties.shift()) {
							if (typeof element.style[p] != 'undefined') {
								return p;
							}
							
						}
						return false;
					}
					
					var transformProperty = getTransformProperty(el);
					
					if(transformProperty) {
						return function(y) {
							img.style[transformProperty] = 'translate3d(0,'+y+'px,0)';
						}
					} else {
						return function(y) {
							img.style.top = y+'px';
						}
					}
					
				})();
				
			function requestTick() {
				if(!ticking) {
					requestAnimationFrame(parallax);
				}
				ticking = true;
			}
			
			img.onload = function() {
				imgW = img.width;
				imgH = img.height;
				el.appendChild(img);
				parallax();
			}
			img.className = 'parallax-img';
			img.src = el.getAttribute('data-img');
			
			function parallax() {
				ticking = false;
				var screenY = elY - scrollY,
					isOnScreen = (screenY < h && screenY > -1*elH);
				console.log(elY)
				var scrollableArea = h+elH;
				
				var displayedImageHeight = elW/imgW * imgH,
					slideRoom = Math.max(displayedImageHeight-elH, 0),
					slideRatio = Math.min(1,Math.max(0,(screenY+elH)/(scrollableArea)));
				transform(Math.round(-slideRatio*slideRoom));
			}
			
			var ticking = false;
			
			function doCalc() {
				scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;;
				h = window.innerHeight || document.documentElement.clientHeight;
				elY = $(el).offset().top;
				elH = el.offsetHeight;
				elW = el.offsetWidth;
				requestTick();
			}
			
			$(window).on('scroll', function(e) {
				doCalc();	
			});
			
			$(window).on('resize', function(e) {
				doCalc();	
			});
	
		});
	},
	
	admin: {
		init: function() {
			//Listen for clicks on exec page remove film button
			$('article input[name=remove_showing]').on('click', function(e) {
				e.stopPropagation()
				e.preventDefault()
				if(confirm('Are you sure you want to delete this showing of ' + this.getAttribute('data-film')+'?')) {
					$(this).closest('form').each(function(){
						console.log(this)
						HTMLFormElement.prototype.submit.call(this);
					})
				}
			});
			
			//Setup our convoluted JSONP method
			var jsonPTag = document.createElement('script')
			document.body.appendChild(jsonPTag)
			$('#new-listing-film').on('input', function(e) {
				document.body.removeChild(jsonPTag);
				jsonPTag = document.createElement('script')
				document.body.appendChild(jsonPTag)
				console.log(this.value)
				if(Filmsoc.utils.imdbRegex.test(this.value)) {
					jsonPTag.src = 'http://www.omdbapi.com/?i='+encodeURIComponent(this.value)+'&callback=gotFilmFromID'
				} else {
					jsonPTag.src = 'http://www.omdbapi.com/?s='+encodeURIComponent(this.value)+'&callback=getFilmSuggestions'
				}
			})
			
			
			var FilmToBeShown = null;
			//Listen for clicks on film suggestions and move forward in the process
			$('#film-suggestions li').on('click', function(e) {
				e.stopPropagation()
				e.preventDefault()
				var film = Filmsoc.admin.suggestions[$(this).index()]
				if(!this.innerHTML || !film) {
					return;
				}
				console.log(film)
				document.querySelectorAll('#choosetime h3')[0].innerHTML = 'When will '+film.Title+' (' + film.Year + ') be showing?' 
				$('.slidepanel-wrapper').addClass('step2')
				FilmToBeShown = film.imdbID;
				Filmsoc.api('getFilmData', {imdbid:film.imdbID}, function(r) {
					console.log(r)
					document.getElementById('new-film-poster').style.backgroundImage = 'url(../posters/'+r.poster+')'
				}, true)
			})
			
			//Validate input on time box
			$('#new-listing-time').on('input', function(e) {
				var val = this.value,
					d = Filmsoc.utils.dateFromPrettyTimestamp(val),
					now = new Date()
					isValid = (!!d && !isNaN(d.getMonth()) && d > now && val.length === 16 && Filmsoc.utils.dateRegex.test(val))
					
				if(isValid) {
					$(this).removeClass('problem')
					$('#new-showing-submit-button').removeClass('disabled')
				} else {
					$(this).addClass('problem')
					$('#new-showing-submit-button').addClass('disabled')
				}
			}).trigger('input')
			
			//Listen for clicks on submit button
			$('#new-showing-submit-button').on('click', function(e) {
				e.stopPropagation()
				e.preventDefault()
				var timestamp = document.getElementById('new-listing-time').value,
					d = Filmsoc.utils.dateFromPrettyTimestamp(timestamp),
					now = new Date()
					isValid = (!!d && !isNaN(d.getMonth()) && d > now && timestamp.length === 16 && Filmsoc.utils.dateRegex.test(timestamp)),
					apiTimestamp = Filmsoc.utils.apiTimestamp(Filmsoc.utils.dateFromPrettyTimestamp(timestamp))
					
				if(isValid && !!FilmToBeShown) {
					var btn = $(this)
					btn.addClass('loading')
					Filmsoc.api('addNewShowing', {imdbid:FilmToBeShown, time:apiTimestamp}, function(r) {
						btn.removeClass('loading')
						console.log(r)
						var film = r.omdbResult
						document.querySelectorAll('#showingadded h3')[0].innerHTML = 'Success! New showing for '+film.Title+' (' + film.Year + ') scheduled for '+r.time 
						$('.slidepanel-wrapper').addClass('step3')
					}, true)
				}
			})
		},
		
		suggestions: [],
		
		displayFilmSuggestions: function(suggestions) {
			var i = 0,
				l = 3,
				ul = document.getElementById('film-suggestions')
			Filmsoc.admin.suggestions = suggestions
			for(i;i<l;i++) {
				if(suggestions[i]) {
					ul.children[i].innerHTML = suggestions[i].Title + ' (' + suggestions[i].Year + ')'
					ul.children[i].className = ''
				} else {
					ul.children[i].innerHTML = ''
					ul.children[i].className = 'hide'
				}
			}
		}
	},
	
	api: function(api, data, callback, debug) {
		var req = new XMLHttpRequest()
		
		data.api = api
		
		req.onload = function() {
			if(this.status === 200) {
				if(debug) {
	 				console.log(this)
	 				console.log(this.responseText)
	 				console.log(req.responseText)
	 				try {
	 					console.log(JSON.parse(req.responseText))
	 				} catch(e){}
				}
				if(callback) {
					callback(JSON.parse(req.responseText))
				}
			} else {
				console.error(this.status, this.statusText, this.responseText)
			}
		}
		req.open('POST', 'api/')
		req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
		req.send(JSONtoQueryString(data))
	},
	
	utils: {
		prettyTimestamp: function() {
			var d = new Date(),
				s = this.date2Digits(d.getDate())+'-'+this.date2Digits(d.getMonth()+1)+'-'+d.getFullYear()+' '+this.date2Digits(d.getHours())+':'+this.date2Digits(d.getMinutes());
			return s;
		},
		apiTimestamp: function(d) {
			var s = d.getFullYear()+'-'+this.date2Digits(d.getMonth()+1)+'-'+this.date2Digits(d.getDate())+' '+this.date2Digits(d.getHours())+':'+this.date2Digits(d.getMinutes())+':'+this.date2Digits(d.getSeconds());
			return s;
		},
		dateFromPrettyTimestamp: function(d) {
//			2014-01-06 06:00:00
			var parts = d.split(' '),
				dat = parts[0].split('-'),
				tim = parts[1].split(':')
				result = new Date (dat[2], dat[1]-1, dat[0], tim[0], tim[1]);
			return result;
		},
//		dateRegex: /[0-9]{4}-[0-1][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]/,
		dateRegex: /[0-3][0-9]-[0-1][0-9]-[0-9]{4} [0-2][0-9]:[0-5][0-9]/,
		date2Digits: function(d) {
			d = d+'';
			return d.length>1?d:'0'+d;
		},
		
		imdbRegex: /tt\d{7}/
	}
}

$(function() {
	Filmsoc.init();
})

function getFilmSuggestions(result) {
	try {
		var j = result.Search.filter(function(r) {
			return r.Type === 'movie'
		})
		console.log(j)
		Filmsoc.admin.displayFilmSuggestions(j)
	} catch(e) {
		Filmsoc.admin.displayFilmSuggestions([])
	}
}

function gotFilmFromID(result) {
	try {
		var j = result
		console.log(j)
		Filmsoc.admin.displayFilmSuggestions([j])
	} catch(e) {
		Filmsoc.admin.displayFilmSuggestions([])
	}
}

function JSONtoQueryString(j) {
	var a = []
	for(key in j) {
		if(j.hasOwnProperty(key)) {
			a.push(encodeURIComponent(key)+'='+encodeURIComponent(j[key]))
		}
	}
	return a.join('&')
}

$(function() {if(!window.requestAnimationFrame) {
	window.requestAnimationFrame = (function(){
		window.webkitRequestAnimationFrame ||
		window.mozRequestAnimationFrame    ||
		function( callback ){
			window.setTimeout(callback, 1000 / 60);
		}
	})()
}})