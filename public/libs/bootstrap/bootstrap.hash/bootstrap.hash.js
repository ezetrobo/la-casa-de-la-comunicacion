function hash(){
	var self = this;

	self.show = function(){
		if(window.location.hash != ''){
			//	Collapse
			if($(window.location.hash).hasClass('panel-collapse')){
				$('.panel-group a[data-parent=' + $('a[href=' + window.location.hash + ']').attr('data-parent') + ']').addClass('collapsed');
				$('.panel-group a[href=' + window.location.hash + ']').removeClass('collapsed');
				$('.panel-group .panel-collapse').removeClass('in');
				$('.panel-group ' + window.location.hash).addClass('in');
			}

			//	Modal
			else if($(window.location.hash).hasClass('modal')){
				$(window.location.hash).modal();
			}

			//	Tab
			else if($(window.location.hash).hasClass('tab-pane')){
				$('.nav.nav-tabs li').removeClass('active');
				$('.nav.nav-tabs a[href=' + window.location.hash + ']').parents('li').addClass('active');
				$('.tab-content').children('.tab-pane').removeClass('active');
				$('.tab-content').children(window.location.hash).addClass('active');
			}
		}
	}

	self.events = function(){
		//	Panels
		$('body').on('click', '.panel-group a', function(){
			var hash = this.getAttribute('href');
			if(hash.indexOf('#') == 0)
				window.location.hash = hash;
		});

		//	Tabs
		$('body').on('click', '.nav li a', function(){
			var hash = this.getAttribute('href');
			if(hash.indexOf('#') == 0)
				window.location.hash = hash;
		});

		//	Buttons
		$('body').on('click', 'button', function(){
			var hash = this.getAttribute('data-target');
			if(hash != null && hash.indexOf('#') == 0)
				window.location.hash = hash;
			else if(this.getAttribute('data-dismiss') == 'modal')
				window.location.hash = '';
		});

		//	Hash change
		$(window).on('hashchange', function(){
			if(window.location.hash != '' && $(window.location.hash).hasClass('modal')){
				$(window.location.hash).modal();
			}
		});
	}

	self.events();
	self.show();
}

hash();