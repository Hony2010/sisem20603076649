<script>
  var base_url = "<?php echo base_url();?>";
  var SITE_URL = "<?php echo site_url(); ?>";
  var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/ModeloVistaUsuario.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ModeloVistaEmpleado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/ModeloVistaAccesoRol.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/ModeloVistaAccesoUsuario.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/AutoCompletadoEmpleados.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/Usuario.js"></script>
<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/AccesoRol.js"></script>--> -->

<script>
    var Models = new Index(data);
    ko.applyBindingsWithValidation(Models);

    $('.multiselect-container div.checkbox').each(function (index) {
	            $(this).click(function (e) {
	                // Prevents the click from bubbling up and hiding the dropdown
	                e.stopPropagation();
	            });
	      });
    //var Models2 = new IndexEmpleado(data);
    //ko.applyBindings(Models2, document.getElementById("modalEmpleado"));
</script>

<script>
var _primera_linea = Models.data.Usuarios()[0];
console.log(_primera_linea);
//debugger;
//var event2 = new Event('build');
_primera_linea.Seleccionar(_primera_linea, window);

//$("#NombreEmpleado").easyAutocomplete(new optionsAutoCompletadoEmpleado("#NombreEmpleado", Models.cargarPersona));
</script>
<!-- prueba -->
<script type="text/javascript">
  $(document).ready(function () {
      //$("#NombreEmpleado").prop("disabled", "true");
      $("#FileFoto").click(function(){
        this.value = null;
      });
  });

  AccesoKey.AgregarKeyOption("#principal", "#btnNuevo", 78);
  AccesoKey.AgregarKeyOption("#ContentUsuario", "#btn_GrabarUsuario", 71);
  // AccesoKey.AgregarKeyOption("#modalUsuario", "#AgregarEmpleado", 78);
  // AccesoKey.AgregarKeyOption("#modalEmpleado", "#FileFoto", 78);


</script>
