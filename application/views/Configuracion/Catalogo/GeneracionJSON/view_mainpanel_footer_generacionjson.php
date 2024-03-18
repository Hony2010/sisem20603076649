<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/GeneracionJSON.js"></script>

<script>
    var Models = new IndexGeneracionJSON(data);
    ko.applyBindingsWithValidation(Models);
</script>
