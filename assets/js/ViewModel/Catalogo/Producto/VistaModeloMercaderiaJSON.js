VistaModeloMercaderiaJSON = function (data, base) {
  var self = this;
  var base = base;
  ko.mapping.fromJS(data, {}, self);

  self.InicializarModelo = function (data, event) {
    if (event) {
    }
  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnKeyEnter = function (data, event) {
    var resultado = $(event.target).enterToTab(event);
    return resultado;
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

  self.CalcularSubTotal = function (data, event) {
    if (event) {
      var total = parseFloatAvanzado(data.PrecioUnitario()) * parseFloatAvanzado(data.Cantidad())
      self.SubTotal(accounting.formatNumber(parseFloatAvanzado(total), NUMERO_DECIMALES_VENTA));
    }
  }

  self.ColorText = ko.computed(function () {
    var stock = self.StockProducto().replace(',', '');
    return parseFloat(stock) < 0 ? 'text-danger' : "text-secondary"
  }, this);

  self.ValidarPrecioUnitario = function (data, event) {
    if (event) {
      if (data.PrecioUnitario() === "") data.PrecioUnitario("0.00");
    }
  }

  self.OnClickImagenMercaderia = function (data, event) {
    if (event) {
      if (!($(event.target).closest('.panel').hasClass('active'))) {
        $(event.target).closest('.panel').addClass('active');
      }
      base.OnClickAgregarMercaderiaImagen(data, event);

    }
  }

}
