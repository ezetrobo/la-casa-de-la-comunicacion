(function footer(){
	var self = this;
	self.sf = document.getElementById('sawubona-footer');
	if(self.sf == null) return false;
	self.color = (self.sf.getAttribute('data-color') != null) ? self.sf.getAttribute('data-color') : 'blanco';
	self.objects = {
		0: {
			'src': 'http://app.fidelitytools.net/img/pieCu/fidelity_' + self.color + '.png',
			'alt': 'Fidelitytools - www.fidelitytools.net',
			'link': 'http://www.fidelitytools.net'
		},
		1: {
			'src': 'http://app.fidelitytools.net/img/pieCu/alojanet_' + self.color + '.png',
			'alt': 'Alojanet - www.alojanet.com',
			'link': 'http://www.alojanet.com'
		},
		2: {
			'src': 'http://app.fidelitytools.net/img/pieCu/sawubona_' + self.color + '.png',
			'alt': 'Sawubona - www.sawubona.com.ar',
			'link': 'http://www.sawubona.com.ar'
		}
	};

	if(self.sf != null && self.sf != undefined){
		self.sf.style.height = '23px';
		self.sf.style.width = '100%';
		self.sf.style.overflow = 'hidden';

		for(i = 0; i < 3; i++){
			var img = document.createElement('img');
			img.src = self.objects[i].src;
			img.alt = self.objects[i].alt;
			img.style.border = '0';
			var a = document.createElement('a');
			a.href = self.objects[i].link;
			a.target = '_blank';
			a.appendChild(img);
			var div = document.createElement('div');
			div.style.width = '21px';
			div.style.width.hover
			div.style.height = '25px';
			div.style.float = 'right';
			div.style.overflow = 'hidden';
			div.style.transition = 'all 0.75s';
			div.onmouseover = function(){ this.style.width = '118px'; }
			div.onmouseout = function(){ this.style.width = '21px'; }
			div.appendChild(a);
			self.sf.appendChild(div);
		}
	}
})()