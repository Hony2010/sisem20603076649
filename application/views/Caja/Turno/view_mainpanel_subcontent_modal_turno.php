<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalTurno"
  data-bind="bootstrapmodal : Turno.showTurno, show : Turno.showTurno, onhiden :  function(){Turno.Hide(window)}" >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bind="click : Turno.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="panel-title"><span data-bind="text : Turno.MostrarTitulo"></span></h4>
            </div>
            <div class="modal-body">
              <?php echo $view_form_turno; ?>
            </div>
      </div>
    </div>
</div>
