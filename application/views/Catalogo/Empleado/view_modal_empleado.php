<!-- ko with : $root.data -->
<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalEmpleado"
  data-bind="bootstrapmodal : Empleado.showEmpleado, show : Empleado.showEmpleado, onhiden :  function(){Empleado.Hide(window)}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bind="click : Empleado.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="event:{click: Cerrar}"><span aria-hidden="true">&times;</span></button> -->
        <!-- ko with : $root.data.Empleado -->
        <h4 class="panel-title"><span data-bind="text : MostrarTitulo()"></span></h4>
        <!-- /ko -->
      </div>
      <div class="modal-body">
          <!-- ko with : $root.data.Empleado -->
          <?php echo $view_subcontent_form_empleado; ?>
          <!-- /ko -->
      </div>
    </div>
  </div>
</div>
<!-- /ko -->
