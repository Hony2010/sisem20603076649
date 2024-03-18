<script>
  //DECLARANDO VARIABLES DE SCRIPTS Y DATA JSON PARA LOS COMBOS
  var data=<?php echo json_encode($data); ?>;

  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Mercaderia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/InventarioInicial/EdicionInventarioInicial.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>

<script>
    var ViewModels = new IndexInventarioInicial(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script>
var _primera_linea = ViewModels.data.InventariosInicial()[0];
ViewModels.Seleccionar(_primera_linea,null);
ViewModels.InicializarVistaModelo(window);
var input = ko.toJS(ViewModels.data.Filtros);
$("#Paginador").paginador(input, ViewModels.ConsultarPorPagina);
</script>
<!-- prueba -->
<script type="text/javascript">

$(document).ready(function () {
  //$.linkedSelect.init();

  //PARA LIMPIAR EL INPUT DE LA FOTO
  $('#FileFoto').click(function(){
    this.value = null;
  });

});

</script>

<script type="text/javascript">
  AccesoKey.Agregar("btnNuevo", TECLA_N);
  AccesoKey.AgregarKeyOption("#form", "#btn_Grabar", TECLA_G);
  RecorrerTabla.Agregar("#TablaConsultaInventariosInicial tbody");
  RecorrerPaginador.Agregar("#Paginador");
</script>
