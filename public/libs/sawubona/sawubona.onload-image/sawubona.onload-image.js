function onloadImage(image) {
	try{
		
		//alert( imagen.height() ); // alto original. Ej: 600
		var anchoImg = image.naturalWidth;
		var altoImg = image.naturalHeight; 

		var contenedor = image.parentElement;
		var anchoContenedor = contenedor.clientWidth;
		var altoContenedor = contenedor.clientHeight;

		image.className+= 'visible';

		//alert(anchoContenedor );
		//alert(altoContenedor);

		if (anchoImg > altoImg && anchoContenedor > altoContenedor){
		 	image.style.height = '100%';
		 	image.style.width = 'auto';
		 	var anchoImgNew = image.width;
		 	if( anchoImgNew < anchoContenedor) {
		 		image.style.height = 'auto';
		 		image.style.width = '100%';
		 	}

		}
		else if (anchoImg < altoImg && anchoContenedor > altoContenedor){
			//$(contenedor).addClass('ancho');
			image.style.width = '100%';
		 	image.style.height = 'auto';
			var altoImgNew = image.height;
		 	if( altoImgNew < altoContenedor) {
		 		image.style.height = '100%';
		 		image.style.width = 'auto';
		 	}
		}
		else if (anchoImg > altoImg && anchoContenedor < altoContenedor){
			//$(contenedor).addClass('alto');
			image.style.height = '100%';
			image.style.width = 'auto';
			var anchoImgNew = image.width;
		 	if( anchoImgNew < anchoContenedor) {
		 		image.style.height = 'auto';
		 		image.style.width = '100%';
		 	}
		}
		else if (anchoImg < altoImg && anchoContenedor < altoContenedor){
			//$(contenedor).addClass('ancho');
			image.style.width = '100%';
		 	image.style.height = 'auto';
			var altoImgNew = image.height;
		 	if( altoImgNew < altoContenedor) {
		 		image.style.height = '100%';
		 		image.style.width = 'auto';
		 	}
		}
		else if (anchoImg > altoImg && anchoContenedor == altoContenedor){
			//$(contenedor).addClass('ancho');
			image.style.height = '100%';
			image.style.width = 'auto';
			var altoImgNew = image.width;
		 	if( altoImgNew < altoContenedor) {
		 		image.style.width = '100%';
		 		image.style.height = 'auto';
		 	}
		}
		else if (anchoImg < altoImg && anchoContenedor == altoContenedor){
			//$(contenedor).addClass('ancho');
			image.style.width = '100%';
		 	image.style.height = 'auto';
			var altoImgNew = image.height;
		 	if( altoImgNew < altoContenedor) {
		 		image.style.height = '100%';
		 		image.style.width = 'auto';
		 	}
		}
		else if (anchoImg == altoImg && anchoContenedor > altoContenedor){
			//$(contenedor).addClass('ancho');
			image.style.width = '100%';
		 	image.style.height = 'auto';
			var altoImgNew = image.height;
		 	if( altoImgNew < altoContenedor) {
		 		image.style.height = '100%';
		 		image.style.width = 'auto';
		 	}
		}
		else if (anchoImg == altoImg && anchoContenedor < altoContenedor){
			//$(contenedor).addClass('ancho');
			image.style.height = '100%';
			image.style.width = 'auto';
			var altoImgNew = image.width;
		 	if( altoImgNew < altoContenedor) {
		 		image.style.width = '100%';
		 		image.style.height = 'auto';
		 	}
		}
	}
	catch(e) {
		console.log
	}
}

// window.addEventListener("resize", function(){
// 	var images = document.getElementsByClassName('container-image');
// 	for(var i = 0; i < images.length; i++){
// 		onloadImage(images[i].children)
// 	}
// });