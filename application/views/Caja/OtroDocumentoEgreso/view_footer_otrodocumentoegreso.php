<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/MappingCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoEgreso/ModeloOtroDocumentoEgreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoEgreso/VistaModeloOtroDocumentoEgreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoEgreso/VistaModeloRegistroOtroDocumentoEgreso.js"></script>
<script>
    var ViewModels = new VistaModeloRegistroOtroDocumentoEgreso(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
