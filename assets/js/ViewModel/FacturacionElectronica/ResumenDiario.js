var filas_pendiente = 0;
var filas_generada = 0;
var filas_seleccionadas = 0;
var _objeto = null;
var cantidad_filas = 0;
var tiempo;

var Mapping = Object.assign(
  MappingResumenDiario
);


Index = function (data) {
    var self = this;
    ko.mapping.fromJS(data, Mapping, self);

    self.Inicializar = function() {
      $("#fecha-emision").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-emision2").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-inicio").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-fin").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#GenerarResumen").prop('disabled', true);

      cantidad_filas = Models.data.ResumenesDiario().length;
    }

    self.SelectorTodo = ko.observable(false);

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
        Models.data.ResumenDiario([]);
        ko.utils.arrayFirst(Models.data.ResumenesDiario(), function(item) {
            if(item.IndicadorEstadoCPE() != ESTADO_CPE.ACEPTADO)
            {
              item.EstadoSelector(true);
              Models.data.ResumenDiario.push(item);
              var id = "#"+ item.IdComprobanteVenta();
              $(id).addClass('active');
            }
          });
        filas_seleccionadas = cantidad_filas;
        $("#GenerarResumen").prop('disabled', false);
      }
      else {
        Models.data.ResumenDiario([]);
        ko.utils.arrayFirst(Models.data.ResumenesDiario(), function(item) {
            item.EstadoSelector(false);
            var id = "#"+ item.IdComprobanteVenta();
            $(id).removeClass('active');
        });
        filas_seleccionadas = 0;
        $("#GenerarResumen").prop('disabled', true);
      }

      if(cantidad_filas == 0){
        $("#GenerarResumen").prop('disabled', true);
      }

    }

    self.GenerarResumen = function (data, event) {
      if(event)
      {
        $("#loader").show();
        var objeto = Knockout.CopiarObjeto(Models.data.ResumenDiario);
        var datajs = ko.mapping.toJS({"Data" : objeto}, mappingIgnore)
        datajs = {Data: JSON.stringify(datajs)};
        //var datajs = null;
        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/FacturacionElectronica/cResumenDiario/GenerarResumen',
                success: function (data) {
                    if (data != null) {
                      console.log("Enviar Sunat");
                      //console.log(data);
                      if(data.error == "")
                      {
                        ko.utils.arrayFirst(objeto(), function(item) {
                            cantidad_filas--;
                          Models.data.ResumenesDiario.remove( function (item2) { return item2.IdComprobanteVenta() == item.IdComprobanteVenta(); } );
                            //Models.data.ResumenesDiario.replace(old_item, new ResumenesDiarioModel(item));
                        });
                        Models.data.Buscador.LimpiarOpciones(data, event);
                      }
                    }

                    $("#loader").hide();
                    CargarNotificacionDetallada(data);
              },
              error : function (jqXHR, textStatus, errorThrown) {
                     //console.log(jqXHR.responseText);
                     $("#loader").hide();
                     $.ajax({
                         type: "POST",
                         data: {"Data": {"Data": jqXHR.responseText, "Foot": "ResumenDiario", "Head": "Error Tiempo Respuesta Servidor."}},
                         dataType: "json",
                         url: SITE_URL+'/Seguridad/cLog/CrearLog',
                         success: function(data)
                         {
                           console.clear();
                         }
                       });
                 },
              statusCode: {
                 500: function() {
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


    /*self.EnviarSunat = function (data, event) {
      if(event)
      {
        $("#loader").show();
        var objeto = Knockout.CopiarObjeto(Models.data.ResumenDiarioConsulta);
        var datajs = ko.mapping.toJS({"Data" : objeto}, mappingIgnore)
        //var datajs = null;
        $.ajax({
                type: 'POST',
                data : datajs,
                url: SITE_URL+'/FacturacionElectronica/cResumenDiario/EnviarSunat',
                success: function (data) {
                    if (data != null) {
                      console.log("Enviar Sunat");
                      //console.log(data);
                      if(data == "")
                      {
                        ko.utils.arrayFirst(objeto(), function(item) {
                            //cantidad_filas--;
                            item.IndicadorEstadoResumenDiario("A");
                            var old_item = ko.utils.arrayFirst(self.data.ResumenesDiarioConsulta(), function(item2) {
                                  return item.IdResumenDiario() == item2.IdResumenDiario();
                              });
                            Models.data.ResumenesDiarioConsulta.replace(old_item, new ResumenesDiarioConsultaModel(item));
                        });
                        Models.data.BuscadorConsulta.LimpiarOpciones(data, event);

                      }
                      else {
                        alertify.alert(data);
                      }
                    }
                    $("#loader").fadeOut("slow");
              },
              error : function (jqXHR, textStatus, errorThrown) {
                     console.log(jqXHR.responseText);
              }
        });

      }
    }*/

    self.ActualizarPendientes = function(data, event)
    {
      if(event)
      {
        //Models.data.ResumenesDiario([]);
        var datajs = Knockout.CopiarObjeto(Models.data.Buscador);

        Models.data.Buscador.BuscarFactura(datajs, event);

      }
    }

    self.ActualizarGenerados = function(data, event)
    {
      if(event)
      {
        //Models.data.ResumenesDiarioConsulta([]);
        Models.data.BuscadorConsulta.FechaEmision(Models.data.Buscador.FechaEmision());
        var datajs = Knockout.CopiarObjeto(Models.data.BuscadorConsulta);

        Models.data.BuscadorConsulta.BuscarFactura(datajs, event);

      }
    }

    self.ValidarXML = function (data, event) {
      if(event)
      {
        $("#loader").show();
        var old_data = data;

        $.ajax({
                type: 'POST',
                dataType: 'json',
                data : {"nombre": old_data.name},
                url: SITE_URL+'/FacturacionElectronica/cResumenDiario/ValidarXML',
                success: function (data) {
                  if(data == '1')
                  {
                    window.location = old_data.url;
                    // window.open(old_data.url,'_blank');
                    console.log("Descarga" + old_data.url);
                  }
                  else {
                    alertify.alert("ADVERTENCIA!", "El archivo xml no se encuentra disponible. Consulte con el administrador.");
                  }
                  $("#loader").hide();
              },
              error : function (jqXHR, textStatus, errorThrown) {
                $("#loader").hide();
                   console.log(jqXHR.responseText);
               }
        });

      }
    }

    self.ValidarCDR = function (data, event) {
      if(event)
      {
        $("#loader").show();
        var old_data = data;

        $.ajax({
                type: 'POST',
                dataType: 'json',
                data : {"nombre": old_data.name},
                url: SITE_URL+'/FacturacionElectronica/cResumenDiario/ValidarCDR',
                success: function (data) {
                  if(data == '1')
                  {
                    window.location = old_data.url;
                    // window.open(old_data.url,'_blank');
                    console.log("Descarga" + old_data.url);
                  }
                  else {
                    alertify.alert("ADVERTENCIA!", "El CDR no se encuentra disponible. Consulte con el administrador.");
                  }
                  $("#loader").hide();
              },
              error : function (jqXHR, textStatus, errorThrown) {
                $("#loader").hide();
                   console.log(jqXHR.responseText);
               }
        });

      }
    }


}
