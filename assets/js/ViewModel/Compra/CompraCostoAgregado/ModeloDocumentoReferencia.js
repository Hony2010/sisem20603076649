ModeloDocumentoReferencia = function (data, base) {
  var self = this;
  var base = base;

  ko.mapping.fromJS(data, {}, self);

  self.Documento = ko.observable(data.NombreAbreviado + '-' + data.SerieDocumento + '-' + data.NumeroDocumento);
  self.NombreProveedor = ko.observable(data.RazonSocial);
  self.DecimalCantidad = ko.observable(CANTIDAD_DECIMALES_COSTO_AGREGADO.CANTIDAD);
  self.CopiaCantidad = ko.observable(data.Cantidad);
  if (!self.Porcentaje) self.Porcentaje = ko.observable("");

  self.CostoUnitarioCalculado = ko.computed(function(){
    var resultado = parseFloat(self.CostoUnitario()) - parseFloat(self.DescuentoUnitario());
    return resultado.toFixed(CANTIDAD_DECIMALES_COSTO_AGREGADO.COSTO_UNITARIO_CALCULADO);
  },this);


  self.InicializarModelo = function (event, data) {
    if(event) {

    }
  }

  self.BorrarFila = function(data, event)
  {
    if(event)
    {
      var referencias = base.DetallesDocumentoReferencia;
      referencias.remove( function (item) { return item.IdDetalleComprobanteCompra() == data.IdDetalleComprobanteCompra(); } );
    }
  }

  self.ChangeCantidad = function(data, event)
  {
    if(event)
    {
      if(parseInt(data.Cantidad()) > parseInt(self.CopiaCantidad()))
      {
        alertify.confirm("Cantidad","¿Esta Seguro modificar a una cantidad superior?",function(){

        },function(){
          data.Cantidad(parseInt(self.CopiaCantidad()));
        });
      }
    }
  }


}
