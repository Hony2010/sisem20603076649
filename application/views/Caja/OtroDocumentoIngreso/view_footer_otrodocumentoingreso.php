<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/MappingCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoIngreso/ModeloOtroDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoIngreso/VistaModeloOtroDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoIngreso/VistaModeloRegistroOtroDocumentoIngreso.js"></script>
<script>
    var ViewModels = new VistaModeloRegistroOtroDocumentoIngreso(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
