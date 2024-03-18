var cantidad_filas = data.data.Numero_Filas;
var filas_seleccionadas = 0;
var option_button = 0;

var cantidad_filas_consultas = 0;
var filas_seleccionadas_consultas = 0;
var option_button_consultas = 0;

var Mapping = Object.assign(
  MappingEnvioFactura
);

Index = function (data) {
    var self = this;
    ko.mapping.fromJS(data, Mapping, self);

    self.Inicializar = function() {
      $("#fecha-inicio").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-fin").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-inicioconsulta").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-finconsulta").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#EnviarSunat").prop('disabled', true);
      $("#EnviarSunatConsulta").prop('disabled', true);
    }

    self.SelectorTodo = ko.observable(false);
    self.SelectorTodoConsulta = ko.observable(false);

    self.data.MostarBetaSunat = ko.pureComputed(function(){
      if(self.data.ParametroBetaSUNAT() == 1)
      {
        $("#nota-beta").show();
      }
      return self.data.ParametroBetaSUNAT();
    }, this);

    self.SeleccionarTodo = function(){
      if(self.SelectorTodo() == true)
      {
        Models.data.EnvioFactura([]);
        ko.utils.arrayFirst(Models.data.EnviosFactura(), function(item) {
            if(item.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO)
            {
              var id = "#"+ item.IdComprobanteVenta();
              $(id).addClass('active');
              item.EstadoSelector(true);
              Models.data.EnvioFactura.push(item);
            }
          });
        filas_seleccionadas = cantidad_filas;
        $("#EnviarSunat").prop('disabled', false);
      }
      else {
        Models.data.EnvioFactura([]);
        ko.utils.arrayFirst(Models.data.EnviosFactura(), function(item) {
            var id = "#"+ item.IdComprobanteVenta();
            $(id).removeClass('active');
            item.EstadoSelector(false);
        });
        filas_seleccionadas = 0;
        $("#EnviarSunat").prop('disabled', true);
      }

      if(cantidad_filas == 0){
        $("#EnviarSunat").prop('disabled', true);
      }

    }

    self.SeleccionarTodoConsulta = function(){
      if(self.SelectorTodoConsulta() == true)
      {
        Models.data.EnvioFacturaConsulta([]);
        ko.utils.arrayFirst(Models.data.EnviosFacturaConsulta(), function(item) {
            if(item.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO)
            {
              var id = "#"+ item.IdComprobanteVenta();
              $(id).addClass('active');
              item.EstadoSelector(true);
              Models.data.EnvioFacturaConsulta.push(item);
            }
          });
        filas_seleccionadas_consultas = cantidad_filas_consultas;
        $("#EnviarSunatConsulta").prop('disabled', false);
      }
      else {
        Models.data.EnvioFacturaConsulta([]);
        ko.utils.arrayFirst(Models.data.EnviosFacturaConsulta(), function(item) {
            var id = "#"+ item.IdComprobanteVenta();
            $(id).removeClass('active');
            item.EstadoSelector(false);
        });
        filas_seleccionadas_consultas = 0;
        $("#EnviarSunatConsulta").prop('disabled', true);
      }

      if(cantidad_filas_consultas == 0){
        $("#EnviarSunatConsulta").prop('disabled', true);
      }

    }

    self.HabilitarButton = function(event)
    {
      if(event)
      {
        if(option_button == 0)
        {
          if(filas_seleccionadas <= 0){
            $("#EnviarSunat").prop('disabled', true);
          }
          else{
            $("#EnviarSunat").prop('disabled', false);
          }
          $("#BuscadorEnvio").prop('disabled', false);
          $("#loader").hide();
        }
      }
    }

    self.HabilitarButtonCDR = function(event)
    {
      if(event)
      {
        if(option_button == 0)
        {
          if(filas_seleccionadas <= 0){
            $("#EnviarSunatConsulta").prop('disabled', true);
          }
          else{
            $("#EnviarSunatConsulta").prop('disabled', false);
          }
          $("#BuscadorEnvioConsulta").prop('disabled', false);
          $("#loader").hide();
        }
      }
    }

    self.ActualizarEnvios = function(data, event)
    {
      if(event)
      {
        var datajs = Knockout.CopiarObjeto(Models.data.Buscador);
        Models.data.Buscador.BuscarFactura(datajs, event);

      }
    }

    self.ActualizarPedientes = function(data, event)
    {
      if(event)
      {
        // Models.data.BuscadorConsulta.FechaEmision(Models.data.Buscador.FechaEmision());
        var datajs = Knockout.CopiarObjeto(Models.data.BuscadorConsulta);
        Models.data.BuscadorConsulta.BuscarFactura(datajs, event);
      }
    }

    self.EnviarSUNAT = function(data, event)
    {
      if(event)
      {
        $("#loader").show();
        $("#EnviarSunat").prop('disabled', true);
        $("#BuscadorEnvio").prop('disabled', true);
        var objeto = Knockout.CopiarObjeto(Models.data.EnvioFactura);
        option_button = objeto().length;
        ko.utils.arrayFirst(objeto(), function(item) {
          self.EnvioDatosSunat(item, event);
        })

      }
    }

    self.EnvioDatosSunat = function(data, event)
    {
      if(event)
      {
        var objeto = Knockout.CopiarObjeto(data);
        var datajs = ko.mapping.toJS(data, mappingIgnore);
        var datajs = {Data: JSON.stringify(datajs)};
        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/FacturacionElectronica/cEnvioFactura/EnviarXMLSUNAT',
                success: function (data) {
                    if (data != null) {
                      console.log("Enviar Sunat");
                      //console.log(data);
                      if(data.error == "")
                      {
                        objeto.IndicadorEstadoCPE(data.estado);
                        var old_item = ko.utils.arrayFirst(self.data.EnviosFactura(), function(item) {
                              return item.IdComprobanteVenta() == objeto.IdComprobanteVenta();
                        });
                        Models.data.EnviosFactura.replace(old_item ,new EnviosFacturaModel(objeto));

                        Models.data.EnvioFactura.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } );
                        filas_seleccionadas--;
                        cantidad_filas--;

                        $("#cantidadFacturaPendienteEnvio").text(data.cantidad);

                        old_item.CambiarOpcionesCheck(event);
                      }
                      else if(data.tipoerror)
                      {
                        if(data.tipoerror == 1){
                          objeto.IndicadorEstadoCPE(data.estado);
                          var old_item = ko.utils.arrayFirst(self.data.EnviosFactura(), function(item) {
                            return item.IdComprobanteVenta() == objeto.IdComprobanteVenta();
                          });
                          Models.data.EnviosFactura.replace(old_item ,new EnviosFacturaModel(objeto));

                          filas_seleccionadas--;
                          cantidad_filas--;
                        }
                      }
                    }

                    CargarNotificacionDetallada(data);

                    option_button--;
                    self.HabilitarButton(event);
              },
              error : function (jqXHR, textStatus, errorThrown) {
                option_button--;
                self.HabilitarButton(event);

                $.ajax({
                    type: "POST",
                    data: {"Data": {"Data": jqXHR.responseText, "Foot": "EnvioFactura", "Head": "Error Tiempo Respuesta Servidor."}},
                    dataType: "json",
                    url: SITE_URL+'/Seguridad/cLog/CrearLog',
                    success: function(data)
                    {
                      console.clear();
                    }
                  });
                 },
              statusCode: {
                 500: function(other) {
                     data.title = "<strong>Ocurrio un error.</strong>";
             			   data.type = "warning";
             			   data.clase = "notify-conection";
             			   data.message = "El tiempo de respuesta demoro.";
                     CargarNotificacionDetallada(data);
                }
              }

        });

      }
    }


    self.ConsultarSUNAT = function(data, event)
    {
      if(event)
      {
        $("#loader").show();
        $("#EnviarSunat").prop('disabled', true);
        $("#BuscadorEnvio").prop('disabled', true);
        var objeto = Knockout.CopiarObjeto(Models.data.EnvioFacturaConsulta);
        option_button_consultas = objeto().length;
        ko.utils.arrayFirst(objeto(), function(item) {
          self.ConsultaCRDSunat(item, event);
        })

      }
    }

    self.ConsultaCRDSunat = function(data, event)
    {
      if(event)
      {
        var objeto = Knockout.CopiarObjeto(data);
        var datajs = ko.mapping.toJS(data, mappingIgnore);
        var datajs = {Data: JSON.stringify(datajs)};
        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/FacturacionElectronica/cEnvioFactura/ConsultarCDRSunat',
                success: function (data) {
                    if (data != null) {
                      console.log("Consultar CDR Sunat");
                      //console.log(data);
                      if(data.error == "")
                      {
                        objeto.IndicadorEstadoCPE(data.estado);
                        var old_item = ko.utils.arrayFirst(self.data.EnviosFacturaConsulta(), function(item) {
                              return item.IdComprobanteVenta() == objeto.IdComprobanteVenta();
                        });
                        Models.data.EnviosFacturaConsulta.replace(old_item ,new EnviosFacturaConsultaModel(objeto));

                        Models.data.EnvioFacturaConsulta.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } );
                        Models.data.EnviosFacturaConsulta.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } );
                        filas_seleccionadas_consultas--;
                        cantidad_filas_consultas--;

                        old_item.CambiarOpcionesCheck(event);
                      }
                      else if(data.tipoerror)
                      {
                        if(data.tipoerror == 1){
                          objeto.IndicadorEstadoCPE(data.estado);
                          var old_item = ko.utils.arrayFirst(self.data.EnviosFacturaConsulta(), function(item) {
                            return item.IdComprobanteVenta() == objeto.IdComprobanteVenta();
                          });
                          Models.data.EnviosFacturaConsulta.replace(old_item ,new EnviosFacturaConsultaModel(objeto));

                          filas_seleccionadas_consultas--;
                          cantidad_filas_consultas--;
                        }
                      }
                    }

                    CargarNotificacionDetallada(data);

                    option_button_consultas--;
                    self.HabilitarButtonCDR(event);
              },
              error : function (jqXHR, textStatus, errorThrown) {
                option_button_consultas--;
                self.HabilitarButtonCDR(event);

                $.ajax({
                    type: "POST",
                    data: {"Data": {"Data": jqXHR.responseText, "Foot": "EnvioFactura", "Head": "Error Tiempo Respuesta Servidor."}},
                    dataType: "json",
                    url: SITE_URL+'/Seguridad/cLog/CrearLog',
                    success: function(data)
                    {
                      console.clear();
                    }
                  });
                 },
              statusCode: {
                 500: function(other) {
                     data.title = "<strong>Ocurrio un error.</strong>";
             			   data.type = "warning";
             			   data.clase = "notify-conection";
             			   data.message = "El tiempo de respuesta demoro.";
                     CargarNotificacionDetallada(data);
                }
              }

        });

      }
    }
}
