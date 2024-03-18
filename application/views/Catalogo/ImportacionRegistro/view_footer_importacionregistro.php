<script>
    var data=<?php echo json_encode($data); ?>;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ImportacionMasiva/MappingMasivo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ImportacionMasiva/ModeloImportacionMasiva.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ImportacionMasiva/VistaModeloImportacionMasiva.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ImportacionRegistro/VistaModeloImportacionRegistro.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloImportacionRegistro(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
