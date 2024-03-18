<!-- ko with : CierreCaja -->
<form  id="formCierreCaja" name="formCierreCaja" role="form" autocomplete="off">
  <div class="datalist__result">
    <input id="IdComprobanteVenta" class="form-control" type="hidden" placeholder="IdComprobanteVenta">
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
                        <div class="input-group-addon formulario">Caja</div>
                        <select id="combo-caja" class="form-control formulario" name=""data-bind="
                          value : IdCaja,
                          options : Cajas,
                          optionsValue : 'IdCaja' ,
                          optionsText : 'NombreCaja',
                          event : { focus : OnFocus, change: function(data,event) {return OnChangeCaja(data,event,CargarApertura);}, keydown: OnKeyEnter}">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">F. Apertura</div>
                        <input id="FechaAperturaCaja" disabled type="text" class="form-control formulario fecha disabled" data-bind="value:FechaAperturaCaja, event : { focus : OnFocus , keydown : OnKeyEnter, change : ValidarFechaApertura}"
                        data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Fecha incorrecta">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Moneda</div>
                        <select id="combo-moneda" disabled class="form-control formulario no-tab disabled" data-bind="
                          value : IdMoneda,
                          options : Monedas,
                          optionsValue : 'IdMoneda' ,
                          optionsText : 'NombreMoneda',
                          event : { focus : OnFocus , keydown : OnKeyEnter}">
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">F. Cierre</div>
                        <input id="FechaCierreCaja" disabled type="text" class="form-control formulario fecha disabled" data-bind="value:FechaCierreCaja, event : { focus : OnFocus , keydown : OnKeyEnter, change : ValidarFechaCierre}"
                        data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Fecha incorrecta">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Cajero</div>
                        <input id="IdUsuarioApertura" disabled type="text" class="form-control formulario no-tab disabled" data-bind = 'value : AliasUsuarioVenta, event : { focus : OnFocus , keydown : OnKeyEnter}'>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Monto Inicial</div>
                        <input id="MontoAperturaCaja" disabled type="text" class="form-control formulario disabled" data-bind = 'value : MontoAperturaCaja, event : { focus : OnFocus , keydown : OnKeyEnter, change : ValidarMontoAperturaCaja}, numbertrim : MontoAperturaCaja'
                        data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Monto Final</div>
                        <input id="MontoCierreCaja" readonly type="text" class="form-control formulario" data-bind = 'value : MontoCierreCaja, event : { focus : OnFocus , keydown : OnKeyEnter, change : ValidarMontoCierreCaja}, numbertrim : MontoCierreCaja'>
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
              <button type="button" id="BtnGrabar" class="btn btn btn-success focus-control" data-bind="click : OnClickBtnGrabar, enable : Cajas().length > 0 && FechaAperturaCaja() != '' ">Grabar</button> &nbsp;
              <button type="button" id="BtnLimpiar" class="btn btn-default focus-control" data-bind="click : OnClickBtnLimpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
              <button type="button" id="BtnDeshacer" class="btn btn-default focus-control" data-bind="click : OnClickBtnDeshacer,visible : opcionProceso() == 2">Deshacer</button> &nbsp;
              <button type="button" id="BtnCerrar" class="btn btn-default focus-control btn-close" data-bind="click : OnClickBtnCerrar, visible : opcionProceso() == 2">Cerrar</button>
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
