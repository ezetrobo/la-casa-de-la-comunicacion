(Sawubona.carousel = function(){
	this.carousels = $('.carousel-responsive');

	for(var i = 0; i < this.carousels.length; i++){
		//	Por cada .carousel
		var self = $(this.carousels[i]);
		var setItems = (self.attr('data-items') != undefined && self.attr('data-items') != '') ? self.attr('data-items').split(',') : [1, 2, 3, 4];
		//	Gardo sus items en una variable global
		window["bkp-items-" + i] = (window["bkp-items-" + i] != undefined) ? window["bkp-items-" + i] : self.children('.carousel-inner').children('.item').removeClass('active').removeClass('item');
		var items = window["bkp-items-" + i];
		var width = window.innerWidth;
		//	Segun el ancho de la pantalla es la cantidad de items-responsive que se mostraran
		var cantidad = (width >= 998) ? (setItems[3] != undefined ? setItems[3] : 4) : ((width >= 768) ? (setItems[2] != undefined ? setItems[2] : 3) : ((width >= 480) ? (setItems[1] != undefined ? setItems[1] : 2) : (setItems[0] != undefined ? setItems[0] : 1) ));

		//	Limpio los contenedores
		self.children('.carousel-inner').empty();
		self.children('.carousel-indicators').empty();

		var k = 0;
		for(var j = 0; j < (items.length / cantidad); j++){
			var item = document.createElement('div');
			item.className = (j == 0) ? 'item active' : 'item';

			//	Creo un item-responsive
			for(var l = 0; l < cantidad; l++){
				if(items[k] != undefined){
					items[k].className = 'item-responsive';
					items[k].style.width = (100 / cantidad) + '%';
					items[k].style.overflow = 'hidden';
					items[k].style.float = 'left';
					item.innerHTML += items[k].outerHTML;
					k++;
				}
			}

			var li = document.createElement('li');
			li.setAttribute('data-target', '#' + self.attr('id'));
			li.setAttribute('data-slide-to', j);
			li.className = (j == 0) ? 'active' : '';

			self.children('.carousel-inner').append(item);
			self.children('.carousel-indicators').append(li);
		}
	}
})();

$(window).resize(function(){
	Sawubona.carousel();
});

$('.carousel').hammer().bind('swipeleft', function(e){
	$(this).carousel('next')
});

$('.carousel').hammer().bind('swiperight', function(e){
	$(this).carousel('prev')
});