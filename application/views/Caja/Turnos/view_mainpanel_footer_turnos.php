<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/Turno/ModeloTurno.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/Turno/VistaModeloTurno.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/Turnos/ModeloTurnos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/Turnos/VistaModeloTurnos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/MappingTurno.js"></script>

<script>
    var ViewModels = new VistaModeloTurnos(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>

<script type="text/javascript">
AccesoKey.Agregar("btnNuevo", TECLA_N);
RecorrerTabla.Agregar("#TablaConsultaTurnos tbody");
RecorrerPaginador.Agregar("#Paginador");
</script>
