
$(document).ready(function(){

  $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
  $(".año").inputmask({"mask":"9999",positionCaretOnTab : false});

  $("#SelectorUsuarios").click();
  $("#SelectorUsuarios_CPC").click();
  $("#SelectorVendedores_mcpc").click();
  $("#SelectorVendedores_dcpc").click();
  
  $('body').keydown(function(){
    if (event.keyCode == 88) {
      $(".bhoechie-tab-content:visible").find(".excel").click();
    }
  });

  // VALIDADOR DE AUTOCOMPLEADO PRODUCTO

  $.formUtils.addValidator({
    name: 'autocompletado_cliente',
    validatorFunction: function (value, $el) {
      var texto = $el.attr("data-validation-text-found");
      var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
      return resultado;
    },
    errorMessageKey: 'badautocompletado_cliente'
  });

  // INICIALIZAR VISTA MODELO
  vistaModeloReporte.ReporteModeloMovimientoCuentasPorCobrar.Inicializar();
  vistaModeloReporte.ReporteDetalladoCuentasPorCobrar.Inicializar();
  vistaModeloReporte.ReporteDeudasCliente.Inicializar();
  vistaModeloReporte.ReporteDocumentosPorCobrar.Inicializar();
  vistaModeloReporte.ReporteSaldoPorClientes.Inicializar();
  vistaModeloReporte.ReporteCobrosPorCobrador.Inicializar();
});

