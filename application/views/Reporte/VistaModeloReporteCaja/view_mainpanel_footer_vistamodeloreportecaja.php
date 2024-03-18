<script type="text/javascript">

</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Caja/ReporteMovimientoCaja/VistaModeloReporteMovimientoCaja.js"></script>

<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var koNode = document.getElementById('maincontent');
  vistaModeloReporte = {
    ReporteMovimientoCaja: new VistaModeloReporteMovimientoCaja(data.dataReporteMovimientoCaja),
  }
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(vistaModeloReporte, koNode);
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Caja/ContenedorFooter.js"></script>
<script type="text/javascript">
  // RecorrerLista.Agregar("#tab-general");
</script>
