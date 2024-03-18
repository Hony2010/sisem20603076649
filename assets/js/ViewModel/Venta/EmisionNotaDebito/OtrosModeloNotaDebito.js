BusquedaComprobantesVentaNDModel = function (data) {
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
        var filas_bcv = ViewModels.data.NotaDebito.BusquedaComprobanteVentaND().length;
        var filas_mcv = ViewModels.data.NotaDebito.MiniComprobantesVentaND().length;
        if(data.EstadoSelector() == true && filas_bcv > 0 && cant_items == 0)
        {
          alertify.alert("Usted Tiene seleccionado un comprobante. El Motivo no permite seleccionar mas.");
          self.EstadoSelector(false);
          return false;
        }

        if(data.EstadoSelector() == true && window.Motivo.Data.IdMotivoNotaDebito == 10)
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
            var primera_fila = ViewModels.data.NotaDebito.MiniComprobantesVentaND()[0];
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
            var primera_fila = ViewModels.data.NotaDebito.BusquedaComprobanteVentaND()[0];
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
          var primera_fila = ViewModels.data.NotaDebito.MiniComprobantesVentaND()[0];
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
          var primera_fila = ViewModels.data.NotaDebito.BusquedaComprobanteVentaND()[0];
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
          ViewModels.data.NotaDebito.BusquedaComprobanteVentaND.push(new BusquedaComprobantesVentaNDModel(objeto));
        }
        else
        {
          $(id).removeClass('active');
          ViewModels.data.NotaDebito.BusquedaComprobanteVentaND.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
        }
        self.ActualizarBotonAgregar(event);

      }

    }

    self.ActualizarBotonAgregar = function(event)
    {
      if(event)
      {
        var length = ViewModels.data.NotaDebito.BusquedaComprobanteVentaND().length;
        if(length > 0)
        {
          $("#BusquedaComprobantesVentaModelND").find("#btn_AgregarComprobantesVenta").prop("disabled", false);
        }
        else {
          $("#BusquedaComprobantesVentaModelND").find("#btn_AgregarComprobantesVenta").prop("disabled", true);
        }

      }
    }

}

BusquedaComprobanteVentaNDModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

}

FiltrosNDModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.ConsultarPorCliente = function (data,event) {
      if(event) {
        if(!$("#BusquedaComprobantesVentaModelND").isValid() ) {//lang, conf, false
          //displayErrors( errors );
          alertify.alert("Por favor asegurese de ingresar bien las fechas.", function(){
            // $("#BusquedaComprobantesVentaModelNC").find("#fecha-inicio").focus();
          });
          return false;
        }

        $("#BusquedaComprobantesVentaModelND").find("#btn_AgregarComprobantesVenta").prop("disabled", true);
        ViewModels.data.NotaDebito.BusquedaComprobanteVentaND([]);
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
          url: SITE_URL+'/Venta/NotaDebito/cNotaDebito/ConsultarComprobantesVentaPorCliente',
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
        ViewModels.data.NotaDebito.BusquedaComprobantesVentaND([]);
        ko.utils.arrayForEach(data.resultado, function (item) {
          ViewModels.data.NotaDebito.BusquedaComprobantesVentaND.push(new BusquedaComprobantesVentaNDModel(item));
        });

        //ELIMINAMOS LAS FACTURAS QUE YA ESTAN EN LA DATA
        var seleccionados = ViewModels.data.NotaDebito.MiniComprobantesVentaND;
        var filas = seleccionados().length;
        if(filas > 0)
        {
          ko.utils.arrayForEach(seleccionados(), function (objeto) {
            ViewModels.data.NotaDebito.BusquedaComprobantesVentaND.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
          });
        }
        // var objeto = ViewModels.data.NotaDebito.BusquedaComprobantesVentaND()[0];
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

MiniComprobantesVentaNDModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    // self.Documento = ko.observable(self.SerieDocumento() + "-" + self.NumeroDocumento());
    self.Documento = ko.observable(data.SerieDocumento + "-" + data.NumeroDocumento);
    self.IdDocumentoReferencia = ko.observable("");

    self.EliminarComprobanteVenta = function(data, event)
    {
      if(event)
      {
        ViewModels.data.NotaDebito.MiniComprobantesVentaND.remove( function (item) { return item.IdComprobanteVenta() == data.IdComprobanteVenta(); } );
        // self.EliminarDetalleComrpobanteVenta(data, event);
        ViewModels.data.NotaDebito.DetallesNotaDebito([]);

        var tipo_motivo = window.Motivo.Reglas.CantidadFacturas;
        if(tipo_motivo == 1)
        {
          //Se elimina si son varios

          ViewModels.data.NotaDebito.SumarComprobantesElegidos(event);
          if(ViewModels.data.NotaDebito.MiniComprobantesVentaND().length > 0)
          {
            // ViewModels.data.NotaDebito.CalcularPorcentaje(ViewModels.data.NotaDebito, event);
            ViewModels.data.NotaDebito.CalcularPorcentaje(ViewModels.data.NotaDebito, event);
            // ViewModels.data.NotaDebito.LimpiarPorConcepto(event);
          }
          else {
            ko.mapping.fromJS(ViewModels.data.NotaDebito.NuevaNotaDebito, {}, ViewModels.data.NotaDebito)
          }
        }
        else {

          ViewModels.data.NotaDebito.CalcularTotales(event);
        }
      }
    }

    self.EliminarDetalleComrpobanteVenta = function(data, event)
    {
      if(event)
      {
        ko.utils.arrayFirst(data.DetallesComprobanteVenta(), function(item2) {
          // ViewModels.data.NotaDebito.DetallesNotaDebito.remove( function (item) { return item.IdDetalleComprobanteVenta() == item2.IdDetalleComprobanteVenta(); } );
          ViewModels.data.NotaDebito.DetallesNotaDebito.remove( function (item) { return item.IdReferenciaDCV() == item2.IdDetalleComprobanteVenta(); } );
        });
      }
    }

}
