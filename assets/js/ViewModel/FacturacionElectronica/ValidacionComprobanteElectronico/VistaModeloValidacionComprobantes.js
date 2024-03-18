VistaModeloValidacionComprobantes = function (data) {

    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.Inicializar = function ()  {

    }

    self.TotalComprobante = ko.computed(function(){
      var resultado="";
      var total = accounting.formatNumber(self.Total(), NUMERO_DECIMALES_VENTA);
      resultado = total;
      return resultado;
    },this);

    self.EstadoSelector = ko.observable(false);

    self.CambiarEstadoCheck = function (data, event) {
      if(event){
        var id = "#"+ data.IdComprobanteVenta();

        var objeto = ko.mapping.toJS(data);//Knockout.CopiarObjeto(data);
        if (data.EstadoSelector() == true)
        {
          $(id).addClass('active');
          ViewModels.data.ComprobanteVenta.push(new VistaModeloValidacionComprobantes(objeto));
          filas_seleccionadas++;
        }
        else
        {
          $(id).removeClass('active');
          ViewModels.data.ComprobanteVenta.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta; } )
          filas_seleccionadas--;
        }

        self.CambiarOpcionesCheck(event);
      }

    }

    self.CambiarOpcionesCheck = function(event)
    {
      if(event)
      {
        if(cantidad_filas == filas_seleccionadas){
          $("#SelectorTodo").prop('checked', true);
        }
        else {
          $("#SelectorTodo").prop('checked', false);
        }
        
        if (ViewModels.data.ComprobanteVenta().length == 0) {
          $("#SelectorTodo").prop('checked', false);
        }

      }
    }
}
