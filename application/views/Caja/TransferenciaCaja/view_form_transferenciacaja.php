<!-- ko with : TransferenciaCaja -->
<form  id="formTransferenciaCaja" name="formTransferenciaCaja" role="form" autocomplete="off">
  <div class="datalist__result">
    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="scrollable scrollbar-macosx">
        <div class="container-fluid">
          <div class="row">
            <fieldset id="fieldsetForm">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-7">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Documento</div>
                        <select id="ComboCaja" class="form-control formulario" name="" data-bind="
                          value : SerieDocumento,
                          options : SeriesDocumento,
                          optionsValue : 'SerieDocumento' ,
                          optionsText : function (item) { return item.SerieDocumento },
                          event : { focus : OnFocus , keydown : OnKeyEnter}"
                          data-validation="select" data-validation-error-msg="Selecciona una Serie Valida">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <input id="FechaTranferencia" disabled type="text" class="form-control formulario" data-bind="value: NumeroDocumento"
                      data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Fecha incorrecta">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">F. Transferencia</div>
                        <input id="FechaTranferencia" disabled type="text" class="form-control formulario fecha" data-bind="value: FechaComprobante, event : { focus : OnFocus , keydown : OnKeyEnter}"
                        data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Fecha incorrecta">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12" data-bind="css: IdMoneda() == ID_MONEDA_DOLARES ? 'col-md-9' : 'col-md-12'">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Caja Origen</div>
                        <select id="ComboCaja" class="form-control formulario" name="" data-bind="
                          value : IdCajaOrigen,
                          options : CajasOrigen,
                          optionsValue : 'IdCaja' ,
                          optionsText : function (item) { return item.NombreCaja() + ' - ' + item.NombreMoneda() },
                          optionsCaption : 'Selecionar Caja...',
                          event : { focus : OnFocus , keydown : OnKeyEnter, change: function(data,event){return OnChangeComboOrigen(data, event,ValidarCombo)}}"
                          data-validation="select" data-validation-error-msg="Selecciona una Caja Origen">
                        </select>
                      </div>
                    </div>
                  </div>
                  <!-- ko if:(IdMoneda() == ID_MONEDA_DOLARES) -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">T. C.</div>
                        <input id="FechaTranferencia" type="text" class="form-control formulario fecha" data-bind=""
                        data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Fecha incorrecta">
                      </div>
                    </div>
                  </div>
                  <!-- /ko -->
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Caja Destino</div>
                        <select id="combo-caja" class="form-control formulario" name=""data-bind="
                          value : IdCajaDestino,
                          options : CajasDestino,
                          optionsValue : 'IdCaja' ,
                          optionsText : function (item) { return item.NombreCaja() + ' - ' + item.NombreMoneda() },
                          optionsCaption : 'Selecionar Caja...',
                          event : { focus : OnFocus , keydown : OnKeyEnter, change: function(data,event){return OnChangeComboDestino(data, event,ValidarCombo)}}"
                          data-validation="select" data-validation-error-msg="Selecciona una Caja Destino">
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Cajero a Transferir</div>
                        <input id="CajeroTransferencia" readonly type="text" class="form-control formulario no-tab" data-bind = 'value : AliasUsuarioVenta, event : { focus : OnFocus , keydown : OnKeyEnter}'>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Saldo Actual (Soles)</div>
                        <input id="SaldoActual" readonly type="text" class="form-control formulario" data-bind = 'value : SaldoActual, event : { focus : OnFocus , keydown : OnKeyEnter}, numbertrim : SaldoActual'
                        data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Transferir (Soles)</div>
                        <input id="MontoTransferencia" type="text" class="form-control formulario" data-bind = 'value : MontoTransferencia, event : { focus : OnFocus , keydown : OnKeyEnter}, numbertrim : MontoTransferencia'
                        data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Observación </div>
                        <input id="Observacion" type="text" class="form-control formulario" data-bind="value: Observacion">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </fieldset>
        </div>
        <br>
        <div class="row">
          <div class="col-md-3">
            <br>
            <strong class="alert-info">* Grabar = ALT + G</strong>
          </div>
          <div class="col-md-6">
            <center>
              <button type="button" id="BtnGrabar" class="btn btn btn-success focus-control" data-bind="click : OnClickBtnGrabar, enable : Cajas().length > 0">Grabar</button> &nbsp;
              <button type="button" id="BtnLimpiar" class="btn btn-default focus-control" data-bind="click : OnClickBtnLimpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
              <button type="button" id="BtnDeshacer" class="btn btn-default focus-control" data-bind="click : OnClickBtnDeshacer,visible : opcionProceso() == 2">Deshacer</button> &nbsp;
              <button type="button" id="BtnCerrar" class="btn btn-default focus-control" data-bind="click : OnClickBtnCerrar, visible : opcionProceso() == 2">Cerrar</button>
              <p>
              </center>
            </div>
            <div class="col-md-3">
              &nbsp;
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
