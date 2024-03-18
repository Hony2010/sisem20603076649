<div class="scrollable scrollbar-macosx">
    <?php echo $view_subcontent_buscador_servicio; ?>
    <fieldset>
      <table id="TablaConsultaServicios" class="datalist__table table display table-border" width="100%" data-products="brand">
        <thead>
          <tr>
            <th class="products__id">Código</th>
            <th  class="products__title">Descripción</th>
            <th class="products__title">Tipo</th>
            <th class="products__title">Sub Famlia</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <!-- ko foreach : Servicios -->
          <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdProducto }">

            <td align="left" data-bind="text : CodigoServicio">
              <input type="hidden" data-bind="value : IdProducto">
            </td>

            <td data-bind="text : NombreProducto"></td>
            <td data-bind="text : NombreTipoServicio"></td>
            <td data-bind="text : NombreSubFamiliaProducto"></td>

            <td align="center" >
              <button data-bind="click : $root.Editar , attr : { id : IdProducto() + '_btnEditar' } "
              class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar Servicio">
                <span class="glyphicon glyphicon-pencil"></span>
              </button>
              <button data-bind="click : $root.PreEliminar"
              class="btn btn-sm btn-danger btn-operaciones" data-toogle="tooltip" title="Eliminar Servicio">
                <span class="glyphicon glyphicon-trash"></span>
              </button>
            </td>
          </tr>
          <!-- /ko -->
        </tbody>
      </table>
    </fieldset>
    <?php echo $view_subcontent_paginacion_servicio; ?>
</div>
