<!-- ko with : ComprobanteCompra -->
<form  id="formComprobanteCompraAlternativo" name="formComprobanteCompraAlternativo" role="form" autocomplete="off" >
  <div class="datalist__result">
  <input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoDocumento">
  <input id="IdTipoCompra" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoCompra">
  <input id="IdComprobanteCompra" class="form-control" type="hidden" placeholder="IdComprobanteCompra">
  <input id="IdProveedor" class="form-control" type="hidden" placeholder="RUC/DNI:"  data-bind="value: IdProveedor">

    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="scrollable scrollbar-macosx">
        <div class="container-fluid">
          <div class="row">
            <fieldset>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">RUC - Proveedor</div>
                        <input id="Proveedor" name="Proveedor" class="form-control formulario" type="text" data-bind="value : RUCDNIProveedor(),event : { focus : OnFocus }"
                        data-validation="autocompletado_proveedor" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de Proveedor" data-validation-text-found="">
                        <div class="input-group-btn">
                          <button type="button" class="btn-buscar btn btn-default no-tab" id="BtnNuevoProveedor" data-bind="click : function(data,event) {  return OnClickBtnNuevoProveedor(data,event,$parent.Proveedor); }"><span class="fa fa-plus-circle"></span></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">F. Emision</div>
                        <input id="FechaEmision" name="FechaEmision" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaEmision, event: {focus : OnFocus , focusout : ValidarFechaEmision ,keydown : OnKeyEnter}"
                        data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de emision en invalida"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Periodo Registro</div>
                        <select id="combo-periodo" class="form-control formulario" data-bind="
                        value : IdPeriodo,
                        options : Periodos,
                        optionsValue : 'IdPeriodo' ,
                        optionsText : 'NombrePeriodo' ,
                        event : { change : OnChangePeriodo , focus : OnFocus , keydown : OnKeyEnter }">
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Dirección</div>
                        <input readonly tabindex="-1" class="form-control formulario no-tab" id="Direccion"  data-bind="value : Direccion" type="text" >
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Forma Pago</div>
                        <select id="combo-formapago" class="form-control formulario" data-bind="
                        value : IdFormaPago,
                        options : FormasPago,
                        optionsValue : 'IdFormaPago' ,
                        optionsText : 'NombreFormaPago' ,
                        event : { change : OnChangeFormaPago , focus : OnFocus , keydown : OnKeyEnter }">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">F. Vencimiento</div>
                        <input id="FechaVencimiento" name="FechaVencimiento" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaVencimiento, event : {focus : OnFocus , focusout : ValidarFechaVencimiento , keydown : OnKeyEnter}"
                        data-validation="fecha_vencimiento" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de vencimiento es incorrecta.Solo es obligatoria cuando es forma de pago credito"/>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Tipo Doc.</div>
                      <select id="combo-tipodocumento" class="form-control formulario"data-bind="
                      value : IdTipoDocumento,
                      options : TiposDocumento,
                      optionsValue : 'IdTipoDocumento' ,
                      optionsText : 'NombreAbreviado' ,
                      event : { change : OnChangeTipoDocumento , focus : OnFocus , keydown : OnKeyEnter }">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group formulario-venta">
                      <div class="input-group-addon formulario-venta">Serie</div>
                      <input id="SerieDocumento" type="text" name="" class="form-control formulario" value="" data-bind="value: SerieDocumento, event : {  focus : OnFocus , focusout : ValidarSerieDocumento , keydown : OnKeyEnter}, attr: {'maxlength': TamanoSerieCompra()}"
                        data-validation="required" data-validation-error-msg="Debe Ingresar una Serie">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Numero</div>
                      <input id="NumeroDocumento" class="form-control formulario" type="text"  data-bind="value: NumeroDocumento , event : {  focus : OnFocus , focusout : ValidarNumeroDocumento, keydown : OnKeyEnter}"
                      data-validation="number" data-validation-allowing="range[1;99999999]" data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Guia Remision</div>
                      <input id="GuiaRemision" class="form-control formulario" type="text" data-bind="value: GuiaRemision ,event : { focus : OnFocus , keydown : OnKeyEnter}"  >
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">OC:</div>
                      <input id="OrdenCompra" class="form-control formulario" type="text" data-bind="value: OrdenCompra, event : { focus : OnFocus ,keydown : OnKeyEnter }"  >
                    </div>
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Observación</div>
                      <input id="Observacion" class="form-control formulario" type="text" data-bind="value: Observacion, event : { focus : OnFocus ,keydown : OnKeyEnter }"  >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          </div>
          <div class="row">
            <div class="col-md-3">
              <br>
              <!-- <strong class="alert-info">* Grabar = ALT + G</strong> -->
            </div>
            <div class="col-md-6">
              <center>
                <br>
                <button type="button" id="btn_Grabar" class="btn btn-success focus-control" data-bind="click : GuardarAlternativo">Grabar</button> &nbsp;
                <!-- <button type="button" id="btn_Limpiar" class="btn btn-default focus-control" data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp; -->
                <!-- <button type="button" id="BtnDeshacer" class="btn btn-default " data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp; -->
                <button type="button" id="btn_Cerrar" class="btn btn-default " data-bind="click : OnClickBtnCerrarAlternativo , visible :  opcionProceso() == 2">Cerrar</button>
                <br>
                <p>
              </center>
            </div>
            <div class="col-md-3">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
