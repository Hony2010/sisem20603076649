ConsultasComprobanteElectronicoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.FechaFormateada = ko.pureComputed(function(){
      return FechaFormato.FormatearFechaYYYYMMDD(self.FechaEmision());
    }, this);

    self.Icono = ko.pureComputed(function(){
      if(self.SerieDocumento().search(CODIGO_SERIE_BOLETA) != -1)
      {
        if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
          if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO) {
            return "fa-check";
          }
          return "fa-minus";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO){
          return "fa-load";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
          return "fa-check";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO){
          return "fa-times";
        }
        else {
          return "";
        }
      }
      else {
        if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
          return "fa-minus";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO) {
          return "fa-times";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO) {
          return "fa-load";//BUSCAR
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO) {
          return "fa-check";
        }
        else {
          return "";
        }
      }
    }, this);

    self.Color = ko.pureComputed(function(){
      if(self.SerieDocumento().search(CODIGO_SERIE_BOLETA) != -1)
      {
        if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
          if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO) {
            return "green";
          }
          return "orange";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO){
          return "gray";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
          return "green";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO){
          return "red";
        }
        else {
          return "";
        }
      }
      else {
        if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
          return "orange";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO){
          return "red";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO){
          return "gray";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
          return "green";
        }
        else{
          return "";
        }
      }
    }, this);

    self.VistaCheck = ko.pureComputed(function(){
      return self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO ? "visible" : "hidden";
    }, this);

    self.VistaCDR = ko.pureComputed(function(){
      return self.IdTipoDocumento() != ID_TIPO_DOCUMENTO_BOLETA && (self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO || self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO) ? "visible" : "hidden";
    }, this);

    self.EstadoCE = ko.computed(function(){
      if(self.SerieDocumento().search(CODIGO_SERIE_BOLETA) != -1)
      {
        if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
          if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO) {
            //svar mensaje="SI(Advertencia, tiene q enviar el CP como RD)";
            return "Aceptado";
          }
          return "Pend. Envío";//"Pendiente Envío";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO){
          return "En Proceso";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
          return "Aceptado";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO){
          return "Rechazado";
        }
        else {
          return "";
        }
      }
      else {
        if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
          return "Pend. Envío";//"Pendiente Envío";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO){
          return "Rechazado";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO){
          return "En Proceso";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
          return "Aceptado";
        }
        else {
          return "";
        }
      }

    }, this);

    self.CodigoRespuesta = ko.computed(function(){
      if(self.SerieDocumento().search(CODIGO_SERIE_BOLETA) != -1)
      {
        //NONE
        return "";
      }
      else {
        if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
          return "";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
          return "";
        }
        else {
          return self.CodigoError();
        }
      }
    }, this);

    self.DescripcionRespuesta = ko.computed(function(){
      if(self.SerieDocumento().search(CODIGO_SERIE_BOLETA) != -1)
      {
        //NONE
        return "";
      }
      else {
        if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
          return "";
        }
        else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
          return "";
        }
        else {
          return self.DescripcionError();
        }
      }
    }, this);

    self.GenerarPDF = function(data, event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        var _data = Knockout.CopiarObjeto(data);
        var objeto = ko.mapping.toJS( data, mappingIgnore);
        var datajs ={Data: JSON.stringify(objeto)};
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          context: document.body,
          url: SITE_URL+'/FacturacionElectronica/cComprobanteElectronico/ExportarPDF',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                $('#DescargarPDF_iframe').prop('src', "");
                $('#DescargarPDF_iframe').prop('src', data);
                $("#modalPDFGenerado").modal("show");
              }
              else {

              }
              $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            $("#loader").hide();
          }
        });
      }
    }

    self.MostrarXML = function(data, event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        var _data = Knockout.CopiarObjeto(data);
        var datajs = ko.mapping.toJS({"Data" : _data}, mappingIgnore);

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "html",
          context: document.body,
          url: SITE_URL+'/FacturacionElectronica/cComprobanteElectronico/MostrarXML',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                editor.setValue(data);
                setTimeout(function(){
                  editor.refresh();
                }, 200);

                $("#modalXML").modal("show");
              }
              else {

              }
              $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            $("#loader").hide();
          }
        });
      }
    }

    self.DescargarXML = function(data, event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        var _data = Knockout.CopiarObjeto(data);
        var datajs = ko.mapping.toJS({"Data" : _data}, mappingIgnore);

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          context: document.body,
          url: SITE_URL+'/FacturacionElectronica/cComprobanteElectronico/DescargarXML',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                document.location = data;
              }
              else {

              }
              $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            $("#loader").hide();
          }
        });
      }
    }

    self.Seleccionar = function (data,event)  {
      if(event)
      {
        console.log("Seleccionar");
        var id = "#"+data.IdComprobanteVenta();
        $(id).addClass('active').siblings().removeClass('active');

      }
    }

    //PARA ENVIAR EMAIL
    self.ObtenerFilaClienteJSON = function (data, event) {
      if (event) {
        codigo = data.IdCliente;//data.IdPersona;
        url_json = SERVER_URL + URL_JSON_CLIENTES;
        _busqueda = "IdPersona";

        var json = ObtenerJSONCodificadoDesdeURL(url_json);
        var rpta = JSON.search(json, '//*['+_busqueda+'="'+codigo+'"]');

        if (rpta.length > 0)  {
          return rpta[0];
        } else {
          return null;
        }
      }
    }

    self.ActualizarEmailCliente = function (data, event, callback, dataCliente) {
      if (event) {
        dataCliente.Email = data;
        var datajs = {"Data": dataCliente};
        $("#loader").show();

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cCliente/ActualizarEmailCliente',
          success: function (data) {
            $("#loader").hide();
            callback(data,event);
          },
          error :  function (jqXHR, textStatus, errorThrown) {
            var data = {error:{msg:jqXHR.responseText}};
            $("#loader").hide();
            callback(data,event);
          }
        });
      }
    }
    
    self.EnviarEmailCliente = function(data, event)
    {
      if(event)
      {
        var data_objeto = ko.mapping.toJS(data);
        if((data_objeto.SerieDocumento.search(CODIGO_SERIE_BOLETA) >= 0 && data_objeto.IdTipoDocumento == ID_TIPO_DOCUMENTO_BOLETA) || (data_objeto.SerieDocumento.search(CODIGO_SERIE_FACTURA) >= 0 && data_objeto.IdTipoDocumento == ID_TIPO_DOCUMENTO_FACTURA))
        {
          var rpta = self.ObtenerFilaClienteJSON(data_objeto, event);
          if(rpta != null)
          {
            if(data_objeto.NombreArchivoComprobante)
            {
              if(rpta.Email == null || rpta.Email == "")
              {
                alertify.prompt("Validación!", "El cliente no tiene un correo asignado,para continuar debe introducir un Correo Electronico.","", function(evt, value) {
                  self.ActualizarEmailCliente(value, event, function ($data, $event) {
                    if (!$data.error) {
                      self.EnviarEmailCliente(data, event);
                    } else {
                      alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
                    }
                  }, rpta)
                }, function() { }).set('labels', {ok:'Enviar', cancel:'Cancel'});
                return false;
              }
              else {
                data_notify = {};
                data_notify.title = "¿Desea Enviar el CPE por Email al Cliente?";
                data_notify.message = "Se enviará el XML y PDF respectivo.";
                LoadNotificacionEmail(data_notify, function(res){
                  if(res)
                  {
                    if(validarEmail(rpta.Email) == false)
                    {
                      var val_data = {
                        title: "<strong>Validación!</strong>",
                				type: "danger",
                				clase: "notify-danger",
                				message: "El email del cliente es invalido."
                      };
                      CargarNotificacionDetallada(val_data);
                      return false;
                    }
                    rpta.NombreArchivo = data_objeto.NombreArchivoComprobante;
                    rpta.IdComprobanteVenta = data_objeto.IdComprobanteVenta;
                    rpta.SerieDocumento = data_objeto.SerieDocumento;
                    rpta.NumeroDocumento = data_objeto.NumeroDocumento;
                    rpta.Total = data_objeto.Total;
                    rpta.NombreAbreviado = data_objeto.NombreAbreviado;
                    rpta.NombreTipoDocumento = data_objeto.NombreTipoDocumento;
                    rpta.IdTipoDocumento = data_objeto.IdTipoDocumento;
                    self.EnviarMail(rpta, event, function($data, $event){
                      if(!$data.error)
                      {
                        CargarNotificacionDetallada($data);
                      }
                    });
                  }
                });

              }
            }
          }
          else
          {
            console.log("CLIENTE NO ENCONTRADO");
          }

        }

      }
    }

    self.EnviarMail = function(data,event,callback) {
      if(event) {
        var datajs = ko.mapping.toJS({"Data":data});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType : "json",
          url: SITE_URL+'/Venta/cVenta/EnviarEmail',
          success: function (data) {
            callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            var data = {error:{msg:jqXHR.responseText}};
            callback(data,event);
          }
        });
      }
    }

}


BuscadorModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.NumeroDocumento = ko.observable("");
    self.RazonSocial = ko.observable("");

    self.BusquedaTexto = function(data, event){
      if(data.NumeroDocumento() == ""){
        Models.data.Buscador.NumeroDocumento("%");
      }
    }

    self.LimpiarOpciones = function(event){
        if(event)
       {
         filas_seleccionadas = 0;
         Models.data.ConsultaComprobanteElectronico([]);
       }

    }

    self.BuscarFactura =function(data,event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        var _data = Knockout.CopiarObjeto(data);

        if(data.NumeroDocumento() == "")
        {
          _data.NumeroDocumento("%");
        }

        if(data.RazonSocial() == "")
        {
          _data.RazonSocial("%");
        }

        var datajs = ko.mapping.toJS({"Data" : _data}, mappingIgnore);

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/FacturacionElectronica/cConsultaComprobanteElectronico/ConsultarComprobantesVentaElectronico',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                Models.data.ConsultasComprobanteElectronico.removeAll();

                Models.data.ConsultasComprobanteElectronico([]);
                cantidad_filas = 0;
                ko.utils.arrayFirst(data, function(item) {
                    if(item.IndicadorEstadoCPE == ESTADO_CPE.GENERADO){
                      cantidad_filas++;
                    }
                    Models.data.ConsultasComprobanteElectronico.push(new ConsultasComprobanteElectronicoModel(item));
                });
                self.LimpiarOpciones(event);

              }
              else {
                Models.data.ConsultasComprobanteElectronico([]);
              }
              $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            $("#loader").hide();
          }
        });
      }

    }

}

var MappingConsultaComprobanteElectronico = {
    'ConsultasComprobanteElectronico': {
        create: function (options) {
            if (options)
              return new ConsultasComprobanteElectronicoModel(options.data);
            }
    },
    'ConsultaComprobanteElectronico': {
        create: function (options) {
            if (options)
              return new ConsultasComprobanteElectronicoModel(options.data);
            }
    },
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel(options.data);
            }
    }
}
