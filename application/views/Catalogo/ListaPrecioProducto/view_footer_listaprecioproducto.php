<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecioProducto/ModeloListaPrecioProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecioProducto/VistaModeloListaPrecioProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecioProducto/VistaModeloRegistroListaPrecioProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecioProducto/VistaModeloDetalleListaPrecio.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloRegistroListaPrecioProducto(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
