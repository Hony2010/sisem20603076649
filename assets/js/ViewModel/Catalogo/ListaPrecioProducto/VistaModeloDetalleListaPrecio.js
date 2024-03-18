VistaModeloDetalleListaPrecio = function (data, base) {
    var self = this;
    ko.mapping.fromJS(data, MappingCatalogo , self);

    self.Cabecera = base
    self.DecimalPrecio = ko.observable(CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO);

    self.OnFocus = self.Cabecera.OnFocus;
    self.OnKeyEnter = self.Cabecera.OnKeyEnter;
    
    self.OnChangePrecio = function (data,event) {
      if (event) {
        //var objeto = { IdProducto : self.Cabecera.IdProducto() , IndicadorProducto : self.Cabecera.IndicadorProducto() };
        var objeto = { IdProducto : data.IdProducto() , IndicadorProducto : data.IndicadorProducto() };        
        self.Cabecera.CopiaIdProductosDetalle().push(objeto);        
      }
    }
  }
