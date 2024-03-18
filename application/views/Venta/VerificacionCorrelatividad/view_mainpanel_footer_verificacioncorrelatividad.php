
<script>
  var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/VerificacionCorrelatividad/VistaModeloVerificacionCorrelatividad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/VerificacionCorrelatividad/ModeloVerificacionCorrelatividad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/VerificacionCorrelatividad/VistaModeloDetalleVerificacionCorrelatividad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/VerificacionCorrelatividad/ModeloDetalleVerificacionCorrelatividad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/VerificacionCorrelatividad/VistaModeloRegistroVerificacionCorrelatividad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/MappingVenta.js"></script>
<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloRegistroVerificacionCorrelatividad(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
</script>
