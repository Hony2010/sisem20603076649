<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Vehiculo/ModeloVehiculo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Vehiculo/VistaModeloVehiculo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Vehiculos/ModeloVehiculos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Vehiculos/VistaModeloVehiculos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Vehiculos/MappingVehiculo.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloVehiculos(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>

<script type="text/javascript">
AccesoKey.Agregar("btnNuevo", TECLA_N);
RecorrerTabla.Agregar("#TablaConsultaVehiculos tbody");
RecorrerPaginador.Agregar("#Paginador");
</script>

<script type="text/javascript">
var busqueda = document.getElementById('input-text-filtro-vehiculo');
var table = document.getElementById("TablaConsultaVehiculos").tBodies[0];

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
