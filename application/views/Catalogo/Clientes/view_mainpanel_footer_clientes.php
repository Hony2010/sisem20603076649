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

<script type="text/javascript">
  ViewModels.Inicializar();
</script>

<script type="text/javascript">
AccesoKey.Agregar("btnNuevo", TECLA_N);
RecorrerTabla.Agregar("#TablaConsultaClientes tbody");
RecorrerPaginador.Agregar("#Paginador");
</script>

<script type="text/javascript">
var busqueda = document.getElementById('input-text-filtro-cliente');
var table = document.getElementById("TablaConsultaClientes").tBodies[0];

buscaTabla = function(){
  texto = busqueda.value.toLowerCase();
  var r=0;
  while(row = table.rows[r++])
  {
    if ( row.innerText.toLowerCase().indexOf(texto) !== -1 )
      row.style.display = null;
    else
      row.style.display = 'none';
  }
}

busqueda.addEventListener('keyup', buscaTabla);

</script>
