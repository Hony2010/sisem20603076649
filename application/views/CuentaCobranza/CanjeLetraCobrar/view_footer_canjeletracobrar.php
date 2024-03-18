<script>
  var data = <?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/CuentaCobranza/MappingCuentaCobranza.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/CuentaCobranza/CanjeLetraCobrar/ModeloCanjeLetraCobrar.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/CuentaCobranza/CanjeLetraCobrar/VistaModeloCanjeLetraCobrar.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/CuentaCobranza/CanjeLetraCobrar/VistaModeloDetalleCanjeLetraCobrar.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/CuentaCobranza/CanjeLetraCobrar/VistaModeloRegistroCanjeLetraCobrar.js"></script>
<script>
  var ViewModels = new VistaModeloRegistroCanjeLetraCobrar(data);
  ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>