<script type="text/javascript">

</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaCobranza/ReporteDeudasCliente/VistaModeloReporteDeudasCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaCobranza/ReporteDetalladoCuentasPorCobrar/VistaModeloReporteDetalladoCuentasPorCobrar.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaCobranza/ReporteDocumentosPorCobrar/VistaModeloReporteDocumentosPorCobrar.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaCobranza/ReporteModeloMovimientoCuentasPorCobrar/VistaModeloReporteModeloMovimientoCuentasPorCobrar.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaCobranza/ReporteSaldoPorClientes/VistaModeloReporteSaldoPorClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaCobranza/ReporteCobrosPorCobrador/VistaModeloReporteCobrosPorCobrador.js"></script>

<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var koNode = document.getElementById('maincontent');
  vistaModeloReporte = {
    ReporteDeudasCliente: new IndexReporteDeudasCliente(data.dataReporteDeudasCliente),
    ReporteDetalladoCuentasPorCobrar: new IndexReporteDetalladoCuentasPorCobrar(data.dataReporteDetalladoCuentasPorCobrar),
    ReporteDocumentosPorCobrar: new IndexReporteDocumentosPorCobrar(data.dataReporteDocumentosPorCobrar),
    ReporteModeloMovimientoCuentasPorCobrar: new IndexReporteModeloMovimientoCuentasPorCobrar(data.dataReporteModeloMovimientoCuentasPorCobrar),
    ReporteSaldoPorClientes: new IndexReporteSaldoPorClientes(data.dataReporteSaldoPorClientes),
    ReporteCobrosPorCobrador: new IndexReporteCobrosPorCobrador(data.dataReporteCobrosPorCobrador),
  }
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(vistaModeloReporte, koNode);
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaCobranza/ContenedorFooter.js"></script>
<script type="text/javascript">
  // RecorrerLista.Agregar("#tab-general");
</script>
