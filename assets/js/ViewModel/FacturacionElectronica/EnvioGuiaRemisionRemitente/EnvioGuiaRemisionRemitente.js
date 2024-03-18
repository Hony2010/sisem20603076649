var cantidad_filas = data.data.Numero_Filas;
var filas_seleccionadas = 0;
var option_button = 0;

var cantidad_filas_consultas = 0;
var filas_seleccionadas_consultas = 0;
var option_button_consultas = 0;

var Mapping = Object.assign(
  MappingEnvioGuiaRemisionRemitente
);

Index = function (data) {
  var self = this;
  ko.mapping.fromJS(data, Mapping, self);

  self.Inicializar = function () {
    $(".fecha").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });

    $("#EnviarSunat").prop('disabled', true);
    $("#EnviarSunatConsulta").prop('disabled', true);
  }

  self.SelectorTodo = ko.observable(false);
  self.SelectorTodoConsulta = ko.observable(false);

  self.data.MostarBetaSunat = ko.pureComputed(function () {
    if (self.data.ParametroBetaSUNAT() == 1) {
      $("#nota-beta").show();
    }
    return self.data.ParametroBetaSUNAT();
  }, this);

  self.SeleccionarTodo = function () {
    if (self.SelectorTodo() == true) {
      Models.data.EnvioGuiaRemisionRemitente([]);
      ko.utils.arrayFirst(Models.data.EnviosGuiaRemisionRemitente(), function (item) {
        if (item.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO) {
          var id = "#" + item.IdGuiaRemisionRemitente();
          $(id).addClass('active');
          item.EstadoSelector(true);
          Models.data.EnvioGuiaRemisionRemitente.push(item);
        }
      });
      filas_seleccionadas = cantidad_filas;
      $("#EnviarSunat").prop('disabled', false);
    }
    else {
      Models.data.EnvioGuiaRemisionRemitente([]);
      ko.utils.arrayFirst(Models.data.EnviosGuiaRemisionRemitente(), function (item) {
        var id = "#" + item.IdGuiaRemisionRemitente();
        $(id).removeClass('active');
        item.EstadoSelector(false);
      });
      filas_seleccionadas = 0;
      $("#EnviarSunat").prop('disabled', true);
    }

    if (cantidad_filas == 0) {
      $("#EnviarSunat").prop('disabled', true);
    }

  }

  self.SeleccionarTodoConsulta = function () {
    if (self.SelectorTodoConsulta() == true) {
      Models.data.EnvioGuiaRemisionRemitenteConsulta([]);
      ko.utils.arrayForEach(Models.data.EnviosGuiaRemisionRemitenteConsulta(), function (item) {
        if (item.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO) {
          var id = "#" + item.IdGuiaRemisionRemitente();
          $(id).addClass('active');
          item.EstadoSelector(true);
          Models.data.EnvioGuiaRemisionRemitenteConsulta.push(item);
        }
      });
      filas_seleccionadas_consultas = cantidad_filas_consultas;
      $("#EnviarSunatConsulta").prop('disabled', false);
    }
    else {
      Models.data.EnvioGuiaRemisionRemitenteConsulta([]);
      ko.utils.arrayForEach(Models.data.EnviosGuiaRemisionRemitenteConsulta(), function (item) {
        var id = "#" + item.IdGuiaRemisionRemitente();
        $(id).removeClass('active');
        item.EstadoSelector(false);
      });
      filas_seleccionadas_consultas = 0;
      $("#EnviarSunatConsulta").prop('disabled', true);
    }

    if (cantidad_filas_consultas == 0) {
      $("#EnviarSunatConsulta").prop('disabled', true);
    }

  }

  self.HabilitarButton = function (event) {
    if (event) {
      if (option_button == 0) {
        if (filas_seleccionadas <= 0) {
          $("#EnviarSunat").prop('disabled', true);
        }
        else {
          $("#EnviarSunat").prop('disabled', false);
        }
        $("#BuscadorEnvio").prop('disabled', false);
        $("#loader").hide();
      }
    }
  }

  self.HabilitarButtonCDR = function (event) {
    if (event) {
      if (option_button == 0) {
        if (filas_seleccionadas <= 0) {
          $("#EnviarSunatConsulta").prop('disabled', true);
        }
        else {
          $("#EnviarSunatConsulta").prop('disabled', false);
        }
        $("#BuscadorEnvioConsulta").prop('disabled', false);
        $("#loader").hide();
      }
    }
  }

  self.ActualizarEnvios = function (data, event) {
    if (event) {
      var datajs = Knockout.CopiarObjeto(Models.data.Buscador);
      Models.data.Buscador.BuscarGuiaRemision(datajs, event);

    }
  }

  self.ActualizarPedientes = function (data, event) {
    if (event) {
      // Models.data.BuscadorConsulta.FechaEmision(Models.data.Buscador.FechaEmision());
      var datajs = Knockout.CopiarObjeto(Models.data.BuscadorConsulta);
      Models.data.BuscadorConsulta.BuscarGuiaRemision(datajs, event);
    }
  }

  self.EnviarSUNAT = function (data, event) {
    if (event) {
      $("#loader").show();
      $("#EnviarSunat").prop('disabled', true);
      $("#BuscadorEnvio").prop('disabled', true);
      var objeto = Knockout.CopiarObjeto(Models.data.EnvioGuiaRemisionRemitente);
      option_button = objeto().length;
      ko.utils.arrayFirst(objeto(), function (item) {
        self.EnvioDatosSunat(item, event);
      })

    }
  }

  self.EnvioDatosSunat = function (data, event) {
    if (event) {
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS(data, mappingIgnore);
      var datajs = { Data: JSON.stringify(datajs) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/FacturacionElectronica/cEnvioGuiaRemisionRemitente/EnviarXMLSUNAT',
        success: function (data) {
          if (data != null) {
            console.log("Enviar Sunat");
            //console.log(data);
            if (data.error == "") {
              objeto.IndicadorEstadoCPE(data.estado);
              var old_item = ko.utils.arrayFirst(self.data.EnviosGuiaRemisionRemitente(), function (item) {
                return item.IdGuiaRemisionRemitente() == objeto.IdGuiaRemisionRemitente();
              });
              Models.data.EnviosGuiaRemisionRemitente.replace(old_item, new EnviosGuiaRemisionRemitenteModel(objeto));

              Models.data.EnvioGuiaRemisionRemitente.remove(function (item) { return item.IdGuiaRemisionRemitente() == objeto.IdGuiaRemisionRemitente(); });
              filas_seleccionadas--;
              cantidad_filas--;

              $("#cantidadGuiaRemisionPendienteEnvio").text(data.cantidad);

              old_item.CambiarOpcionesCheck(event);
            }
            else if (data.tipoerror) {
              if (data.tipoerror == 1) {
                objeto.IndicadorEstadoCPE(data.estado);
                var old_item = ko.utils.arrayFirst(self.data.EnviosGuiaRemisionRemitente(), function (item) {
                  return item.IdGuiaRemisionRemitente() == objeto.IdGuiaRemisionRemitente();
                });
                Models.data.EnviosGuiaRemisionRemitente.replace(old_item, new EnviosGuiaRemisionRemitenteModel(objeto));

                filas_seleccionadas--;
                cantidad_filas--;
              }
            }
          }

          CargarNotificacionDetallada(data);

          option_button--;
          self.HabilitarButton(event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          option_button--;
          self.HabilitarButton(event);

          $.ajax({
            type: "POST",
            data: { "Data": { "Data": jqXHR.responseText, "Foot": "EnvioGuiaRemisionRemitente", "Head": "Error Tiempo Respuesta Servidor." } },
            dataType: "json",
            url: SITE_URL + '/Seguridad/cLog/CrearLog',
            success: function (data) {
              console.clear();
            }
          });
        },
        statusCode: {
          500: function (other) {
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


  self.ConsultarSUNAT = function (data, event) {
    if (event) {
      $("#loader").show();
      $("#EnviarSunat").prop('disabled', true);
      $("#BuscadorEnvio").prop('disabled', true);
      var objeto = Knockout.CopiarObjeto(Models.data.EnvioGuiaRemisionRemitenteConsulta);
      option_button_consultas = objeto().length;
      ko.utils.arrayFirst(objeto(), function (item) {
        self.ConsultaCRDSunat(item, event);
      })

    }
  }

  self.ConsultaCRDSunat = function (data, event) {
    if (event) {
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS(data, mappingIgnore);
      var datajs = { Data: JSON.stringify(datajs) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/FacturacionElectronica/cEnvioGuiaRemisionRemitente/ConsultarCDRSunat',
        success: function (data) {
        
          if (data != null) {
            console.log("Consultar CDR Sunat");
            //console.log(data);
            if (data.error == "") {
              objeto.IndicadorEstadoCPE(data.estado);
              /*var old_item = ko.utils.arrayFirst(self.data.EnvioGuiaRemisionRemitenteConsulta(), function (item) {
                return item.IdGuiaRemisionRemitente() == objeto.IdGuiaRemisionRemitente();
              });
              Models.data.EnvioGuiaRemisionRemitenteConsulta.replace(old_item, new EnvioGuiaRemisionRemitenteConsultaModel(objeto));
              */
              Models.data.EnviosGuiaRemisionRemitenteConsulta.remove(function (item) { return item.IdGuiaRemisionRemitente() == objeto.IdGuiaRemisionRemitente(); });
              //Models.data.EnvioGuiaRemisionRemitenteConsulta.remove(function (item) { return item.IdGuiaRemisionRemitente() == objeto.IdGuiaRemisionRemitente(); });
              filas_seleccionadas_consultas--;
              cantidad_filas_consultas--;

              //old_item.CambiarOpcionesCheck(event);
            }
            else if (data.tipoerror) {
              if (data.tipoerror == 1) {
                objeto.IndicadorEstadoCPE(data.estado);
                var old_item = ko.utils.arrayFirst(self.data.EnviosGuiaRemisionRemitenteConsulta(), function (item) {
                  return item.IdGuiaRemisionRemitente() == objeto.IdGuiaRemisionRemitente();
                });

                old_item.IndicadorEstadoCPE(data.estado);
                //Models.data.EnvioGuiaRemisionRemitenteConsulta.replace(old_item, new EnvioGuiaRemisionRemitenteConsultaModel(objeto));
                filas_seleccionadas_consultas--;
                cantidad_filas_consultas--;
              }
            }
          }

          CargarNotificacionDetallada(data);

          option_button_consultas--;
          self.HabilitarButtonCDR(event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          option_button_consultas--;
          self.HabilitarButtonCDR(event);

          $.ajax({
            type: "POST",
            data: { "Data": { "Data": jqXHR.responseText, "Foot": "EnvioGuiaRemisionRemitente", "Head": "Error Tiempo Respuesta Servidor." } },
            dataType: "json",
            url: SITE_URL + '/Seguridad/cLog/CrearLog',
            success: function (data) {
              console.clear();
            }
          });
        },
        statusCode: {
          500: function (other) {
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
