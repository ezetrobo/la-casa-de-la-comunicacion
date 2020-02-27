(function($){
    $.fn.extend({
        loading : function(show){
           var show = (show == undefined) ? true : show;
            var div = document.getElementById('sawubona-utils-loading');
            if(div == null){
                div = document.createElement('div');
                div.id = 'sawubona-utils-loading';
                div.style.position = 'fixed';
                div.style.top = '0';
                div.style.right = '0';
                div.style.bottom = '0';
                div.style.left = '0';
                div.style.zIndex = '9999999999';
                div.style.transition = 'all 0.75s';
                div.style.background = '#999';
                img = document.createElement('img');
               // img.src = Sawubona.baseUrl + 'img/loading-fftt.png';
                img.src = Sawubona.baseUrl + 'img/loading.svg';
               
                img.style.position = 'absolute';
                img.style.margin = 'auto';
                img.style.height = '50px';
                img.style.width = '50px';width = '50px';
                img.style.top = '0';
                img.style.right = '0';
                img.style.bottom = '0';
                img.style.left = '0';
                img.style.transition = 'opacity 0.75s';
              //  img.style.animation = 'rotar 1.5s linear infinite';
                div.appendChild(img);
                document.body.appendChild(div);
            }
            div.style.opacity = (show) ? '0.8' : '0';
            div.style.visibility = (show) ? 'visible' : 'hidden';
        }
    });
})(jQuery);