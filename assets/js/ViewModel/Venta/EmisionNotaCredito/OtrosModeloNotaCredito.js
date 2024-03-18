BusquedaComprobantesVentaNCModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.EstadoSelector = ko.observable(false);
    self.Documento = ko.observable(data.SerieDocumento + "-" + data.NumeroDocumento);

    self.CambiarEstadoCheck = function (data, event) {
      if(event){
        var id = "#"+data.IdComprobanteVenta()+'_tr_comprobanteventaporcliente';
        var objeto = Knockout.CopiarObjeto(data);
        var cant_items = window.Motivo.Reglas.CantidadFacturas;
        var facturamixta = window.Motivo.Reglas.FacturaMixtas;
        var filas_bcv = ViewModels.data.NotaCredito.BusquedaComprobanteVentaNC().length;
        var filas_mcv = ViewModels.data.NotaCredito.MiniComprobantesVentaNC().length;
        if(data.EstadoSelector() == true && filas_bcv > 0 && cant_items == 0)
        {
          alertify.alert("Usted Tiene seleccionado un comprobante. El Motivo no permite seleccionar mas.");
          self.EstadoSelector(false);
          return false;
        }

        if(data.EstadoSelector() == true && window.Motivo.Data.IdMotivoNotaCredito == 10)
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

        if(data.EstadoSelector() == true && facturamixta == 0 && (parseFloat(data.ValorVentaGravado()) > 0 && parseFloat(data.ValorVentaNoGravado()) > 0))
        {
          alertify.alert("Para este motivo no se permiten facturas Mixtas.");
          self.EstadoSelector(false);
          return false;
        }
        else if(facturamixta == 0){
          if(filas_mcv > 0)
          {
            var primera_fila = ViewModels.data.NotaCredito.MiniComprobantesVentaNC()[0];
            var tipo_factura = 1; //TIPO NO GRAVADO
            var data_tipo_factura = 0;
            if(parseFloat(primera_fila.ValorVentaGravado()) > 0)
            {
              tipo_factura = 0; //TIPO GRAVADO
            }

            if(parseFloat(data.ValorVentaGravado()) > 0)
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
            var primera_fila = ViewModels.data.NotaCredito.BusquedaComprobanteVentaNC()[0];
            var tipo_factura = 1; //TIPO NO GRAVADO
            var data_tipo_factura = 0;
            if(parseFloat(primera_fila.ValorVentaGravado()) > 0)
            {
              tipo_factura = 0; //TIPO GRAVADO
            }

            if(parseFloat(data.ValorVentaGravado()) > 0)
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
          var primera_fila = ViewModels.data.NotaCredito.MiniComprobantesVentaNC()[0];
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
          var primera_fila = ViewModels.data.NotaCredito.BusquedaComprobanteVentaNC()[0];
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
          ViewModels.data.NotaCredito.BusquedaComprobanteVentaNC.push(new BusquedaComprobantesVentaNCModel(objeto));
        }
        else
        {
          $(id).removeClass('active');
          ViewModels.data.NotaCredito.BusquedaComprobanteVentaNC.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
        }
        self.ActualizarBotonAgregar(event);

      }

    }

    self.ActualizarBotonAgregar = function(event)
    {
      if(event)
      {
        var length = ViewModels.data.NotaCredito.BusquedaComprobanteVentaNC().length;
        if(length > 0)
        {
          $("#BusquedaComprobantesVentaModelNC").find("#btn_AgregarComprobantesVenta").prop("disabled", false);
        }
        else {
          $("#BusquedaComprobantesVentaModelNC").find("#btn_AgregarComprobantesVenta").prop("disabled", true);
        }

      }
    }

}

BusquedaComprobanteVentaNCModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

}

FiltrosNCModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.ConsultarPorCliente = function (data,event) {
      if(event) {
        if(!$("#BusquedaComprobantesVentaModelNC").isValid() ) {//lang, conf, false
          //displayErrors( errors );
          alertify.alert("Por favor asegurese de ingresar bien las fechas.", function(){
            // $("#BusquedaComprobantesVentaModelNC").find("#fecha-inicio").focus();
          });
          return false;
        }
        $("#BusquedaComprobantesVentaModelNC").find("#btn_AgregarComprobantesVenta").prop("disabled", true);
        ViewModels.data.NotaCredito.BusquedaComprobanteVentaNC([]);
        self.ConsultarComprobantesVentaPorCliente(data,event,self.PostConsultar);
      }
    }

    self.ConsultarComprobantesVentaPorCliente = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data}, mappingIgnore);
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Venta/NotaCredito/cNotaCredito/ConsultarComprobantesVentaPorCliente',
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
        ViewModels.data.NotaCredito.BusquedaComprobantesVentaNC([]);
        ko.utils.arrayForEach(data.resultado, function (item) {
          if(item.DetallesComprobanteVenta.length > 0)
          {
            ViewModels.data.NotaCredito.BusquedaComprobantesVentaNC.push(new BusquedaComprobantesVentaNCModel(item));
          }
        });

        //ELIMINAMOS LAS FACTURAS QUE YA ESTAN EN LA DATA
        var seleccionados = ViewModels.data.NotaCredito.MiniComprobantesVentaNC;
        var filas = seleccionados().length;
        if(filas > 0)
        {
          ko.utils.arrayForEach(seleccionados(), function (objeto) {
            ViewModels.data.NotaCredito.BusquedaComprobantesVentaNC.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
          });
        }
        // var objeto = ViewModels.data.NotaCredito.BusquedaComprobantesVentaNC()[0];
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

MiniComprobantesVentaNCModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.Documento = ko.observable(data.SerieDocumento + "-" + data.NumeroDocumento);
    // self.Documento = ko.observable(self.SerieDocumento() + "-" + self.NumeroDocumento());
    self.IdDocumentoReferencia = ko.observable("");

    self.EliminarComprobanteVenta = function(data, event)
    {
      if(event)
      {
        ViewModels.data.NotaCredito.MiniComprobantesVentaNC.remove( function (item) { return item.IdComprobanteVenta() == data.IdComprobanteVenta(); } );
        // self.EliminarDetalleComrpobanteVenta(data, event);
        ViewModels.data.NotaCredito.DetallesNotaCredito([]);

        var tipo_motivo = window.Motivo.Reglas.CantidadFacturas;
        if(tipo_motivo == 1)
        {
          ViewModels.data.NotaCredito.SumarComprobantesElegidos(event);
          if(ViewModels.data.NotaCredito.MiniComprobantesVentaNC().length > 0)
          {
            // ViewModels.data.NotaCredito.CalcularPorcentaje(ViewModels.data.NotaCredito, event);
            ViewModels.data.NotaCredito.CalcularPorcentaje(ViewModels.data.NotaCredito, event);
            // ViewModels.data.NotaCredito.LimpiarPorConcepto(event);
          }
          else {
            ko.mapping.fromJS(ViewModels.data.NotaCredito.NuevaNotaCredito, {}, ViewModels.data.NotaCredito)
          }
        }
        else {
          ViewModels.data.NotaCredito.CalcularTotales(event);
        }
      }
    }

    self.EliminarDetalleComrpobanteVenta = function(data, event)
    {
      if(event)
      {
        ko.utils.arrayFirst(data.DetallesComprobanteVenta(), function(item2) {
          // ViewModels.data.NotaCredito.DetallesComprobanteVenta.remove( function (item) { return item.IdDetalleComprobanteVenta() == item2.IdDetalleComprobanteVenta(); } );
          ViewModels.data.NotaCredito.DetallesNotaCredito.remove( function (item) { return item.IdReferenciaDCV() == item2.IdDetalleComprobanteVenta(); } );
        });
      }
    }

}
