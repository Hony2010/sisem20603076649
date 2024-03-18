<script>
    var data = <?php echo json_encode($data); ?>;
    var cantidad_filas = data.data.NumeroFilas;
    var filas_seleccionadas = 0;
    var option_button = 0;
    
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ValidacionComprobanteElectronico/MappingVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ValidacionComprobanteElectronico/ModeloValidacionComprobanteElectronico.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ValidacionComprobanteElectronico/VistaModeloValidacionComprobanteElectronico.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ValidacionComprobanteElectronico/VistaModeloValidacionComprobantes.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloValidacionComprobanteElectronico(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
