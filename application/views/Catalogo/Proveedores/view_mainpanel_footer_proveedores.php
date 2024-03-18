<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/ModeloProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/VistaModeloProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedores/ModeloProveedores.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedores/VistaModeloProveedores.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloProveedores(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>

<script type="text/javascript">
AccesoKey.Agregar("btnNuevo", TECLA_N);
AccesoKey.AgregarKeyOption("#formproveedor", "#BtnGrabar", TECLA_G);
RecorrerTabla.Agregar("#TablaConsultaProveedores tbody");
RecorrerPaginador.Agregar("#Paginador");
</script>
