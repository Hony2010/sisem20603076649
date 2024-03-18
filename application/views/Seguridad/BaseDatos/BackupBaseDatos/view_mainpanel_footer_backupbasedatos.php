<script>
    var data = <?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/BaseDatos/VistaModeloGenerarBackup.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/BaseDatos/ModeloGenerarBackup.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new IndexGenerarBackup(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>
