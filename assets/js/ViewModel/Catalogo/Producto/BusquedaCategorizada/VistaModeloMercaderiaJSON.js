VistaModeloMercaderiaJSON = function (data, base) {
  var self = this;
  var base = base;
  ko.mapping.fromJS(data, {}, self);

  self.InicializarModelo = function (data,event) {
    if(event) {
    }
  }

  self.OnFocus = function(data,event) {
    if(event)  {
        $(event.target).select();
    }
  }

  self.ValidarCantidad = function(data,event) {
    if(event) {
      if(data.Cantidad() === "") data.Cantidad("0.00");
    }
  }

  self.ColorText = ko.computed( function () {
    // var stock = self.StockProducto().replace(',','');
    // return parseFloat(stock) < 0 ? 'text-danger' : "text-secondary"
    return "";
  },this);

  self.ValidarPrecioUnitario = function(data,event) {
    if(event) {
      if(data.PrecioUnitario() === "") data.PrecioUnitario("0.00");
    }
  }

  self.OnClickImagenMercaderia = function(data, event)
  {
    if(event)
    {
      if (!($(event.target).closest('.panel').hasClass('active'))) {
        $(event.target).closest('.panel').addClass('active');
      }
      
      // var response = {title: "<strong></strong>", type: "success", clase: "notify-producto", message: "Se agregó el producto "+data.NombreProducto()+"."};
      // CargarNotificacionBusquedaProducto(response);
      

      base.OnClickAgregarMercaderiaImagenPuntoVenta(data, event);
    }
  }

  self.OnClickAgregarMercaderia = function(data, event, root)
  {
    if(event)
    {
      if (!($(event.target).closest('.panel').hasClass('active'))) {
        $(event.target).closest('.panel').addClass('active');
      }

      if (data.ListaAnotacionesPlato().length > 0) {
        root.AnotacionesPlatoProducto([]);
        var mercaderia = ko.mapping.toJS(data);
        var objeto = [];
        mercaderia.ListaAnotacionesPlato.forEach(function(entry, key){
          entry.Mercaderia = mercaderia;
          var objetoanotacion = new VistaModeloAnotacionProducto(entry);
          root.AnotacionesPlatoProducto.push(objetoanotacion);
        });
        $('.titulo-anotacion').text('¿Como desea su "'+mercaderia.NombreProducto+'"?')
        $("#PreviewAnotacionesPlato").modal("show");
      } else {
        var $data = {'Mercaderia': data};
        base.OnClickAgregarMercaderiaImagenComanda($data, event);
      }
    }
  }

  self.OnClickAnotacionMercaderia = function (data, event) {
    if (event) {
      self.OnClickImagenMercaderia(data.Mercaderia, event);
    }
  }

  self.OnClickImgPreviewProduct = function (data, event, base) {
    if (event) {
      $("#ImgProduct").attr("src",data.Foto());
      $("#PreviewImgProduct").modal("show");
    }
  }

}
