<div class="scrollable scrollbar-macosx">
  <?php echo $view_subcontent_buscador_activofijo; ?>
  <fieldset>
    <table id="TablaConsultaActivosFijo" class="datalist__table table display" width="100%" data-products="brand">
      <thead>
        <tr>
          <th class="products__id">Codigo</th>
          <th  class="products__title">Descripcion</th>
          <th class="products__title">Tipo</th>
          <th class="products__title">Modelo</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <!-- ko foreach : ActivosFijo -->
        <tr class="clickable-row" data-bind="click : $root.Seleccionar, attr : { id: IdProducto }">

          <td align="left" data-bind="text : CodigoActivoFijo">
            <input type="hidden" data-bind="value : IdProducto">
          </td>

          <td data-bind="text : NombreProducto" class="text-uppercase"></td>
          <td data-bind="text : NombreTipoActivo" class="text-uppercase"></td>
          <td data-bind="text : NombreModelo" class="text-uppercase"></td>

          <td align="center" >
            <button data-bind="click : $root.Editar , attr : { id : IdProducto() + '_btnEditar' } "
            class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar Activo Fijo">
            <span class="glyphicon glyphicon-pencil"></span>
          </button>
          <button data-bind="click : $root.PreEliminar"
          class="btn btn-sm btn-danger btn-operaciones" data-toogle="tooltip" title="Eliminar Activo Fijo">
          <span class="glyphicon glyphicon-trash"></span>
        </button>
      </td>
    </tr>
    <!-- /ko -->
  </tbody>
</table>
</fieldset>
</div>
