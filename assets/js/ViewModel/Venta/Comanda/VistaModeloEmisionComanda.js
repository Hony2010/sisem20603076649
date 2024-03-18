VistaModeloEmisionComanda = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingVenta, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.Comanda,window);
    self.data.Comanda.OnClickBtnBuscadorMercaderia(self.data.Comanda,window,self.data);
    $(".btn-familias").eq(0).addClass("active").click();
    moversidebar();
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.Comanda.Nuevo(data,event,self.PostGuardar);

      self.data.Comanda.DetallesComprobanteVenta([]);
      $(".btn-tipodocumento").eq(0).addClass("active").click();
      sizeProductList();
      sizeProductDetails();
    }
  }

  self.PostGuardar = function(data,event) {
    if(event) {
      if(data.error) {
        $("#loader").hide();
        alertify.alert(data.error.msg,function()  {
        });
      }
      else {
        $("#loader").hide();
        var comprobante_nuevo = ko.mapping.toJS(self.data.ComprobanteVentaNuevo);
        comprobante_nuevo.IdTipoVenta = data.IdTipoVenta;

        self.data.Comanda.OnHideOrShowElement(data, event);
        self.Nuevo(comprobante_nuevo,event);
      }
    }
  }

}
