<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/MappingCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/CierreCaja/ModeloCierreCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/CierreCaja/VistaModeloCierreCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/CierreCaja/VistaModeloRegistroCierreCaja.js"></script>
<script>
    var ViewModels = new VistaModeloRegistroCierreCaja(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
