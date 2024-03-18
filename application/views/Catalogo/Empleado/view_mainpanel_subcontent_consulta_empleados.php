<div class="scrollable scrollbar-macosx">
    <?php echo $view_subcontent_buscador_empleado; ?>
    <fieldset>
      <table id="TablaConsultaEmpleados" class="datalist__table table display table-border" width="100%" data-products="brand">
        <thead>
          <tr>
            <th class="products__id">Código</th>
            <th class="products__title"> Nombre/Razón Social</th>
            <th class="products__title">Cargo</th>
            <th class="products__title">Sede</th>
            <th class="products__title">Estado</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <!-- ko foreach : Empleados -->
          <tr class="clickable-row text-uppercase" data-bind="click : Seleccionar, attr : { id: IdEmpleado }">

            <td align="left" data-bind="text : IdEmpleado">
              <input type="hidden" data-bind="value : IdEmpleado">
            </td>

            <td data-bind="text : RazonSocial"></td>
            <td data-bind="text : NombreRol"></td>
            <td data-bind="text : NombreSede"></td>
            <td data-bind="text : NombreEstado"></td>

            <td align="center" data-bind="attr : { id : IdEmpleado() + '_trButtons' } ">
              <button data-bind="click : Editar , attr : { id : IdEmpleado() + '_btnEditar' } "
              class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar Empleado">
                <span class="glyphicon glyphicon-pencil"></span>
              </button>
              <!--<button data-bind="click : PreEliminar"
              class="btn btn-sm btn-danger" data-toogle="tooltip" title="Eliminar Empleado">
              <span class="glyphicon glyphicon-trash"></span>
              </button>-->
            </td>
            <td align="center" data-bind="attr : { id : IdEmpleado() + '_trButtons' } ">
              <button data-bind="click : PreDarBaja, attr : { id : IdEmpleado() + '_btnBaja', title: mensajeTitle() }, css: PintadoBoton"
              class="btn btn-sm btn-operaciones" data-toogle="tooltip">
                <span class="glyphicon" data-bind="css: Icono"></span>
              </button>
            </td>
          </tr>
          <!-- /ko -->
        </tbody>
      </table>
    </fieldset>
    <?php echo $view_subcontent_paginacion_empleado; ?>
</div>
