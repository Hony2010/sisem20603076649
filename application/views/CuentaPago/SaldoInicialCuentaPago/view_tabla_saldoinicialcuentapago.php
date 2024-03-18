<form  id="formSaldoInicialCuentaPago" name="formSaldoInicialCuentaPago" role="form" autocomplete="off" >
  <fieldset>
    <div class="" style="width: 100%; height: 100%; overflow: auto; min-height: 400px;">
      <table class="datalist__table table display table-border" width="100%" data-products="brand">
        <thead>
          <tr>
            <th class="text-left">Proveedor</th>
            <th class="text-left">Tipo Doc.</th>
            <th class="text-left">Serie</th>
            <th class="text-left">Documento</th>
            <th class="text-left">F. Emision</th>
            <th class="text-left">F. Vencimiento</th>
            <th class="text-left">Moneda</th>
            <th class="text-right">M. Original</th>
            <th class="text-right">S. Inicial</th>
            <th class="text-right">T. C.</th>
            <th width="41"></th>
            <th width="41"></th>
            <th width="41"></th>
            <th width="41"></th>
          </tr>
        </thead>
        <tbody>
          <!-- ko foreach : SaldosInicialesCuentaPago -->
          <tr class="clickable-row text-uppercase" data-bind="attr: { id: IdSaldoInicialCuentaPago }, event{ keyup: $root.OnEscFilaSaldoInicial }">
            <td class="" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: RazonSocialProveedor(),
              visible: VisibleSpan,
              attr: { id: 'spanRazonSocialProveedor_' + IdSaldoInicialCuentaPago() }" style="width: 300px;"></span>
              <input type="text" class="form-control formulario" data-bind="
              value: RazonSocialProveedor(),
              visible: VisibleInput,
              attr: { id : 'inputRazonSocialProveedor_' + IdSaldoInicialCuentaPago(), 'data-validation-text-found': RazonSocialProveedor } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter, blur: ValidarProveedor }"
              data-validation="autocompletado_proveedor" data-validation-error-msg="" style="width: 300px;">
            </td>
            <td class="" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: TipoDocumento,
              visible: VisibleSpan,
              attr: { id: 'spanTipoDocumento_' + IdSaldoInicialCuentaPago() }" style="width: 80px;"></span>
              <select class="form-control formulario" data-bind="
              value: IdTipoDocumento,
              options: TiposDocumento,
              optionsValue: 'IdTipoDocumento' ,
              optionsText: 'NombreAbreviado',
              visible: VisibleInput,
              attr: { id : 'inputTipoDocumento_' + IdSaldoInicialCuentaPago() } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter }" style="width: 80px;"></select>
            </td>
            <td class="" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: SerieDocumento,
              visible: VisibleSpan,
              attr: { id: 'spanSerieDocumento_' + IdSaldoInicialCuentaPago() }" style="width: 80px;"></span>
              <input type="text" class="form-control formulario" data-bind="
              value: SerieDocumento,
              visible: VisibleInput,
              attr: { id : 'inputSerieDocumento_' + IdSaldoInicialCuentaPago() } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter, focusout: OnFocusOutSerieDocumento }" style="width: 80px;">
            </td>
            <td class="" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: NumeroDocumento,
              visible: VisibleSpan,
              attr: { id: 'spanNumeroDocumento_' + IdSaldoInicialCuentaPago() }" style="width: 100px;"></span>
              <input type="text" class="form-control formulario" data-bind="
              value: NumeroDocumento,
              visible: VisibleInput,
              attr: { id : 'inputNumeroDocumento_' + IdSaldoInicialCuentaPago() } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter, focusout: OnFocusOutNumeroDocumento }" style="width: 100px;">
            </td>
            <td class="" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: FechaEmision,
              visible: VisibleSpan,
              attr: { id: 'spanFechaEmision_' + IdSaldoInicialCuentaPago() }" style="width: 80px;"></span>
              <input type="text" class="form-control formulario fecha" data-bind="
              value: FechaEmision,
              visible: VisibleInput,
              attr: { id : 'inputFechaEmision_' + IdSaldoInicialCuentaPago() } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter }" style="width: 80px;">
            </td>
            <td class="" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: FechaEmision,
              visible: VisibleSpan,
              attr: { id: 'spanFechaEmision_' + IdSaldoInicialCuentaPago() }" style="width: 80px;"></span>
              <input type="text" class="form-control formulario fecha" data-bind="
              value: FechaEmision,
              visible: VisibleInput,
              attr: { id : 'inputFechaEmision_' + IdSaldoInicialCuentaPago() } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter }" style="width: 80px;">
            </td>
            <td class="" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: NombreMoneda,
              visible: VisibleSpan,
              attr: { id: 'spanNombreMoneda_' + IdSaldoInicialCuentaPago() }" style="width: 80px;"></span>
              <select class="form-control formulario" data-bind="
              value: IdMoneda,
              options: Monedas,
              optionsValue: 'IdMoneda' ,
              optionsText: 'NombreMoneda',
              visible: VisibleInput,
              attr: { id : 'inputNombreMoneda_' + IdSaldoInicialCuentaPago() } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter }" style="width: 80px;"></select>
            </td>
            <td class="text-right" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: MontoOriginal,
              visible: VisibleSpan,
              attr: { id: 'spanMontoOriginal_' + IdSaldoInicialCuentaPago() }" style="width: 80px;"></span>
              <input type="text" class="form-control formulario text-right" data-bind="
              value: MontoOriginal,
              numbertrim: MontoOriginal,
              visible: VisibleInput,
              attr: { id : 'inputMontoOriginal_' + IdSaldoInicialCuentaPago() } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter }" style="width: 80px;">
            </td>
            <td class="text-right" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: SaldoInicial,
              visible: VisibleSpan,
              attr: { id: 'spanSaldoInicial_' + IdSaldoInicialCuentaPago() }" style="width: 80px;"></span>
              <input type="text" class="form-control formulario text-right" data-bind="
              value: SaldoInicial,
              numbertrim: SaldoInicial,
              visible: VisibleInput,
              attr: { id : 'inputSaldoInicial_' + IdSaldoInicialCuentaPago() } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter }" style="width: 80px;">
            </td>
            <td class="text-right" data-bind="event: { click: $root.OnClickFilaSaldoInicial }">
              <span data-bind="
              text: TipoCambioCompra,
              visible: VisibleSpan,
              attr: { id: 'spanTipoCambioVenta_' + IdSaldoInicialCuentaPago() }" style="width: 80px;"></span>
              <input type="text" class="form-control formulario text-right" data-bind="
              value: TipoCambioCompra,
              numbertrim: TipoCambioCompra,
              visible: VisibleInput,
              attr: { id : 'inputTipoCambioVenta_' + IdSaldoInicialCuentaPago() } ,
              event: { focus: $root.OnFocus, keydown: $root.OnKeyEnter }"  style="width: 80px;">
            </td>
            <td lass="text-center">
              <button type="button" class="btn btn-info btn-consulta" title="Detalle Saldo Inicial" data-bind="enable : OnEnableBtnDetalle, event: { click: OnClickBtnDetalle }">
                <span class="fas fa-ellipsis-h"></span>
              </button>
            </td>
            <td lass="text-center">
              <button type="button" class="btn btn-success btn-consulta" title="Guardar Saldo Inicial" data-bind="enable: OnEnableBtnGuardar, event: { click: OnClickBtnGuardar }">
                <span class="glyphicon glyphicon-floppy-disk"></span>
              </button>
            </td>
            <td  lass="text-center">
              <button  type="button" class="btn btn-warning btn-consulta" title="Editar Saldo Inicial"  data-bind="enable: OnEnableBtnEditar, event: { click: OnClickBtnEditar }">
                <span class="glyphicon glyphicon-pencil"></span>
              </button>
            </td>
            <td lass="text-center">
              <button type="button" class="btn btn-danger btn-consulta"  title="Eliminar Saldo Inicial" data-bind="enable: OnEnableBtnEliminar, event: { click: OnClickBtnEliminar }">
                <span class="glyphicon glyphicon-trash"></span>
              </button>
            </td>
          </tr>
          <!-- /ko -->
        </tbody>
      </table>
    </div>
  </fieldset>
</form>
