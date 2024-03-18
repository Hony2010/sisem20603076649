VistaModeloCabeceraCompraMasiva = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {} , self);

    self.SerieDocumento = ko.observable(data.SerieDocumento + '-' +data.NumeroDocumento);
    self.Proveedor = ko.observable(data.RUC + ' - ' + data.RazonSocial);
    // self.FechaEmision = ko.observable(data.FechaEmision);
    // self.Total = ko.observable(accounting.formatNumber(data.Total, NUMERO_DECIMALES_VENTA));
    // self.Producto = ko.observable(data.NombreProducto);
    // self.Unidad = ko.observable(data.Unidad);

    self.DetallesComprobanteCompra = ko.computed(function() {
      var list = [];
      var length = parseInt(data.DetallesComprobanteCompra.length, 10); // the <input> makes `pages` a string!

      for (var i = 0; i < length; i++) {
        list.push(new VistaModeloDetalleCompraMasiva(data.DetallesComprobanteCompra[i]));
      }
      return list;
    });

    self.Observaciones = ko.computed(function() {
      var list = [];
      var length = parseInt(data.Observaciones.length, 10); // the <input> makes `pages` a string!

      for (var i = 0; i < length; i++) {
        var nuevo = {"Observacion":data.Observaciones[i]};
        list.push(nuevo);
      }
      return list;
    });

}
