<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/MappingCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/AperturaCaja/ModeloAperturaCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/AperturaCaja/VistaModeloAperturaCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/AperturaCaja/VistaModeloRegistroAperturaCaja.js"></script>
<script>
    var ViewModels = new VistaModeloRegistroAperturaCaja(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
