VistaModeloEmisionPreVenta = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingVenta, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.PreVenta,window);
    self.data.PreVenta.OnClickBtnBuscadorMercaderia(self.data.PreVenta,window,self.data);
    $(".btn-familias").eq(0).addClass("active").click();
    moversidebar();
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.PreVenta.Nuevo(data,event,self.PostGuardar);

      self.data.PreVenta.DetallesComprobanteVenta([]);
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

        self.data.PreVenta.OnHideOrShowElement(data, event);
        self.Nuevo(comprobante_nuevo,event);
      }
    }
  }

}
