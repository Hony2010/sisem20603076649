VistaModeloDetalleVerificacionCorrelatividad = function (data,base) {
    var self = this;
    var cabecera = base;
    ko.mapping.fromJS(data, MappingVenta , self);
    ModeloDetalleVerificacionCorrelatividad.call(this,self);

    self.InicializarVistaModelo =  function (event) {
      if(event) {

      }
    }

    self.Seleccionar = function (data, event) {
        if (event) {
            var id = "#"+data.IdCorrelativoDocumento()+'_tr';
            if (data.IndicadorEstadoCheck() == true) {
              $(id).addClass('active');
            } else {
              $(id).removeClass('active');
            }
        }
    }

    self.RadioBtnSeleccionar = function (data, event) {
        if (event) {
            var $data = ko.mapping.toJS(ViewModels.data.VerificacionCorrelatividad.DetallesVerificacionCorrelatividad);
            var totalData = $data.length;
            var totalSeleccionados = 0;
            
            $data.forEach(function(entry, key) {
                if (entry.IndicadorEstadoCheck == true) {
                    totalSeleccionados ++ ;
                }
            });

            if (totalSeleccionados == totalData) {
                ViewModels.data.VerificacionCorrelatividad.SelectorTodo(true);
            } else {
                ViewModels.data.VerificacionCorrelatividad.SelectorTodo(false);
            }

            if (totalSeleccionados > 0) {
                $("#btnVerificar").prop('disabled',false)
            } else {
                $("#btnVerificar").prop('disabled',true)
            }

           self.Seleccionar(data, event);
        }
    }
}
