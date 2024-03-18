<div class="scrollable scrollbar-macosx">
  <?php //echo $view_subcontent_buscador_turnos; ?>
  <fieldset>
    <table id="TablaConsultaTurnos" class="datalist__table table display table-border" width="100%" data-products="brand">
      <thead>
        <tr>
          <th class="products__id">Código</th>
          <th class="products__title"> Nombre Turno </th>
          <th class="products__title">Horario</th>
          <th class="products__title">Horas de Holgura</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <!-- ko foreach : Turnos -->
        <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdTurno }">
          <td class="text-left" data-bind="text : IdTurno">
            <input type="hidden" data-bind="value : IdTurno">
          </td>
          <td data-bind="text : NombreTurno" ></td>
          <td data-bind="text : HoraInicio()+' - '+HoraFin()"></td>
          <td data-bind="text : HorasHolgura"></td>
          <td class="text-center">
            <button data-bind="click : $root.OnClickBtnEditar , attr : { id : IdTurno() + '_btnEditar' } "
              class="btn btn-sm btn-warning btn-operaciones" title="Editar Turno">
              <span class="glyphicon glyphicon-pencil"></span>
            </button>
            <button data-bind="click : $root.OnClickBtnEliminar"
              class="btn btn-sm btn-danger btn-operaciones" title="Eliminar Turno">
              <span class="glyphicon glyphicon-trash"></span>
            </button>
          </td>
    </tr>
    <!-- /ko -->
  </tbody>
</table>

  </fieldset>
      <?php //echo $view_subcontent_paginacion_turnos; ?>
</div>
