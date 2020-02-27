(function($){
    $.fn.extend({
        dinamic : function(){
			var oldtitle = document.title;
        	$(this).on('click', 'a', function(e){
        		var open = this.getAttribute('sw-open');
				var close = this.getAttribute('sw-close');

				var show = this.getAttribute('sw-show');
				var hide = this.getAttribute('sw-hide');

				var tgShow = this.getAttribute('sw-tg-show');
				var tgOpen = this.getAttribute('sw-tg-open');
				
				var target = this.getAttribute('sw-target');
				var href = this.getAttribute('href');

				if(tgOpen == null){
					$(close).removeClass('open');
					$(open).addClass('open');
				}
				else
					$(tgOpen).toggleClass('open');

				if(tgShow == null){
					$(hide).removeClass('active');
					$(show).addClass('active');
				}
				else
					$(tgShow).toggleClass('active');

				if(target != null || target == ''){
					if(href != null && href != ''){
						if(target != ''){
							$('body').loading();
							$('html, body').animate({scrollTop: 0}, 500);

							$.ajax({
				                data:  {dinamic: true},
				                dataType: 'json',
				                url:   href,
				                type:  'get',
				                success:  function (response){
									$(target).empty();
				                	//	Agregar un evento de analitycs
				                	window.history.pushState(null, response.title, href);
				                	oldtitle = document.title;
				                	document.title = response.title;
				                	$(target).html(response.data)
				                	$('body').loading(false);

				                	if(tgOpen == null){
										$(close).removeClass('open');
										$(open).addClass('open');
									}
									else
										$(tgOpen).toggleClass('open');

									if(tgShow == null){
										$(hide).removeClass('active');
										$(show).addClass('active');
									}
									else
										$(tgShow).toggleClass('active');
				                },
				                error : function(error){
				                	$(target).empty();
				                	$('body').loading(false);
				                }
				        	});
						}
						else{
							//	Agregar un evento de analitycs
							document.title = oldtitle;
							window.history.pushState(null, oldtitle, href);
						}
					}

					e.preventDefault();
				}
        	});

	        (function(){
	        	if (typeof history.pushState === "function"){
				   	window.onpopstate = function(){
				   		if(Sawubona.baseUrl != window.location.pathname)
				   			location.reload();
				   		else{
				   			document.title = 'Merlino';
							$('.sw-back').removeClass('open');
							$('.sw-back').empty();
						}
				   	};
				}
	        })();
        }
    });
})(jQuery);