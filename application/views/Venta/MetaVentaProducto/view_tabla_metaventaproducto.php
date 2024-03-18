<fieldset>
  <table id="TablaMetasProducto" class="datalist__table table display table-border">
    <thead>
      <tr>
        <th>CODIGO</th>
        <th>PRODUCTO</th>
        <th>META CANTIDAD</th>
        <th>% COMISION</th>
        <th width="41"></th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : MetasVentaProducto -->
      <tr class="clickable-row" data-bind="event: { click : $root.Seleccionar }">
        <td class="">
          <span data-bind="text: CodigoMercaderia"></span>
        </td>
        <td class="">
          <input type="text" class="form-control formulario" data-bind="
            value: ProductoMeta,
            attr: { id : 'inputProductoMeta_' + IdMetaVentaProducto(), 'data-validation-text-found': ProductoMeta } ,
            event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter, blur: ValidarProductoMeta }" data-validation="autocompletado_producto" data-validation-error-msg="">
        </td>
        <td class="">
          <div class="form-grop">
            <input type="text" class="form-control formulario text-right" data-bind="
            value: MetaCantidad,
            numbertrim: MetaCantidad,
            event: {focus: $root.OnFocus, keydown: $root.OnKeyEnter}">
          </div>
        </td>
        <td class="">
          <div class="form-grop">
            <input type="text" class="form-control formulario text-right" data-bind="
            value: PorcentajeComisionVenta,
            numbertrim: PorcentajeComisionVenta,
            event: {focus: $root.OnFocus, keydown: $root.OnKeyEnter}">
          </div>
        </td>
        <td>
          <button type="button" class="btn btn-danger btn-operaciones" data-bind="event: { click: $root.OnClickBtnRemoverMetaProducto }">X</button>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</fieldset>