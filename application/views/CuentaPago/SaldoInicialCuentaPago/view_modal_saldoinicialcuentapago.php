<div id="modalDetalleComprobante" class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" data-bind="bootstrapmodal: $root.showDetalleComprobante, show: $root.showDetalleComprobante, onhide : () => { return $root.OnHideModalDetalle($root, window) }">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h3 class="panel-title">Detalle Comprobante</h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <br>
          <form  id="formSaldoInicialCuentaPago" name="formSaldoInicialCuentaPago" role="form" autocomplete="off" >
            <fieldset>
              <div class="" >
                <table class="datalist__table table display table-border grid-detail-body" width="100%" data-products="brand">
                  <thead>
                    <tr>
                      <th class="text-left">Codigo</th>
                      <th class="text-left">Descipcion</th>
                      <th class="text-left">Cantidad</th>
                      <th class="text-left">P.U</th>
                      <th class="text-left">Importe</th>
                      <th width="41"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- ko foreach : DetallesSaldoInicialCuentaPago -->
                    <tr class="clickable-row text-uppercase" data-bind="attr: { id: 'tr_detalle_' + IdDetalleSaldoInicialCuentaPago() }, event{ }">
                      <td class="">
                        <input type="text" class="form-control formulario" data-bind="
                        value: CodigoMercaderia,
                        attr: { id: 'input_CodigoMercaderia_' + IdDetalleSaldoInicialCuentaPago() },
                        event: { focus: OnFocusCodigoMercaderia, keydown: OnKeyEnterCodigoMercaderia}"
                        data-validation="validacion_producto" data-validation-error-msg="Cod. Inválido" data-validation-found="false"  data-validation-text-found="" style="width: 150px;">
                      </td>
                      <td class="">
                        <input type="text" class="form-control formulario" data-bind="
                        value: NombreProducto,
                        attr: { id: 'input_NombreProducto_' + IdDetalleSaldoInicialCuentaPago(), 'data-validation-reference': 'input_CodigoMercaderia_' + IdDetalleSaldoInicialCuentaPago() },
                        event: { focus: OnFocusNombreProducto }"
                        data-validation="autocompletado_producto" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto" style="width: 425px;">
                      </td>
                      <td class="">
                        <input type="text" class="form-control formulario text-right" data-bind="
                        value: Cantidad,
                        attr: { id: 'input_Cantidad' + IdDetalleSaldoInicialCuentaPago(), 'data-cantidad-decimal': DecimalCantidad() },
                        event: { focus: OnFocusCantidad, keydown: $root.OnKeyEnter, focusout: OnFocusOutCantidad },
                        numberdecimal: Cantidad "
                        data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más" style="width: 100px;">
                      </td>
                      <td class="">
                        <input type="text" class="form-control formulario text-right" data-bind="
                        value: PrecioUnitario,
                        attr: { id: 'input_PrecioUnitario' + IdDetalleSaldoInicialCuentaPago(), 'data-cantidad-decimal': DecimalPrecioUnitario() },
                        event: { focus: OnFocusPrecioUnitario, keydown : $root.OnKeyEnter, focusout : OnFocusOutPrecioUnitario },
                        numberdecimal : PrecioUnitario"
                        data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo" style="width: 100px;">
                      </td>
                      <td class="">
                        <input type="text" class="form-control formulario text-right" data-bind="
                        value: SubTotal,
                        attr: { id: 'input_SubTotal' + IdDetalleSaldoInicialCuentaPago()},
                        event: { focus: OnFocusSubtotal, keydown : $root.OnKeyEnter, focusout: OnFocusOutSubTotal, change: OnChangeSubtotal},
                        numbertrim: SubTotal"
                        data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más" style="width: 100px;">
                      </td>
                      <td width="40">
                        <button type="button" class="btn btn-danger btn-consulta glyphicon glyphicon-minus no-tab" data-bind="
                        visible: !UltimoItem(),
                        event: { focus: OnFocusBtnRemove, keydown: $root.OnKeyEnter, click: OnClickBtnOpcion },
                        attr : { id : 'btn_Remove_' + IdDetalleSaldoInicialCuentaPago()}" ></button>
                      </td>
                    </tr>
                    <!-- /ko -->
                  </tbody>
                </table>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
