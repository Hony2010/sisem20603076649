<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalComprobantesPorPagar">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h3 class="panel-title">Comprobantes a Pagar</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="container-fluid">
            <br>
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon formulario-venta">Moneda</div>
                  <select id="ComboMonedas" class="form-control formulario" data-bind="
                  value: IdMoneda,
                  options: Monedas,
                  optionsValue: 'IdMoneda' ,
                  optionsText: 'NombreMoneda',
                  event: { focus: OnFocus, keydown: OnKeyEnter }"></select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon formulario-venta">T.C.</div>
                  <input id="ValorTipoCambio" type="text" class="form-control formulario" data-bind="value: ValorTipoCambio, event: { focus: OnFocus, keydown: OnKeyEnter }">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <fieldset>
                <legend>Pago</legend>
                <table class="table-bordered datalist__table table ">
                  <thead>
                    <tr>
                      <th class="text-center" colspan="4">Documento Original</th>
                      <th class="text-center" colspan="3">Pago</th>
                    </tr>
                    <tr>
                      <th class="text-center">Documento</th>
                      <th class="text-center">Fecha</th>
                      <th class="text-right">Monto Original</th>
                      <th class="text-right">Saldo</th>
                      <th class="text-right">Soles</th>
                      <th class="text-right">Dolares</th>
                      <th class="text-right">Nuevo Saldo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- ko foreach : ComprobantesPorPagar -->
                    <tr>
                      <td class="text-left" data-bind="text: SerieDocumento() + '-' + NumeroDocumento()"></td>
                      <td class="text-center" data-bind="text: FechaEmision"></td>
                      <td class="text-right" data-bind="text: SimboloMoneda() + ' ' + MontoOriginal()"></td>
                      <td class="text-right" data-bind="text: SimboloMoneda() + ' ' + SaldoPendiente()"></td>
                      <td>
                        <div class="form-group">
                          <input type="text" name="MontoSoles" class="form-control formulario text-right" data-bind="
                          value: MontoSoles,
                          numbertrim: MontoSoles,
                          enable: $parent.IdMoneda() == ID_MONEDA_SOLES,
                          event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter,  change: (data, event) => { return OnChangeMontoPago(data, event, $parent.ComprobantesPorPagar)}, focusout : ValidarMontoSoles }" data-validation="number_desc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="text" name="MontoDolares" class="form-control formulario text-right" data-bind="
                          value: MontoDolares,
                          numbertrim: MontoDolares,
                          enable: $parent.IdMoneda() == ID_MONEDA_DOLARES,
                          event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter,  change: (data, event) => { return OnChangeMontoPago(data, event, $parent.ComprobantesPorPagar)}, focusout : ValidarMontoDolares }" data-validation="number_desc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                        </div>
                      </td>
                      <td class="text-right" data-bind="text: NuevoSaldo()"></td>
                    </tr>
                    <!-- /ko -->
                  </tbody>
                </table>
              </fieldset>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">Total Monto Orig.</div>
              <input type="text" name="TotalMontoOriginal" class="form-control formulario text-right disabled" data-bind="
                value: TotalMontoOriginal,
                enable: false,
                event: { focus: OnFocus, keydown: OnKeyEnter }">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">Total Saldo</div>
              <input type="text" name="TotalSaldo" class="form-control formulario text-right disabled" data-bind="
                value: TotalSaldo,
                enable: false,
                event: { focus: OnFocus, keydown: OnKeyEnter }">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">Total Soles</div>
              <input type="text" name="TotalSoles" class="form-control formulario text-right disabled" data-bind="
              value: TotalSoles,
              enable: false,
              event: { focus: OnFocus, keydown: OnKeyEnter }">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">Total Dolares</div>
              <input type="text" name="TotalDolares" class="form-control formulario text-right disabled" data-bind="
              value: TotalDolares,
              enable: false,
              event: {focus: OnFocus, keydown: OnKeyEnter }">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">Total Nuevo Saldo</div>
              <input type="text" name="TotalNuevoSaldo" class="form-control formulario text-right disabled" data-bind="
              value: TotalNuevoSaldo,
              enable: false,
              event: {focus: OnFocus, keydown: OnKeyEnter }">
            </div>
          </div>
        </div>
        <div class="row">
          <br>
          <div class="col-md-12">
            <div class="col-md-12">
              <div class="form-group">
                <button type="button" class="btn btn-primary " data-bind="event: { click: OnClickBtnCargarComprobantesPagados}"> Pagar Comprobantes </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>