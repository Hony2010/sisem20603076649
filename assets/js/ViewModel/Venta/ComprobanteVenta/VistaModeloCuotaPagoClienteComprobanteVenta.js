VistaModeloCuotaPagoClienteComprobanteVenta = function (data, $parent) {
	var self = this;
	self.parent = $parent;
	ko.mapping.fromJS(data, MappingVenta, self);
	self.DecimalMontoCuota = ko.observable(2);

	self.InicializarVistaModelo = function (data, event) {
		if (event) {
			$(self.InputFechaPagoCuota()).inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
		}
	}

	self.InicializarValidator = function (event) {
		if (event) {
		}
	}

	self.OnFocus = function (data, event) {
		if (event) { //, callback
			$(event.target).select();
		}
	}

	self.OnKeyEnter = function (data, event) {
		if (event) {		
			var resultado = $(event.target).enterToTab(event);						
			return resultado;
		}		
	};

	self.ValidarFechaPagoCuota = function(data,event,callback) {
		if (event) {
			console.log("ValidarFechaPagoCuota");
			$(event.target).validate(function (valid, elem) {				
				if(callback) callback(data,event);
			});								
		}
	}

	self.ValidarMontoCuota = function(data,event,callback) {
		if (event) {
			if (data.MontoCuota () === "") data.MontoCuota("0.00");
      $(event.target).validate(function (valid, elem) {
				if(callback) callback(data,event);
      });			
			
			//return resultado;
		}
	}
	
	self.OnChangeFechaPagoCuota = function(data,event) {
		if(event) {
			
		}
	}

	self.InputFechaPagoCuota = ko.computed(function () {
    if (self.IdCuotaPagoClienteComprobanteVenta != undefined) {
      return "#" + self.IdCuotaPagoClienteComprobanteVenta() + "_input_FechaPagoCuota";
    }
    else {
      return "";
    }
  }, this);

}
