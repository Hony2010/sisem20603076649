<script>
    var data=<?php echo json_encode($data); ?>;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/MetaVentaProducto/ModeloRegistrarMetaVentaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/MetaVentaProducto/VistaModeloRegistrarMetaVentaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/MetaVentaProducto/VistaModeloMetaVentaProducto.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloRegistrarMetaVentaProducto(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);

</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
