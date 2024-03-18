<!-- ko with : OtroDocumentoIngreso -->
<form  id="formOtroDocumentoIngreso" name="formOtroDocumentoIngreso" role="form" autocomplete="off">
  <div class="datalist__result">
    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="scrollable scrollbar-macosx">
        <div class="container-fluid">
          <div class="row">
            <fieldset id="fieldsetForm">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Tipo de Op. <b class="alert-info">(*)</b></div>
                        <select id="ComboTipoOperacion" class="form-control formulario" name=""data-bind="
                          value : IdTipoOperacionCaja,
                          options : TiposOperacionCaja,
                          optionsValue : 'IdTipoOperacionCaja' ,
                          optionsText : function (item) { return item.NombreConceptoCaja() },
                          event : { focus : OnFocus, keydown : OnKeyEnter, change: ValidarCombo}"
                          data-validation="select" data-validation-error-msg="Selecciona un Motivo"> </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Documento <b class="alert-info">(*)</b></div>
                        <select id="ComboSeriesDocumento" class="form-control formulario" name=""data-bind="
                        value : IdCorrelativoDocumento,
                        options : SeriesDocumento,
                        optionsValue : 'IdCorrelativoDocumento' ,
                        optionsText : function (item) { return item.SerieDocumento() },
                        event : { focus : OnFocus, keydown : OnKeyEnter, change: OnChangeComboSeriesDocumento } "
                        data-validation="select" data-validation-error-msg="Selecciona un Documento"></select> </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <input id="NumeroDocumento" disabled type="text" class="form-control formulario disabled" data-bind="value: NumeroDocumento"
                      data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Fecha incorrecta">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Fecha </div>
                        <input id="FechaComprobante" type="text" class="form-control formulario fecha" data-bind="value:FechaComprobante, event : { focus : OnFocus , keydown : OnKeyEnter}"
                        data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Fecha incorrecta">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12" data-bind="css: IdMoneda() == ID_MONEDA_DOLARES ? 'col-md-9' : 'col-md-12'">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Caja / Banco <b class="alert-info">(*)</b> </div>
                        <select id="ComboCaja" class="form-control formulario" name="" data-bind="
                          value : IdCaja,
                          options : Cajas,
                          optionsValue : 'IdCaja' ,
                          optionsText : function (item) { return item.NombreCaja() + ' - ' + item.NombreMoneda() },
                          optionsCaption : 'Selecionar Caja...',
                          event : { focus : OnFocus , keydown : OnKeyEnter, change: function (data, event) { return OnChangeComboCaja(data, event, ValidarCombo);} }"
                          data-validation="select" data-validation-error-msg="Selecciona una Caja"> </select>
                      </div>
                    </div>
                  </div>
                  <!-- ko if:(IdMoneda() == ID_MONEDA_DOLARES) -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">T. C.</div>
                        <input id="ValorTipoCambio" type="text" class="form-control formulario" data-bind="value: ValorTipoCambio"
                        data-validation="required" data-validation-error-msg="Ingresar campo">
                      </div>
                    </div>
                  </div>
                  <!-- /ko -->
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Medio de Pago <b class="alert-info">(*)</b></div>
                        <select id="ComboMedioPago" class="form-control formulario" name=""data-bind="
                          value : IdMedioPago,
                          options : MediosPago,
                          optionsValue : 'IdMedioPago' ,
                          optionsText : function (item) { return item.NombreAbreviado() },
                          event : { focus : OnFocus , keydown : OnKeyEnter, change: ValidarCombo}"
                          data-validation="select" data-validation-error-msg="Selecciona un Medio de Pago"> </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Numero Doc.</div>
                        <input id="SaldoActual" type="text" class="form-control formulario" data-bind = "value: NumeroOperacionMedioPago, event : { focus : OnFocus , keydown : OnKeyEnter}">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Persona / Entidad</div>
                        <input id="MontoTransferencia" type="text" class="form-control formulario" data-bind = "value: ClienteMedioPago, event : { focus : OnFocus , keydown : OnKeyEnter}">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Observación </div>
                        <input id="Observacion" type="text" class="form-control formulario" data-bind='value: Observacion, event : { focus : OnFocus , keydown : OnKeyEnter}'>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Monto <b class="alert-info">(*)</b></div>
                        <input id="MontoComprobante" type="text" class="form-control formulario" data-bind='value: MontoComprobante, event : { focus : OnFocus , keydown : OnKeyEnter}, numbertrim: MontoComprobante'
                        data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
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
          <div class="col-md-6 text-center">
            <div class="btn-group">
              <button type="button" id="BtnGrabar" class="btn btn btn-success focus-control" data-bind="click : OnClickBtnGrabar, enable : Cajas().length > 0">Grabar</button> &nbsp;
            </div>
            <div class="btn-group">
              <button type="button" id="BtnLimpiar" class="btn btn-default focus-control" data-bind="click : OnClickBtnLimpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
            </div>
            <div class="btn-group">
              <button type="button" id="BtnDeshacer" class="btn btn-default focus-control" data-bind="click : OnClickBtnDeshacer,visible : opcionProceso() == 2">Deshacer</button> &nbsp;
            </div>
            <div class="btn-group">
              <button type="button" id="BtnCerrar" class="btn btn-default focus-control btn-close" data-bind="click : OnClickBtnCerrar, visible : opcionProceso() == 2">Cerrar</button>
            </div>
            <p></p>
          </div>
            <div class="col-md-3"> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
