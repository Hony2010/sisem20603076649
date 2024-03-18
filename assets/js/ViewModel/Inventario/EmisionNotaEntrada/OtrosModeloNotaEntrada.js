BusquedaComprobantesVentaModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.EstadoSelector = ko.observable(false);
    self.Documento = ko.observable(data.SerieDocumento + "-" + data.NumeroDocumento);

    self.CambiarEstadoCheck = function (data, event) {
      if(event){
        var id = "#"+data.IdComprobante()+'_tr_comprobanteporcliente';
        var objeto = Knockout.CopiarObjeto(data);
        var cant_items = window.Motivo.Reglas.CantidadFacturas;
        var facturamixta = window.Motivo.Reglas.FacturaMixtas;
        var filas_bcv = ViewModels.data.NotaEntrada.BusquedaComprobanteVenta().length;
        var filas_mcv = ViewModels.data.NotaEntrada.MiniComprobantesVenta().length;

        if (data.EstadoSelector() == true)
        {
          $(id).addClass('active');
          ViewModels.data.NotaEntrada.BusquedaComprobanteVenta.push(new BusquedaComprobantesVentaModel(objeto));
        }
        else
        {
          $(id).removeClass('active');
          ViewModels.data.NotaEntrada.BusquedaComprobanteVenta.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
        }

        self.ActualizarBotonAgregar(event);

      }

    }

    self.ActualizarBotonAgregar = function(event)
    {
      if(event)
      {
        var length = ViewModels.data.NotaEntrada.BusquedaComprobanteVenta().length;
        if(length > 0)
        {
          $("#btn_AgregarComprobantesVenta").prop("disabled", false);
        }
        else {
          $("#btn_AgregarComprobantesVenta").prop("disabled", true);
        }

      }
    }

}

BusquedaComprobanteVentaModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

}

FiltrosModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.ConsultarPorCliente = function (data,event) {
      if(event) {
        if(!$("#BusquedaComprobantesVentaModel").isValid() ) {//lang, conf, false
          //displayErrors( errors );
          alertify.alert("Por favor asegurese de ingresar bien las fechas.", function(){
            // $("#fecha-inicio").focus();
          });
          return false;
        }

        $("#btn_AgregarNotasEntrada").prop("disabled", true);
        ViewModels.data.NotaEntrada.BusquedaComprobanteVenta([]);
        if(self.TipoPersona() == 1)
        {
          self.ConsultarComprobantesVentaPorPersona(data,event,self.PostConsultar);
        }
        else {
          self.ConsultarComprobantesCompraPorPersona(data,event,self.PostConsultar);
        }
      }
    }

    self.ConsultarComprobantesVentaPorPersona = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data}, mappingIgnore);
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Inventario/NotaEntrada/cNotaEntrada/ConsultarComprobantesVentaPorPersona',
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

    self.ConsultarComprobantesCompraPorPersona = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data}, mappingIgnore);
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Inventario/NotaEntrada/cNotaEntrada/ConsultarComprobantesCompraPorPersona',
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
        ViewModels.data.NotaEntrada.BusquedaComprobantesVenta([]);
        ko.utils.arrayForEach(data.resultado, function (item) {
          if(item.DetallesNotaEntrada.length > 0)
          {
            ViewModels.data.NotaEntrada.BusquedaComprobantesVenta.push(new BusquedaComprobantesVentaModel(item));
          }
        });

        //ELIMINAMOS LAS FACTURAS QUE YA ESTAN EN LA DATA
        var seleccionados = ViewModels.data.NotaEntrada.MiniComprobantesVenta;
        var filas = seleccionados().length;
        if(filas > 0)
        {
          ko.utils.arrayForEach(seleccionados(), function (objeto) {
            ViewModels.data.NotaEntrada.BusquedaComprobantesVenta.remove( function (item) { return item.IdComprobante() == objeto.IdComprobante(); } )
          });
        }
        // var objeto = ViewModels.data.NotaEntrada.BusquedaComprobantesVenta()[0];
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

MiniComprobantesVentaModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);

    self.Documento = ko.observable(self.SerieDocumento() + "-" + self.NumeroDocumento());
    self.IdDocumentoReferencia = ko.observable("");

    self.EliminarComprobanteVenta = function(data, event)
    {
      if(event)
      {
        ViewModels.data.NotaEntrada.MiniComprobantesVenta.remove( function (item) { return item.IdComprobante() == data.IdComprobante(); } );
        self.EliminarDetalleNotaEntrada(data, event);

        // var tipo_motivo = window.Motivo.Reglas.CantidadFacturas;
        // if(tipo_motivo == 1)
        // {
        //   ViewModels.data.NotaEntrada.SumarComprobantesElegidos(event);
        //   if(ViewModels.data.NotaEntrada.MiniComprobantesVenta().length > 0)
        //   {
        //     // ViewModels.data.NotaEntrada.CalcularPorcentaje(ViewModels.data.NotaEntrada, event);
        //     ViewModels.data.NotaEntrada.CalcularPorcentaje(ViewModels.data.NotaEntrada, event);
        //     // ViewModels.data.NotaEntrada.LimpiarPorConcepto(event);
        //   }
        //   else {
        //     ko.mapping.fromJS(ViewModels.data.NotaEntrada.NuevaNotaEntrada, {}, ViewModels.data.NotaEntrada)
        //   }
        // }
        // else {
        //   ViewModels.data.NotaEntrada.CalcularTotales(event);
        // }
      }
    }

    self.EliminarDetalleNotaEntrada = function(data, event)
    {
      if(event)
      {
        //PRIMERA VERSION
        ko.utils.arrayFirst(data.DetallesNotaEntrada(), function(item2) {
          ViewModels.data.NotaEntrada.DetallesNotaEntrada.remove( function (item) { return item.IdDetalleComprobante() == item2.IdDetalleComprobante(); } );
        });
      }
    }

}
