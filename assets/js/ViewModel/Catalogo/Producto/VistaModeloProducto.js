VistaModeloProducto = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);
    ModeloProducto.call(this,self);

    self.InicializarVistaModelo = function(data,event,callback) {
      if(event) {    
        self.InicializarModelo(data,event,callback);
      }
    }

    self.OnKeyEnter = function (data, event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }

    self.OnFocus = function (data, event) {
      if (event) {
        $(event.target).select();
      }
    }

  self.ValidarCantidad = function (data, event) {
    if (event) {
      if (data.Cantidad() === "") data.Cantidad("0.00");
    }
  }

  self.OnChangeCantidad = function (data, event) {
    if (event) {
      self.CalcularSubTotal(data, event);
    }
  }
  
  self.OnChangePrecioUnitario = function (data, event) {
    if (event) {
      self.CalcularSubTotal(data, event);

    }
  }

  self.ValidarPrecioUnitario = function (data, event) {
    if (event) {
      if (data.PrecioUnitario() === "") data.PrecioUnitario("0.00");
    }
  }

  self.CalcularSubTotal = function (data, event) {
    if (event) {
      var total = parseFloatAvanzado(data.PrecioUnitario()) * parseFloatAvanzado(data.Cantidad())
      self.SubTotal(accounting.formatNumber(parseFloatAvanzado(total), NUMERO_DECIMALES_VENTA));
    }
  }

  self.OnClickBotonAñadir = function(data,event,callback) {
    if (event) {
        if (!($(event.target).closest('.panel').hasClass('active'))) {
          $(event.target).closest('.panel').addClass('active');
        }
        
        callback(data, event); //base.OnClickAgregarMercaderiaImagen
      }
  }
}
