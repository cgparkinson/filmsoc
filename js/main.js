var Filmsoc = {
	
	init: function() {
		if(location.pathname.indexOf('/admin') > -1) {
			if(document.body.id == 'pr') {
				Filmsoc.admin.initPR()
			} else {
				Filmsoc.admin.init()
			}
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
		
		initPR: function() {
			var c = document.getElementById('fbcovercanvas'),
				ctx = c.getContext('2d'),
				w = c.width = 1702,
				h = c.height = 630,
				pr = Filmsoc.admin.pr
			
			ctx.save()
			ctx.font = '60px "Open Sans", sans-serif'
			ctx.textAlign = 'center'
			ctx.fillText('Loading Banner Editor', w/2, h/2)
			
			//Preload images
			for(var i = 0; i < pr.preloadImages.length; i++) {
				var img = pr.preloadImages[i]
				img.img.onload = function() {
					pr.imageLoaded()
				}
				img.img.src = '../../images/'+img.src
			}
			
			//Make button open banner
			document.getElementById('downloadbutton').addEventListener('click', function() {
				window.open(c.toDataURL('image/jpeg'), 'imgwindow')
			}, false)
			
			document.getElementById('downloadbutton2').addEventListener('click', function() {
				window.open(document.getElementById('filmstriptextcanvas').toDataURL('image/png'), 'imgwindow')
			}, false)
		},
		
		pr: {
			preloadImages: [
				{
					src:'film-segment.svg',
					img: new Image()
				},
				{
					src:'logo.svg',
					img: new Image()
				},
				{
					src:'curtain.jpg',
					img: new Image()
				}
			],
			
			loadedCount: 0,
			
			customImages: [
				{
					src:'',
					img: new Image()
				},
				{
					src:'',
					img: new Image()
				},
				{
					src:'',
					img: new Image()
				}
			],
			
			imageLoaded: function() {
				var pr = Filmsoc.admin.pr
				pr.loadedCount++
				if(pr.loadedCount === pr.preloadImages.length) {
					pr.initEditor()
				}
			},
			
			customImageLoaded: function() {
				var pr = Filmsoc.admin.pr
				if(pr.customImages.slice(0).reduce(function(a, b) {
					var bLoaded = b.src === '' || (typeof b.img.naturalWidth !== "undefined" && b.img.naturalWidth !== 0)
					return a && bLoaded
				}, true)) {
					pr.drawFBCoverCanvas()
				}
			},
			
			initEditor: function() {
				var pr = Filmsoc.admin.pr
				
				//Add the event listeners to the file inputs
				$('input[type=file]').on('change', function(e) {
					var f = this.files[0],
						url = window.URL || window.webkitURL,
						src = url.createObjectURL(f),
						i = parseInt(this.id.replace('img', ''))-1,
						img = pr.customImages[i]
					img.src = src
					img.img.onload = function() {
						pr.customImageLoaded()
					}
					img.img.src = img.src
				})

				pr.drawFBCoverCanvas()
				
				//Add event listener to textarea
				$('#filmstriptext').on('input', function(){
					pr.drawFilmstripTextCanvas()
				})
				
				pr.drawFilmstripTextCanvas()
			},
			
			drawFilmstripTextCanvas: function() {
				var c = document.getElementById('filmstriptextcanvas'),
					ctx = c.getContext('2d'),
					pr = Filmsoc.admin.pr,
					txt = document.getElementById('filmstriptext').value,
					lines = txt.split(/(\r\n|\n|\r)/gm).filter(function(el) {
						return !((/(\r\n|\n|\r)/gm).test(el))
					}),
					h = lines.length*110,
					w = Math.max.apply(null, lines.slice(0).map(function(el) {
						return el.length
					}))*55,
					imgs = {
						filmstrip: pr.preloadImages[0].img,
						logo: pr.preloadImages[1].img,
						curtain: pr.preloadImages[2].img
					}
					
				console.log(w, h, lines)
				
				c.width = w*2
				c.style.width = w+'px'
				c.height = h*2
				c.style.height = h+'px'
				
// 				var filmstrip = ctx.createPattern(imgs.filmstrip, 'repeat')
// 				ctx.save()
// 				ctx.scale(2,2)
// 				ctx.fillStyle = filmstrip
// 				ctx.fillRect(0,0,w,h)
// 				ctx.restore()
				
				
				ctx.fillStyle = 'black'
				ctx.textAlign = 'center'
				ctx.font = '110px Open Sans'
				//ctx.textBaseline = 'alphabetic'
				var i,
					j,
					line,
					char,
					factor = 1
					
				for(i = 0; i < lines.length; i++) {
					line = lines[i]
					for(j = 0; j < w; j++) {
						
						ctx.drawImage(imgs.filmstrip, j*110, i*220, 110, 220)
						
						if(j < line.length) {
							char = line[j]
							//Scale so W fits
							if(char === 'W') {
								factor = 0.8
							} else {
								factor = 1
							}
							ctx.save()
							ctx.translate(j*110 + 55, 220*i + 150)
							ctx.scale(factor, 1)
							ctx.fillText(char, 0, 0)
							ctx.restore()
						}
					}
				}
			},
			
			drawFBCoverCanvas: function() {
				var c = document.getElementById('fbcovercanvas'),
					ctx = c.getContext('2d'),
					w = c.width,
					h = c.height,
					pr = Filmsoc.admin.pr,
					imgs = {
						filmstrip: pr.preloadImages[0].img,
						logo: pr.preloadImages[1].img,
						curtain: pr.preloadImages[2].img
					}
				
					
				//Placeholder bg image
				ctx.drawImage(imgs.curtain, 0, 0, w, w/imgs.curtain.width * imgs.curtain.height)
				
				var points = {
					lt: w/3 - 55 - h/2*Math.cos(72*Math.PI/180),
					lb: w/3 - 55 + h/2*Math.cos(72*Math.PI/180),
					rt: w - (w/3 - 55 - h/2*Math.cos(72*Math.PI/180)),
					rb: w - (w/3 - 55 + h/2*Math.cos(72*Math.PI/180))
				}
				
				//Image 1
				if(pr.customImages[0].img) {
					var img1 = pr.customImages[0].img,
						img1w = points.lb,
						img1ratio = Math.max(img1w/img1.width, h/img1.height)
					ctx.save()
						ctx.beginPath()
						ctx.moveTo(0,0)
						ctx.lineTo(points.lt, 0)
						ctx.lineTo(points.lb, h)
						ctx.lineTo(0, h)
						ctx.lineTo(0,0)
						ctx.closePath()
						ctx.clip()
						ctx.drawImage(img1, 0.5*(img1w-img1ratio*img1.width), -0.5*(img1ratio*img1.height-h), img1ratio*img1.width, img1ratio*img1.height)
					ctx.restore()
				}
				//Image 2
				if(pr.customImages[1].img) {
					var img2 = pr.customImages[1].img,
						img2w = points.rt - points.lt,
						img2ratio = Math.max(img2w/img2.width, h/img2.height)
					ctx.save()
						ctx.beginPath()
						ctx.moveTo(points.lt, 0)
						ctx.lineTo(points.rt, 0)
						ctx.lineTo(points.rb, h)
						ctx.lineTo(points.lb, h)
						ctx.closePath()
						ctx.clip()
						ctx.drawImage(img2, points.lt+0.5*(img2w-img2ratio*img2.width), -0.5*(img2ratio*img2.height-h), img2ratio*img2.width, img2ratio*img2.height)
					ctx.restore()
				}
				
				//Image 3
				if(pr.customImages[2].img) {
					var img3 = pr.customImages[2].img,
						img3w = w - points.rb,
						img3ratio = Math.max(img3w/img3.width, h/img3.height)
					ctx.save()
						ctx.beginPath()
						ctx.moveTo(points.rt, 0)
						ctx.lineTo(points.rb, h)
						ctx.lineTo(w, h)
						ctx.lineTo(w, 0)
						ctx.closePath()
						ctx.clip()
						ctx.drawImage(img3, points.rb+0.5*(img3w-img3ratio*img3.width), -0.5*(img3ratio*img3.height-h), img3ratio*img3.width, img3ratio*img3.height)
					ctx.restore()
				}
				
				
				//Filmstrips
				var filmstrip = ctx.createPattern(imgs.filmstrip, 'repeat-x')
				ctx.save()
					ctx.translate(w/3, h/2)
					ctx.rotate(72*Math.PI/180)
					ctx.fillStyle = filmstrip
					ctx.fillRect(-h*1.5, 0, h*3, 110)
				ctx.restore()
				
				ctx.save()
					ctx.scale(-1, 1)
					ctx.translate(-w + w/3, h/2)
					ctx.rotate(72*Math.PI/180)
					ctx.fillStyle = filmstrip
					ctx.fillRect(-h*1.5, 0, h*3, 110)
				ctx.restore()
				
				
				//Logo
				var logo = imgs.logo,
					gap = 400,
					logow = w - gap*2
				ctx.drawImage(
					imgs.logo,
					gap,
					h*0.65,
					logow,
					logo.height * logow/imgs.logo.width
				)
			}
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