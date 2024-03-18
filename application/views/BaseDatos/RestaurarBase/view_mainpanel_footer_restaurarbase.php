<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/BaseDatos/RestaurarBase.js"></script>

<script>
    var Models = new IndexRestaurarBase(data);
    ko.applyBindingsWithValidation(Models);
</script>
