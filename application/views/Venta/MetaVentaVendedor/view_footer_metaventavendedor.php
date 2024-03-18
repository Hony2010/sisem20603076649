<script>
    var data=<?php echo json_encode($data); ?>;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/MetaVentaVendedor/ModeloMetaVentaVendedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/MetaVentaVendedor/VistaModeloMetaVentaVendedor.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloMetaVentaVendedor(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);

</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
