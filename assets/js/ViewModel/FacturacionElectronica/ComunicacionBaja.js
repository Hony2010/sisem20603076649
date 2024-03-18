var filas_pendiente = 0;
var filas_generada = 0;
//var fila_activada = false;
var _objeto = null;
var tiempo;

var Mapping = Object.assign(
  MappingComunicacionBaja
);


Index = function (data) {
    var self = this;
    ko.mapping.fromJS(data, Mapping, self);

    self.SelectorTodo = ko.observable(false);

    self.Inicializar = function() {
      $("#fecha-emision").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-emision2").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-inicio").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-fin").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#GenerarBaja").prop('disabled', true);
      $("#EnviarSunat").prop('disabled', true);
    }

    self.data.MostarBetaSunat = ko.pureComputed(function(){
      if(self.data.ParametroBetaSUNAT() == 1)
      {
        $("#nota-beta").show();
      }
      return self.data.ParametroBetaSUNAT();
    }, this);

    self.GenerarBaja = function (data, event) {
      if(event)
      {
        $("#loader").show();
        var objeto = Knockout.CopiarObjeto(Models.data.ComunicacionBaja);
        var datajs = ko.mapping.toJS({"Data" : objeto}, mappingIgnore)
        //var datajs = null;
        datajs = {Data: JSON.stringify(datajs)};
        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/FacturacionElectronica/cComunicacionBaja/GenerarBaja',
                success: function (data) {
                    if (data != null) {
                      console.log("Enviar Sunat");
                      //console.log(data);
                      if(data.error == "")
                      {
                        ko.utils.arrayFirst(objeto(), function(item) {
                            //cantidad_filas--;
                          Models.data.ComunicacionesBaja.remove( function (item2) { return item2.IdComprobanteVenta() == item.IdComprobanteVenta(); } );
                            //Models.data.ComunicacionesBaja.replace(old_item, new ComunicacionesBajaModel(item));
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
                         data: {"Data": {"Data": jqXHR.responseText, "Foot": "ComunicacionBaja", "Head": "Error Tiempo Respuesta Servidor."}},
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

    self.ActualizarPendientes = function(data, event)
    {
      if(event)
      {
        //Models.data.ComunicacionesBaja([]);
        var datajs = Knockout.CopiarObjeto(Models.data.Buscador);

        Models.data.Buscador.BuscarFactura(datajs, event);

      }
    }

    self.ActualizarGenerados = function(data, event)
    {
      if(event)
      {
        //Models.data.ComunicacionesBajaConsulta([]);
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
                url: SITE_URL+'/FacturacionElectronica/cComunicacionBaja/ValidarXML',
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
                url: SITE_URL+'/FacturacionElectronica/cComunicacionBaja/ValidarCDR',
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
