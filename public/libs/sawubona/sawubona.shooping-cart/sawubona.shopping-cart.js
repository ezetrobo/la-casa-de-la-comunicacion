//  Revisar y testear

function shopping(){
	Sawubona.shopping = this;
	var self = this;

	self.add = function(idProducto){
        var success = function(r){ console.log(r); };
        var error = function(e){ console.log('Add product error', e); };
        var url = baseUrl + '/shopping-cart/add-producto/';
        var post = $.post(
            url,
            {
                idProducto : idProducto,
                cantidad : 1,
                img : null
            },
            success
        );
        post.error(error);
        e.preventDefault();
	}

	self.delete = function(idProducto){
        var success = function(r){ console.log(r); };
        var error = function(e){ console.log('Delete producto error', e); };
        var idProducto = this.getAttribute('shop-id');
        var url = baseUrl + '/shopping-cart/delete-producto/';
        var post = $.post(
            url,
            {
                idProducto : idProducto,
            },
            success
        );
        post.error(error);
        e.preventDefault();
    }

    self.update = function(idProducto, cantidad){
        var success = function(r){ console.log(r); };
        var error = function(e){ console.log('Update cantidad error', e); };
        var url = baseUrl + '/shopping-cart/update-cantidad/';
        var post = $.post(
            url,
            {
                idProducto : idProducto,
                cantidad : cantidad
            },
            success
        );
        post.error(error);
        e.preventDefault();
    }
}