<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/Seguridad.js"></script>

<script>
    var Models = new Index(data);
    ko.applyBindingsWithValidation(Models);
</script>
<script type="text/javascript">
  $("#usuario").focus();
</script>
