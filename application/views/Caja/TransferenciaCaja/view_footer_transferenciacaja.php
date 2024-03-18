<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/MappingCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/TransferenciaCaja/ModeloTransferenciaCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/TransferenciaCaja/VistaModeloTransferenciaCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/TransferenciaCaja/VistaModeloRegistroTransferenciaCaja.js"></script>
<script>
    var ViewModels = new VistaModeloRegistroTransferenciaCaja(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
