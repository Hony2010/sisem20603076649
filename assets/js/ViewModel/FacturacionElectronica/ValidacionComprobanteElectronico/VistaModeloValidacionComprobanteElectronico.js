VistaModeloValidacionComprobanteElectronico = function (data) {
    var self = this;
    ko.mapping.fromJS(data, MappingVenta, self);
    ModeloValidacionComprobanteElectronico.call(this,self);
    self.SelectorTodo = ko.observable(false);

    self.Inicializar = function ()  {
      $("#Exportar").prop('disabled', true);
      $(".fecha").inputmask({"mask": "99/99/9999"});
    }

    self.SeleccionarTodo = function(data,event){
      if (event) {
        if (self.SelectorTodo() == true) {
          self.data.ComprobanteVenta([]);
          ko.utils.arrayFirst(self.data.ComprobantesVenta(), function(item) {
            if (item.Codigo() != '0000') {
              var id = "#"+ item.IdComprobanteVenta();
              $(id).addClass('active');
              item.EstadoSelector(true);
              self.data.ComprobanteVenta.push(item);
            }
          });
          filas_seleccionadas = cantidad_filas;
          $("#Exportar").prop('disabled', false);
        } else {
          self.data.ComprobanteVenta([]);
          ko.utils.arrayFirst(self.data.ComprobantesVenta(), function(item) {
            if (item.Codigo() != '0000') {
              var id = "#"+ item.IdComprobanteVenta();
              $(id).removeClass('active');
              item.EstadoSelector(false);
            }
          });
          filas_seleccionadas = 0;
          $("#Exportar").prop('disabled', true);
        }

        if (cantidad_filas == 0) {
          $("#Exportar").prop('disabled', true);
        }
      }
    }

    self.ConsultarComprobantes = function (data,event) {
      if(event) {
        self.ListarComprobantesVenta(data, event, function($data, $event){
          // console.log($data);
          self.SelectorTodo(false);
          $("#Exportar").prop('disabled', true);
          self.data.ComprobanteVenta([]);
          cantidad_filas = $data.length;
          filas_seleccionadas = 0;
        });
      }
    }

    self.ExportarDataJSON = function(data, event){
      if(event)
      {
        var objeto = ko.mapping.toJS(self.data.ComprobanteVenta);
        self.ExportarVentas(objeto, event, function($data, $event){
          // var file = $('<a href="data:' + $data + '" download="data.json">download JSON</a>');
          // debugger;
          // $(file).click();
          var today  = new Date();
          var optionsFecha = {year: 'numeric', month: '2-digit', day: '2-digit'};
          var optionsHora = {hour: "numeric", minute: "2-digit", second: "2-digit", hour12: false };
          var fechaString = today.toLocaleDateString("en-US", optionsFecha);
          var horaString = today.toLocaleTimeString("en-US", optionsHora);
          var fechaTexto = String(fechaString).replace(/[/]/g, '-');
          var horaTexto = String(horaString).replace(/[:]/g, '-');
          var nombreArchivo =  $data.nombre + "-" + fechaTexto + "-" + horaTexto + $data.extension;
          var link = document.createElement("a");
          link.download = nombreArchivo;//name;
          link.href = $data.file;
          link.click();
        });
      }
    }
    self.ValidarComprobantes = function(data, event) {
      if (event) {
        $("#loader").show();
        $("#BuscadorEnvio").prop('disabled', true);
        var objeto = Knockout.CopiarObjeto(ViewModels.data.ComprobanteVenta);
        option_button = objeto().length;
        ko.utils.arrayFirst(objeto(), function(item) {
          var objetoItem = item;
          self.EnviarComprobanteServidor(item, event, function ($data, $event) {
            if (!$data.error) {

              var old_item = ko.utils.arrayFirst(self.data.ComprobantesVenta(), function(item) {
                    return item.IdComprobanteVenta() == objetoItem.IdComprobanteVenta();
              });

              ViewModels.data.ComprobanteVenta.remove( function (item) { return item.IdComprobanteVenta() == objetoItem.IdComprobanteVenta(); } );


              $('#check_'+old_item.IdComprobanteVenta()).attr("disabled","disabled").parent('.checkbox').remove();
              $('#'+old_item.IdComprobanteVenta()).removeClass('active');
              old_item.Estado($data.Estado);
              old_item.Codigo($data.Codigo);

              old_item.CambiarOpcionesCheck(event);

            } else {
              var valiData = {
                title: "<strong>Validación!</strong>",
                type: "danger",
                clase: "notify-danger",
                message: $data.error.msg
              };
              CargarNotificacionDetallada(valiData);
            }
            option_button--;
            filas_seleccionadas--;
            cantidad_filas--;
            self.HabilitarButton(event);
          });
        });
      }
    }

    self.HabilitarButton = function(event)
    {
      if(event)
      {
        if(option_button == 0)
        {
          $("#BuscadorEnvio").prop('disabled', false);
          $("#loader").hide();
        }
      }
    }
}
