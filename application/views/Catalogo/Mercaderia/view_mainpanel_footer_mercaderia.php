<script>
  //DECLARANDO VARIABLES DE SCRIPTS Y DATA JSON PARA LOS COMBOS
  var data=<?php echo json_encode($data); ?>;
  var url_marca = data.data.Marcas;
  var url_modelo = data.data.Modelos;
  var url_familia = data.data.FamiliasProducto;
  var url_subfamilia = data.data.SubFamiliasProducto;

  var _combo_marca = $('#combo-marca');
  _combo_marca.empty();
  var _combo_modelo = $('#combo-modelo');
  var _combo_familia = $('#combo-familia');
  _combo_familia.empty();
  var _combo_subfamilia = $('#combo-subfamiliaproducto');

  //CARGANDO LOS DATOS EN LOS COMBOS
    $.each(url_marca, function (key, entry) {
      _combo_marca.append($('<option></option>').attr('value', entry.IdMarca).text(entry.NombreMarca));
    })
    $.each(url_familia, function (key, entry) {
      _combo_familia.append($('<option></option>').attr('value', entry.IdFamiliaProducto).text(entry.NombreFamiliaProducto));
    })

</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListadoSubFamilia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListadoModelo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Mercaderia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/VistaModeloBonificacionMercaderia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/AutoCompletadoProveedor.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var Models = new Index(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(Models, koNode);
</script>

<script>
var _primera_linea = Models.data.Mercaderias()[0];
console.log(_primera_linea);
Models.Seleccionar(_primera_linea,null);

var input = ko.toJS(Models.data.Filtros);
$("#Paginador").paginador(input, Models.ConsultarPorPagina);

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
  RecorrerTabla.Agregar("#TablaConsultaMercaderias tbody");
  RecorrerPaginador.Agregar("#Paginador");
</script>
