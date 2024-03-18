<script type="text/javascript">

</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/AutoCompletadoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaPago/ReporteDetalladoCuentasPorPagar/VistaModeloReporteDetalladoCuentasPorPagar.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaPago/ReporteDocumentosPorPagar/VistaModeloReporteDocumentosPorPagar.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaPago/ReporteModeloMovimientoCuentasPorPagar/VistaModeloReporteModeloMovimientoCuentasPorPagar.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaPago/ReporteSaldoPorProveedor/VistaModeloReporteSaldoPorProveedor.js"></script>

<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var koNode = document.getElementById('maincontent');
  vistaModeloReporte = {
    ReporteDetalladoCuentasPorPagar: new IndexReporteDetalladoCuentasPorPagar(data.dataReporteDetalladoCuentasPorPagar),
    ReporteDocumentosPorPagar: new IndexReporteDocumentosPorPagar(data.dataReporteDocumentosPorPagar),
    ReporteModeloMovimientoCuentasPorPagar: new IndexReporteModeloMovimientoCuentasPorPagar(data.dataReporteModeloMovimientoCuentasPorPagar),
    ReporteSaldoPorProveedor: new IndexReporteSaldoPorProveedor(data.dataReporteSaldoPorProveedor),
  }
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(vistaModeloReporte, koNode);
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/CuentaPago/ContenedorFooter.js"></script>
<script type="text/javascript">
  // RecorrerLista.Agregar("#tab-general");
</script>
