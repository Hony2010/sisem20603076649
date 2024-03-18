<script>
  var data=<?php echo json_encode($data); ?>
  //DECLARANDO VARIABLES DE SCRIPTS Y DATA JSON PARA LOS COMBOS
  var url_marca = data.data.Marcas;
  var url_modelo =data.data.Modelos;

  var _combo_marca = $('#combo-marca');
  _combo_marca.empty();
  var _combo_modelo = $('#combo-modelo');

  //CARGANDO LOS DATOS EN LOS COMBOS
  $.each(url_marca, function (key, entry) {
    _combo_marca.append($('<option></option>').attr('value', entry.IdMarca).text(entry.NombreMarca));
  })

</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ActivoFijo.js"></script>

<script>
    var koNode = document.getElementById('maincontent');
    var Models = new Index(data);
    ko.cleanNode(koNode);
    ko.applyBindingsWithValidation(Models, koNode);
</script>

<script>
  var _primera_linea = Models.data.ActivosFijo()[0];
  if (_primera_linea !== undefined) {
    console.log(_primera_linea);
    Models.Seleccionar(_primera_linea,null);
  }
</script>

<script type="text/javascript">
  $(document).ready(function () {

  });
</script>

<script type="text/javascript">
  AccesoKey.Agregar("btnNuevo", TECLA_N);
  AccesoKey.AgregarKeyOption("#form", "#btn_Grabar", TECLA_G);
  RecorrerTabla.Agregar("#TablaConsultaActivosFijo tbody");
</script>
