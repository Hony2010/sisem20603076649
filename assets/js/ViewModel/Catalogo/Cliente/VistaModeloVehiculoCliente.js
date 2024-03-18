VistaModeloVehiculoCliente = function (data, $parent) {
  var self = this;
  self.parent = $parent;
  ko.mapping.fromJS(data, MappingCatalogo, self);

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      //self.InicializarModelo();
    }
  }

  self.InicializarValidator = function (event) {
    if (event) {

    }
  }

  self.OnFocus = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      if (callback) callback(data, event);
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }
}
