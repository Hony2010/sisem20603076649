<script type="text/javascript">
    var data=<?php echo json_encode($data); ?>    
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/ModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloDireccionCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloVehiculoCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Clientes/ModeloClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Clientes/Alumno.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Clientes/VistaModeloClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloClientes(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>