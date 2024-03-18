VistaModeloVerificacionCorrelatividad = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingVenta, self);

  ModeloVerificacionCorrelatividad.call(this,self);
    
  self.InicializarVistaModelo = function (data,event, editar = true) {
    if (event)  {
      AccesoKey.AgregarKeyOption(options.IDForm,"#btnVerificar",TECLA_G);
    }
  }

  self.SeleccionarTodos = function(data, event){
    if (event) {
      var $data = self.DetallesVerificacionCorrelatividad();
      
      ko.utils.arrayFirst($data, function(item) {
        if (self.SelectorTodo() == true) {
          item.IndicadorEstadoCheck(true);
          $("#btnVerificar").prop('disabled',false)
          $(".clickable-row").addClass('active');
        } else {
          item.IndicadorEstadoCheck(false);
          $("#btnVerificar").prop('disabled',true)
          $(".clickable-row").removeClass('active');
        }
      });

    }
  }

  self.OnClickBtnVerificacionCorrelatividad = function (data, event) {
    if (event) {
      var datajs = {}; 
      datajs.Filtros = ko.mapping.toJS(ViewModels.data.Buscador);
      datajs.DetallesVerificacionCorrelatividad = ko.mapping.toJS(self.DetallesVerificacionCorrelatividad);
      var $datajs = {"Data" : datajs};
      $("#loader").show()      
      self.RegistarVerificacionCorrelatividad($datajs , event , function ($data, $event) {
        if ($event) {
          $("#loader").hide();

          if ($data.url) {
            window.location = $data.url;
          } else {
            alertify.alert("HA OCURRIDO UN ERROR","ocurrio un problema, porfavor vuelca a intentarlo");
          }
        }
      })
    }
  }
}
