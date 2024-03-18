VistaModeloDetalleListaRaleo = function (data,base) {
    var self = this;
    var cabecera = base;
    ko.mapping.fromJS(data, MappingCatalogo , self);
    self.DecimalPrecio = ko.observable(CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO);

    self.InicializarVistaModelo =  function (event,callback,callback2) {
      if(event) {
          $(self.InputProducto()).on("focusout",function(event){
            self.ValidarNombreProducto(undefined,event);
          });
      }
    }

    self.InputCodigoMercaderia = ko.computed( function() {
      if(self.IdListaRaleoMercaderia != undefined) {
        return "#"+self.IdListaRaleoMercaderia()+"_input_CodigoMercaderia";
      }
      else {
        return "";
      }
    },this);

    self.ValidarPrecio = function(data,event) {
      if(event) {
        // var precio =parseFloat($(event.target).val());
        //
        // data.Precio(precio.toFixed(2));
      }
    }

    self.OnKeyEnter = function(data,event,$callbackParent) {
      if(event) {
        $callbackParent(data,event);
      }
      return true;
    }

    self.OnFocus = function(data,event) {
      if(event)  {
          $(event.target).select();
      }
    }

    self.OnChangePrecio = function (data,event) {
      if (event) {
        cabecera.CopiaIdProductosDetalle().push(data.IdProducto());
      }
    }
}
