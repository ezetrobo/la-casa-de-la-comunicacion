// function scrolling(loading){
// 	Sawubona.scrolling = this;
// 	var self = this;

// 	self.loading = (loading != undefined && loading == true) ? true : false;
// 	self.limit = (limit != undefined) ? limit : 5;
// 	self.offset = self.limit;
// 	self.flag = true;
// 	self.stop = false;
// 	self.elements = this;

// 	$(window).scroll(function(){
// 		if(($(window).scrollTop() + $('footer').height()) >= $(document).height() - $(window).height()){
// 			if(self.flag && !self.stop){
// 				(self.loading) ? $('body').loading() : null;
// 				var url = window.location + (window.location.search != '') ? '&' : '?' + 'offset=' + self.offset + '&scrolling=true&callback=JSON_CALLBACK';
// 				var get = $.get(
// 					url,
// 					null,
// 					function(result){
// 						self.stop = (result == '' || result == null) ? true : false;
// 						self.offset += self.limit;
// 						self.flag = true;
// 						for(var i = 0; i < self.elements.length; i++){
// 							$(self.elements[i]).append(result);
// 						}
// 						(self.loading) ? $('body').loading(false) : null;
// 					}
// 				);
// 				get.error(function(){
// 					(self.loading) ? $('body').loading(false) : null;
// 					console.log('error')
// 				});
// 			}
// 		}
// 	});
// }


//Funcion para paginar con un llamado onclick desde el boton
var firstClick = false;   
function vermas() {
	var self = this;
	document.getElementById('vermas-scroll-txt').innerHTML = 'CARGANDO';
	$('#vermas-scroll').addClass('cargando');
		
	self.loading = (loading != undefined && loading == true) ? true : false;
	self.limit = (limit != undefined) ? limit : 1;
	
    if (location.pathname !=  baseUrl+'novedades'){

	    //Controla si el offset es igual a 6 cuando se inicia la pagina, sino lo establece en 6
	    if (self.offset == 9){
	    	self.offset = self.limit;
    	}
    }

	//Controla si sigue dentro de la misma pagina , sino vuelve el offset a 6
	//Esto esta planteado para que si se dirige a otra categoria el offset no se siga sumando
    if (document.getElementById('prr').value != window.location.pathname) {
		self.offset = self.limit;
    }
       	
	self.flag = true;
	self.stop = false;

	if(self.flag && !self.stop){
		self.flag = false;
		$('body').loading();
		
		var url = window.location.href + ( (window.location.search != '') ? '&' : '?' ) + 'offset=' + self.offset + '&scrolling=true&callback=JSON_CALLBACK';
		var get = $.get(
			url,
			null,
			function(result){
				if (result === "" ) {
            		document.getElementById('vermas-scroll').style.display = "none";
            		self.stop = (result == '' || result == null) ? true : false;
					self.offset += self.limit;
					self.flag = true;
					$('.container-scroll').append(result);
					$('body').loading(false);
				}
				else {
					self.stop = (result == '' || result == null) ? true : false;
					self.offset += self.limit;
					self.flag = true;
					$('.container-scroll').append(result);
					$('body').loading(false);

					var xUrl = baseUrl+'catalogo';
					if (location.pathname == xUrl){
						document.getElementById('vermas-scroll-txt').innerHTML = 'VER MÁS PRODUCTOS';
					} else {
						document.getElementById('vermas-scroll-txt').innerHTML = 'VER MÁS';
					}
					//console.log(location.pathname);
					$('#vermas-scroll').removeClass('cargando');
				}
			}
		);
		document.getElementById('prr').value = location.pathname;

		get.error(function(e){
			(self.loading) ? $('body').loading(false) : null;
			console.log('error', e);
		});
	}

	/* if(!firstClick){
     	var controlVM = 9;
     	firstClick = true; 
     	if (controlVM < 9  ) {
            document.getElementById('vermas-scroll').style.display = "none";
	    }
	} else {
		var controlVM = document.getElementById('ControlVM').value;
	    if (controlVM < 9  ) {
	    	document.getElementById('vermas-scroll').style.display = "none";
	    }
	} */
}
