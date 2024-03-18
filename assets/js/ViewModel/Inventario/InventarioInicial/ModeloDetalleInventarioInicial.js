ModeloDetalleInventarioInicial = function (data) {
    var self = this;
    var base = data;

    self.InicializarModelo =function(event,callback,callback2) {
      if(event) {
        if(callback)
          self.callback=callback;
        if(callback2)
          self.callback2=callback2;
      }
    }

    self.Reemplazar = function(data) {
      if(data) {
        data.ValorUnitario = data.ValorUnitario === "" || data.ValorUnitario === null ? "0.00" : data.ValorUnitario;
        var nuevodetalle = self.NuevoDetalleInventarioInicial;
        var includesList =Object.keys(ko.mapping.toJS(nuevodetalle,{ignore : "CantidadInicial"}));
        var nuevadata = leaveJustIncludedProperties(data, includesList);
        var copia = Knockout.CopiarObjeto(nuevodetalle);
        var $cantidad = self.CantidadInicial() === "" ? "0.00" : self.CantidadInicial();
        var $fechavencimiento = self.FechaVencimiento() === "" ? "" : self.FechaVencimiento();
        var $fechadocumentozofra = self.FechaEmisionDocumentoSalidaZofra() === "" ? self.FechaHoy() : self.FechaEmisionDocumentoSalidaZofra();
        var $fechadua = self.FechaEmisionDua() === "" ? self.FechaHoy() : self.FechaEmisionDua();        

        var adicionaldata= { 'SaldoPendienteSalida':  "0.00" ,
                              'CantidadInicial':  data.CantidadInicial ,
                              'FechaVencimiento' : $fechavencimiento,
                              'FechaEmisionDocumentoSalidaZofra' : $fechadocumentozofra,
                             'FechaEmisionDua' : $fechadua,
                             'ValorUnitario':  data.ValorUnitario ,
                             'IdInventarioInicial' : self.IdInventarioInicial()  };
        ko.mapping.fromJS(adicionaldata, {} , copia);
        ko.mapping.fromJS(nuevadata, MappingInventario.DetalleInventarioInicial, copia);
        
        var resultado = new VistaModeloDetalleInventarioInicial(copia);
        base.__ko_mapping__= undefined;
        var output = ko.mapping.toJS(resultado,mappingIgnore);
        ko.mapping.fromJS(output,{}, base);
        return resultado;
      }
    }

    self.ReemplazarFilaExcel = function(data) {
      if(data) {
        data.ValorUnitario = data.ValorUnitario === "" || data.ValorUnitario === null ? "0.00" : data.ValorUnitario;
        var nuevodetalle = self.NuevoDetalleInventarioInicial;
        var includesList =Object.keys(ko.mapping.toJS(nuevodetalle,{ignore : "CantidadInicial"}));
        var nuevadata = leaveJustIncludedProperties(data, includesList);
        var copia = Knockout.CopiarObjeto(nuevodetalle);
        var $cantidad = self.CantidadInicial() === "" ? "0.00" : self.CantidadInicial();
        var $valorunitario = self.ValorUnitario() === "" ? "0.00" : self.ValorUnitario();
        var $numerolote = self.NumeroLote() === "" ? "" : self.NumeroLote();
        var $fechavencimiento = self.FechaVencimiento() === "" ? "" : self.FechaVencimiento();
        var $documentozofra = self.NumeroDocumentoSalidaZofra() === "" ? "" : self.NumeroDocumentoSalidaZofra();
        var $numerodua = self.NumeroDua() === "" ? "" : self.NumeroDua();
        var $numeroitemdua = self.NumeroItemDua() === "" ? "" : self.NumeroItemDua();
        var $fechadocumentozofra = self.FechaEmisionDocumentoSalidaZofra() === "" ? self.FechaHoy() : self.FechaEmisionDocumentoSalidaZofra();
        var $fechadua = self.FechaEmisionDua() === "" ? self.FechaHoy() : self.FechaEmisionDua();

        var adicionaldata= { 'SaldoPendienteSalida':  "0.00" ,
                              'CantidadInicial':  $cantidad ,
                              'NumeroLote' : $numerolote,
                              'FechaVencimiento' : $fechavencimiento,
                              'FechaEmisionDocumentoSalidaZofra' : $fechadocumentozofra,
                             'FechaEmisionDua' : $fechadua,
                              'NumeroDocumentoSalidaZofra' : $documentozofra,
                              'NumeroDua' : $numerodua,
                              'NumeroItemDua' : $numeroitemdua,
                             'ValorUnitario':  $valorunitario ,
                             'IdInventarioInicial' : self.IdInventarioInicial()  };
        ko.mapping.fromJS(adicionaldata, {} , copia);
        ko.mapping.fromJS(nuevadata, MappingInventario.DetalleInventarioInicial, copia);
        var resultado = new VistaModeloDetalleInventarioInicial(copia);
        base.__ko_mapping__= undefined;
        var output = ko.mapping.toJS(resultado,mappingIgnore);
        ko.mapping.fromJS(output,{}, base);
        return resultado;
      }
    }

    self.ConsultarInventarioInicialPorIdProductoSede = function(data,event,callback) {
      if(event) {        
        var $data = Knockout.CopiarObjeto(data);
        var datajs = ko.mapping.toJS({"Data": $data});
  
        $.ajax({
          type: 'GET',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Inventario/InventarioInicial/cInventarioInicial/ConsultarInventarioInicialPorIdProductoSede',
          success: function ($data2) {     
            
              callback($data2,event);
            }
        });
      }
    }
  
}
