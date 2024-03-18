BusquedaComprobantesCompraNDModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.EstadoSelector = ko.observable(false);
    self.Documento = ko.observable(data.SerieDocumento + "-" + data.NumeroDocumento);

    self.CambiarEstadoCheck = function (data, event) {
      if(event){
        var id = "#"+data.IdComprobanteCompra()+'_tr_comprobantecompraporcliente';
        var objeto = Knockout.CopiarObjeto(data);
        var cant_items = window.Motivo.Reglas.CantidadFacturas;
        var facturamixta = window.Motivo.Reglas.FacturaMixtas;
        var filas_bcv = ViewModels.data.NotaDebitoCompra.BusquedaComprobanteCompraND().length;
        var filas_mcv = ViewModels.data.NotaDebitoCompra.MiniComprobantesCompraND().length;
        if(data.EstadoSelector() == true && filas_bcv > 0 && cant_items == 0)
        {
          alertify.alert("Usted Tiene seleccionado un comprobante. El Motivo no permite seleccionar mas.");
          self.EstadoSelector(false);
          return false;
        }

        if(data.EstadoSelector() == true && window.Motivo.Data.IdMotivoNotaDebitoCompra == 10)
        {
          var total = parseFloat(data.Total());
          var igv = (total / 1.18) * 0.18;
          var _igv = parseFloat(data.IGV());
          console.log("Motivo OTROS  CONCEPTOS");
          console.log("TOTAL: " + total + ", IGVCORRECTO: " + igv + ", IGVANTERIOR: " + _igv);

          if(_igv.toFixed(2) <= igv.toFixed(2)){
            alertify.alert("Usted No Puede Seleccionar este comprobante con el motivo OTROS CONCEPTOS. Por favor, seleccione otro motivo.");
            self.EstadoSelector(false);
            return false;
          }
        }

        if(data.EstadoSelector() == true && facturamixta == 0 && (parseFloat(data.ValorCompraGravado()) > 0 && parseFloat(data.ValorCompraNoGravado()) > 0))
        {
          alertify.alert("Para este motivo no se permiten facturas Mixtas.");
          self.EstadoSelector(false);
          return false;
        }
        else if(facturamixta == 0){
          if(filas_mcv > 0)
          {
            var primera_fila = ViewModels.data.NotaDebitoCompra.MiniComprobantesCompraND()[0];
            var tipo_factura = 1; //TIPO NO GRAVADO
            var data_tipo_factura = 0;
            if(parseFloat(primera_fila.ValorCompraGravado()) > 0)
            {
              tipo_factura = 0; //TIPO GRAVADO
            }

            if(parseFloat(data.ValorCompraGravado()) > 0)
            {
              data_tipo_factura = 0;
            }
            else {
              data_tipo_factura = 1;
            }

            if(data.EstadoSelector() == true && data_tipo_factura != tipo_factura){
              alertify.alert("El tipo de factura a seleccionar no se parece a la primera que tiene seleccionada.");
              self.EstadoSelector(false);
              return false;
            }
          }
          else if(filas_bcv > 0)
          {
            var primera_fila = ViewModels.data.NotaDebitoCompra.BusquedaComprobanteCompraND()[0];
            var tipo_factura = 1; //TIPO NO GRAVADO
            var data_tipo_factura = 0;
            if(parseFloat(primera_fila.ValorCompraGravado()) > 0)
            {
              tipo_factura = 0; //TIPO GRAVADO
            }

            if(parseFloat(data.ValorCompraGravado()) > 0)
            {
              data_tipo_factura = 0;
            }
            else {
              data_tipo_factura = 1;
            }

            if(data.EstadoSelector() == true && data_tipo_factura != tipo_factura){
              alertify.alert("El tipo de factura a seleccionar no se parece a la primera que tiene seleccionada.");
              self.EstadoSelector(false);
              return false;
            }
          }
        }

        if(filas_mcv > 0)
        {
          var primera_fila = ViewModels.data.NotaDebitoCompra.MiniComprobantesCompraND()[0];
          var serie = primera_fila.SerieDocumento().substring(0, 1);
          // var primer_digito_serie = serie.substring(0, 1);
          var serie_otro = data.SerieDocumento().substring(0, 1);
          if(data.EstadoSelector() == true && (primera_fila.IdTipoDocumento() != data.IdTipoDocumento()
          || serie != serie_otro || primera_fila.IdFormaPago() != data.IdFormaPago()))
          {
            alertify.alert("La factura que quiere Seleccionar No Tiene las mismas caracteristicas de sus facturas ya seleccionadas.");
            self.EstadoSelector(false);
            return false;
          }
        }
        else if(filas_bcv > 0){
          var primera_fila = ViewModels.data.NotaDebitoCompra.BusquedaComprobanteCompraND()[0];
          if(data.EstadoSelector() == true && (primera_fila.IdTipoDocumento() != data.IdTipoDocumento()
          || serie != serie_otro || primera_fila.IdFormaPago() != data.IdFormaPago()))
          {
            alertify.alert("La factura que quiere Seleccionar No Tiene las mismas caracteristicas de sus facturas ya seleccionadas.");
            self.EstadoSelector(false);
            return false;
          }
        }

        if (data.EstadoSelector() == true)
        {
          $(id).addClass('active');
          ViewModels.data.NotaDebitoCompra.BusquedaComprobanteCompraND.push(new BusquedaComprobantesCompraNDModel(objeto));
        }
        else
        {
          $(id).removeClass('active');
          ViewModels.data.NotaDebitoCompra.BusquedaComprobanteCompraND.remove( function (item) { return item.IdComprobanteCompra() == objeto.IdComprobanteCompra(); } )
        }
        self.ActualizarBotonAgregar(event);

      }

    }

    self.ActualizarBotonAgregar = function(event)
    {
      if(event)
      {
        var length = ViewModels.data.NotaDebitoCompra.BusquedaComprobanteCompraND().length;
        if(length > 0)
        {
          $("#BusquedaComprobantesCompraModelND").find("#btn_AgregarComprobantesCompra").prop("disabled", false);
        }
        else {
          $("#BusquedaComprobantesCompraModelND").find("#btn_AgregarComprobantesCompra").prop("disabled", true);
        }

      }
    }

}

BusquedaComprobanteCompraNDModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

}

FiltrosNDModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.ConsultarPorCliente = function (data,event) {
      if(event) {
        if(!$("#BusquedaComprobantesCompraModelND").isValid() ) {//lang, conf, false
          //displayErrors( errors );
          alertify.alert("Por favor asegurese de ingresar bien las fechas.", function(){
            // $("#BusquedaComprobantesCompraModelND").find("#fecha-inicio").focus();
          });
          return false;
        }
        $("#BusquedaComprobantesCompraModelND").find("#btn_AgregarComprobantesCompra").prop("disabled", true);
        ViewModels.data.NotaDebitoCompra.BusquedaComprobanteCompraND([]);
        self.ConsultarComprobantesCompraPorCliente(data,event,self.PostConsultar);
      }
    }

    self.ConsultarComprobantesCompraPorCliente = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data}, mappingIgnore);
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Compra/NotaDebitoCompra/cNotaDebitoCompra/ConsultarComprobantesCompraPorProveedor',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

    self.PostConsultar = function (data,event) {
      if(event) {
        ViewModels.data.NotaDebitoCompra.BusquedaComprobantesCompraND([]);
        ko.utils.arrayForEach(data.resultado, function (item) {
          ViewModels.data.NotaDebitoCompra.BusquedaComprobantesCompraND.push(new BusquedaComprobantesCompraNDModel(item));
        });

        //ELIMINAMOS LAS FACTURAS QUE YA ESTAN EN LA DATA
        var seleccionados = ViewModels.data.NotaDebitoCompra.MiniComprobantesCompraND;
        var filas = seleccionados().length;
        if(filas > 0)
        {
          ko.utils.arrayForEach(seleccionados(), function (objeto) {
            ViewModels.data.NotaDebitoCompra.BusquedaComprobantesCompraND.remove( function (item) { return item.IdComprobanteCompra() == objeto.IdComprobanteCompra(); } )
          });
        }
        // var objeto = ViewModels.data.NotaDebitoCompra.BusquedaComprobantesCompraND()[0];
        // ViewModels.Seleccionar(objeto, event);
      }
    }

    self.ValidarFechaInicio = function(data, event){
      if(event) {
        $(event.target).validate(function(valid, elem) {
           //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
        });
      }
    }

    self.ValidarFechaFin = function(data, event){
      if(event) {
        $(event.target).validate(function(valid, elem) {
           //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
        });
      }
    }

}

MiniComprobantesCompraNDModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.Documento = ko.observable(data.SerieDocumento + "-" + data.NumeroDocumento);
    // self.Documento = ko.observable(self.SerieDocumento() + "-" + self.NumeroDocumento());
    self.IdDocumentoReferencia = ko.observable("");

    self.EliminarComprobanteCompra = function(data, event)
    {
      if(event)
      {
        ViewModels.data.NotaDebitoCompra.MiniComprobantesCompraND.remove( function (item) { return item.IdComprobanteCompra() == data.IdComprobanteCompra(); } );
        // self.EliminarDetalleComrpobanteCompra(data, event);
        ViewModels.data.NotaDebitoCompra.DetallesNotaDebitoCompra([]);

        var tipo_motivo = window.Motivo.Reglas.CantidadFacturas;
        if(tipo_motivo == 1)
        {
          ViewModels.data.NotaDebitoCompra.SumarComprobantesElegidos(event);
          if(ViewModels.data.NotaDebitoCompra.MiniComprobantesCompraND().length > 0)
          {
            // ViewModels.data.NotaDebitoCompra.CalcularPorcentaje(ViewModels.data.NotaDebitoCompra, event);
            ViewModels.data.NotaDebitoCompra.CalcularPorcentaje(ViewModels.data.NotaDebitoCompra, event);
            // ViewModels.data.NotaDebitoCompra.LimpiarPorConcepto(event);
          }
          else {
            ko.mapping.fromJS(ViewModels.data.NotaDebitoCompra.NuevaNotaDebitoCompra, {}, ViewModels.data.NotaDebitoCompra)
          }
        }
        else {
          ViewModels.data.NotaDebitoCompra.CalcularTotales(event);
        }
      }
    }

    self.EliminarDetalleComrpobanteCompra = function(data, event)
    {
      if(event)
      {
        ko.utils.arrayFirst(data.DetallesComprobanteCompra(), function(item2) {
          // ViewModels.data.NotaDebitoCompra.DetallesComprobanteCompra.remove( function (item) { return item.IdDetalleComprobanteCompra() == item2.IdDetalleComprobanteCompra(); } );
          ViewModels.data.NotaDebitoCompra.DetallesNotaDebitoCompra.remove( function (item) { return item.IdReferenciaDCV() == item2.IdDetalleComprobanteCompra(); } );
        });
      }
    }

}
