
<script>
  var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ModeloVistaGeneracionArchivoCFC.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/GeneracionArchivoCFC.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var Models = new Index(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(Models, koNode);
</script>

<script type="text/javascript">
  Models.Inicializar();
</script>
