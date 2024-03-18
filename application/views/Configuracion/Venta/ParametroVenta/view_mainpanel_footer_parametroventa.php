<script type="text/javascript">
  var data = <?php echo json_encode($data); ?>
</script>

<script src="<?php echo base_url() ?>assets/js/ViewModel/Configuracion/ParametroSistema/VistaModeloParametroSistema.js"></script>

<script type="text/javascript">
  var koNode = document.getElementById('maincontent');
  var ViewModels = new IndexParametroSistema(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>