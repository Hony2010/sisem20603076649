var cantidad_filas = data.data.Numero_Filas;
var filas_seleccionadas = 0;
var option_button = 0;

var Mapping = Object.assign(
  MappingPublicacionWeb
);

Index = function (data) {
    var self = this;
    ko.mapping.fromJS(data, Mapping, self);

    self.Inicializar = function() {
      $("#fecha-inicio").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-fin").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#EnviarFTP").prop('disabled', true);
    }

    self.SelectorTodo = ko.observable(false);

    self.SeleccionarTodo = function(){
      if(self.SelectorTodo() == true)
      {
        Models.data.PublicacionWeb([]);
        ko.utils.arrayFirst(Models.data.PublicacionesWeb(), function(item) {
            if(item.IndicadorEstadoPublicacionWeb() == ESTADO_PW.PENDIENTE)
            {
              var id = "#"+ item.IdComprobanteElectronico();
              $(id).addClass('active');
              item.EstadoSelector(true);
              Models.data.PublicacionWeb.push(item);
            }
          });
        filas_seleccionadas = cantidad_filas;
        if(parseInt(Models.data.PublicacionWeb().length) < 1)
        {
          alertify.alert("No es posible seleccionar todos.");
          self.SelectorTodo(false);
        }
        else {
          $("#EnviarFTP").prop('disabled', false);
        }
      }
      else {
        Models.data.PublicacionWeb([]);
        ko.utils.arrayFirst(Models.data.PublicacionesWeb(), function(item) {
            var id = "#"+ item.IdComprobanteElectronico();
            $(id).removeClass('active');
            item.EstadoSelector(false);
        });
        filas_seleccionadas = 0;
        $("#EnviarFTP").prop('disabled', true);
      }

      if(cantidad_filas == 0){
        $("#EnviarFTP").prop('disabled', true);
      }

    }

    self.HabilitarButton = function(event)
    {
      if(event)
      {
        if(option_button == 0)
        {
          if(filas_seleccionadas <= 0){
            $("#EnviarFTP").prop('disabled', true);
          }
          else{
            $("#EnviarFTP").prop('disabled', false);
          }
          $("#BuscadorEnvio").prop('disabled', false);
          $("#loader").hide();
        }
      }
    }

    self.EnviarFTP = function(data, event) {
      if(event) {
        $("#loader").show();
        $("#EnviarFTP").prop('disabled', true);
        $("#BuscadorEnvio").prop('disabled', true);
        var objeto = Knockout.CopiarObjeto(Models.data.PublicacionWeb);
        option_button = objeto().length;
        if(option_button > 0) {
          ko.utils.arrayFirst(objeto(), function(item) {
            self.EnvioDatosFTP(item, event);
          })
        }
        else {
          $("#loader").hide();
          alertify.alert("Debe de Seleccionar un item.");
        }
      }
    }

    self.EnvioDatosFTP = function(data, event) {
      if(event) {
        var objeto = Knockout.CopiarObjeto(data);
        var datajs = ko.mapping.toJS({"Data" : data}, mappingIgnore)
        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/FacturacionElectronica/cPublicacionWeb/EnviarXMLFTP',
                success: function (data) {
                    if (data != null) {                
                      if(data.error == "") {
                        
                        objeto.IndicadorEstadoPublicacionWeb(data.estado);
                        var old_item = ko.utils.arrayFirst(self.data.PublicacionesWeb(), function(item) {
                              return item.IdComprobanteElectronico() == objeto.IdComprobanteElectronico();
                        });

                        Models.data.PublicacionesWeb.replace(old_item ,new PublicacionesWebModel(objeto));

                        Models.data.PublicacionWeb.remove( function (item) { return item.IdComprobanteElectronico() == objeto.IdComprobanteElectronico(); } );
                        filas_seleccionadas--;
                        cantidad_filas--;
                        old_item.CambiarOpcionesCheck(event);
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
                    data: {"Data": {"Data": jqXHR.responseText, "Foot": "PublicacionWeb", "Head": "Error Tiempo Respuesta Servidor."}},
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
                    option_button--;
                    self.HabilitarButton(event);
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
