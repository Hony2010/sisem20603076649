<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
  //var modo=0;//0=Ninguno;1=Nuevo ; 2=Edicion
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/ModeloVistaAccesoRol.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/AccesoRol.js"></script>

<script>
    var Models = new Index(data);
    ko.applyBindingsWithValidation(Models);
</script>

<script>
AccesoKey.AgregarKeyOption("#AccesoRolContent", "#guardar_accesorol", 71);

// var _primera_linea = Models.data.AccesosRol()[0];
// console.log(_primera_linea);
// Models.Seleccionar(_primera_linea,null);

</script>
