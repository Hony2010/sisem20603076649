<div class="scrollable scrollbar-macosx">
  <?php echo $view_subcontent_buscador_mercaderia; ?>
  <fieldset>
    <table id="TablaConsultaMercaderias" class="datalist__table table display table-border" width="100%" data-products="brand">
      <thead>
        <tr>
          <th class="products__id">Código</th>
          <th  class="products__title">Descripción</th>
          <th class="products__title">Unidad</th>
          <th class="products__title">Marca</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <!-- ko foreach : Mercaderias -->
        <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdProducto }">

          <td align="left" data-bind="text : CodigoMercaderia">
            <input type="hidden" data-bind="value : IdProducto">
          </td>

          <td data-bind="text : NombreProducto"></td>
          <td data-bind="text : AbreviaturaUnidadMedida"></td>
          <td data-bind="text : NombreMarca"></td>

          <td align="center" >
            <button data-bind="click : $root.Editar , attr : { id : IdProducto() + '_btnEditar' } "
            class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar Mercaderia">
              <span class="glyphicon glyphicon-pencil"></span>
            </button>
            <button data-bind="click : $root.PreEliminar"
            class="btn btn-sm btn-danger btn-operaciones" data-toogle="tooltip" title="Eliminar Mercaderia">
              <span class="glyphicon glyphicon-trash"></span>
            </button>
            <button data-bind="click : $root.OnClickBtnImprimir"
            class="btn btn-sm btn-print btn-consulta" data-toogle="tooltip" title="Imprimir Codigo Barras">
            <span class="glyphicon glyphicon-barcode"></span>
            </button>
          </td>
        </tr>
        <!-- /ko -->
      </tbody>
    </table>
  </fieldset>
    <?php echo $view_subcontent_paginacion_mercaderia; ?>
</div>
