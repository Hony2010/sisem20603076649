
$(document).ready(function(){

  $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
  $(".año").inputmask({"mask":"9999",positionCaretOnTab : false});

  $("#SelectorUsuarios").click();
  
  $('body').keydown(function(){
    if (event.keyCode == 88) {
      $(".bhoechie-tab-content:visible").find(".excel").click();
    }
  });

  // VALIDADOR DE AUTOCOMPLEADO PRODUCTO

  $.formUtils.addValidator({
    name: 'autocompletado_proveedor',
    validatorFunction: function (value, $el) {
      var texto = $el.attr("data-validation-text-found");
      var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
      return resultado;
    },
    errorMessageKey: 'badautocompletado_proveedor'
  });

  // INICIALIZAR VISTA MODELO
  vistaModeloReporte.ReporteModeloMovimientoCuentasPorPagar.Inicializar();
  vistaModeloReporte.ReporteDetalladoCuentasPorPagar.Inicializar();
  vistaModeloReporte.ReporteDocumentosPorPagar.Inicializar();
  vistaModeloReporte.ReporteSaldoPorProveedor.Inicializar();
});

