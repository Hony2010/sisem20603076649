<script>
var data=<?php echo json_encode($data); ?>

    var url_familia = data.data.FamiliasProducto;
    var url_subfamilia = data.data.SubFamiliasProducto;

    var _combo_familia = $('#combo-familia');
    _combo_familia.empty();
    var _combo_subfamilia = $('#combo-subfamiliaproducto');

    //CARGANDO LOS DATOS EN LOS COMBOS

    $.each(url_familia, function (key, entry) {
      _combo_familia.append($('<option></option>').attr('value', entry.IdFamiliaProducto).text(entry.NombreFamiliaProducto));
    })
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListadoSubFamilia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Servicio.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var Models = new Index(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(Models, koNode);
</script>

<script>
if(Models.data.Servicios().length > 0)
{
  var _primera_linea = Models.data.Servicios()[0];
  Models.Seleccionar(_primera_linea,null);

  var input = ko.toJS(Models.data.Filtros);
  $("#Paginador").paginador(input, Models.ConsultarPorPagina);
}
</script>

<script type="text/javascript">

  $(document).ready(function () {
    $("#FileFoto").click(function(){
      this.value = null;
    });
  });
</script>

<script type="text/javascript">
  AccesoKey.Agregar("btnNuevo", TECLA_N);
  AccesoKey.AgregarKeyOption("#form", "#btn_Grabar", TECLA_G);
  RecorrerTabla.Agregar("#TablaConsultaServicios tbody");
  RecorrerPaginador.Agregar("#Paginador");
</script>
