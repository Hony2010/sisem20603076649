<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalVehiculo" data-bind="
bootstrapmodal : Vehiculo.showVehiculo, show : Vehiculo.showVehiculo, onhiden :  function(){Vehiculo.Hide(window)}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bind="click : Vehiculo.OnClickBtnCerrar" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="panel-title"><span data-bind="text : Vehiculo.MostrarTitulo"></span></h4>
      </div>
      <div class="modal-body">
        <?php echo $view_form_vehiculo; ?>
      </div>
    </div>
  </div>
</div>