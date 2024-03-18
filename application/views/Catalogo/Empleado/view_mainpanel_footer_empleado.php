<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ModeloVistaEmpleado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Empleado.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var Models = new Index(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(Models, koNode);
</script>

<script>
    var _primera_linea = Models.data.Empleados()[0];
    console.log(_primera_linea);
    _primera_linea.Seleccionar(_primera_linea, window);

    var input = ko.toJS(Models.data.Filtros);
    $("#Paginador").paginador(input, Models.ConsultarPorPagina);
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

  RecorrerTabla.Agregar("#TablaConsultaEmpleados tbody");
  RecorrerPaginador.Agregar("#Paginador");
  $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});

</script>
