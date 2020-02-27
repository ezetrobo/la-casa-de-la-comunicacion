function validations(oForm){
	Sawubona.validations = this;
	var self = this;
	self.form = oForm;

	self.isDocument = function(oElement){
		return (typeof oElement == 'object' && oElement.value > 999999);
	}

	self.isEmail = function(oElement){
		return (typeof oElement == 'object'  && (/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w/.test(oElement.value)));
	}

	self.isNumber = function(oElement){
		return (typeof oElement == 'object' && oElement.value != '' && !isNaN(oElement.value));
	}

	self.isRequired = function(oElement){
		switch(oElement.tagName){
			case 'INPUT':
				return (oElement.value != '');
			case 'SELECT':
				return (oElement.value != '' || oElement.value > 0);
		}
	}

	self.isString = function(oElement){
		return (typeof oElement == 'object' && typeof oElement.value == 'string');
	}

	self.isTelephone = function(oElement){
		return (typeof oElement == 'object' && oElement.value != '' && !isNaN((oElement.value.replace(/ /g, '')).replace(/-/g, '')));
	}

	self.proccess = function(xClass, xFunction){
		//	xFunction : recibe un elemento, retorna true si pasa la validacion, false si no lo hace
		if(typeof xClass == 'string' && typeof xFunction == 'function'){
			vElements = self.form.getElementsByClassName(xClass);
			var v = true;
			if(vElements.length > 0){
				for(i = 0; i < vElements.length; i++){
					if(xFunction(vElements[i]))
						vElements[i].className = vElements[i].className.replace(' error', '');
					else{
						v = false;
					 	if(vElements[i].className.indexOf(' error') == -1)
							vElements[i].className += ' error';
					}
				}
			}
			return v;
		}
	}

	self.validate = function(){
		if(!self.proccess('is-required', self.isRequired)) return false;
		if(!self.proccess('is-document', self.isDocument)) return false;
		if(!self.proccess('is-string', self.isString)) return false;
		if(!self.proccess('is-email', self.isEmail)) return false;
		if(!self.proccess('is-number', self.isNumber)) return false;
		if(!self.proccess('is-telephone', self.isTelephone)) return false;
		return true;
	}

	return self.validate();
}