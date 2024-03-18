<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecio/ModeloListaPrecio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecio/ModeloDetalleListaPrecio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecio/VistaModeloListaPrecio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecio/VistaModeloDetalleListaPrecio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecio/VistaModeloListaPrecio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaPrecio/VistaModeloRegistroListaPrecio.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloRegistroListaPrecio(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
