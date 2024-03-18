<form id="formCobranzaCliente" name="formCobranzaCliente" role="form" autocomplete="off">
  <div class="datalist__result">
    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4">
            <fieldset>
              <legend>Documento</legend>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <select id="ComboSeriesDocumento" class="form-control formulario" data-bind="
                      value: IdCorrelativoDocumento,
                      options: SeriesDocumento,
                      optionsValue: 'IdCorrelativoDocumento' ,
                      optionsText: 'SerieDocumento',
                      event: { focus: OnFocus, keydown: OnKeyEnter }"></select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input id="NumeroDocumento" type="text" disabled class="form-control formulario disabled" data-bind="value: NumeroDocumento, event: { focus: OnFocus, keydown: OnKeyEnter }">
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="col-md-8">
            <fieldset style="margin-bottom: 5px;">
              <legend>Otros</legend>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Fecha</div>
                        <input id="FechaComprobante" type="text" class="form-control formulario fecha" data-bind="value: FechaComprobante, event: { focus: OnFocus, keydown: OnKeyEnter }">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
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
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">T.C.</div>
                        <input id="ValorTipoCambio" type="text" class="form-control formulario" data-bind="value: ValorTipoCambio, event: { focus: OnFocus, keydown: OnKeyEnter }">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="row">
        </div>
        <div class="row">
          <div class="col-md-12">
            <fieldset style="margin-bottom: 5px;">
              <legend>Forma de Pago</legend>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Caja / Banco</div>
                        <select id="ComboCajas" class="form-control formulario" data-bind="
                        value : IdCaja,
                        options : CajasFiltrado(),
                        optionsValue : 'IdCaja' ,
                        optionsText : (item) => { return item.NombreCaja() +' - '+ item.NombreMoneda() },
                        event : { focus : OnFocus, keydown : OnKeyEnter }"></select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Nro Recibo (Ref)</div>
                        <input type="text" class="form-control formulario" data-bind="value: NumeroRecibo, event : { focus : OnFocus, keydown : OnKeyEnter }">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Medio de Pago</div>
                        <select id="ComboMediosPago" class="form-control formulario" data-bind="foreach: MediosPago, value: IdMedioPago, event : { focus : OnFocus, keydown : OnKeyEnter }" data-validation="select" data-validation-error-msg="Seleccionar un Medio de Pago">
                          <option data-bind="value: IdMedioPago, text: NombreAbreviado, attr: {title: NombreMedioPago}"></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Nro de Cheque</div>
                        <input type="text" class="form-control formulario" data-bind="value: NumeroChequeMedioPago, event : { focus : OnFocus, keydown : OnKeyEnter, focusout: ValidarNroCheque }" data-validation="formapagocheque" data-validation-error-msg="Debe ingresar un nro.">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <b>Nombre de Banco</b>
                      <textarea style="height: auto !important;" class="form-control formulario" data-bind="value: NombreBancoCliente, event : { focus : OnFocus, focusout: ValidarNroCheque }" data-validation="formapagocheque" data-validation-error-msg="Debe ingresar un nro."></textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Nro de Doc</div>
                        <input id="NumeroOperacionMedioPago" type="text" class="form-control formulario" data-bind="value: NumeroOperacionMedioPago, event : { focus : OnFocus, keydown : OnKeyEnter }">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Tipo de Tarjeta</div>
                        <select id="ComboMediosPago" class="form-control formulario" data-bind="
                        value : IdTipoTarjeta,
                        options : TiposTarjeta,
                        optionsValue : 'IdTipoTarjeta' ,
                        optionsText : 'NombreTarjeta',
                        event : { focus : OnFocus, keydown : OnKeyEnter }"></select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <fieldset>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Cobrador</div>
                        <select id="ComboMonedas" class="form-control formulario" data-bind="
                      value: UsuarioCobrador,
                      options: Cobradores,
                      optionsValue: 'AliasUsuarioVenta' ,
                      optionsText: 'AliasUsuarioVenta',
                      event: { focus: OnFocus, keydown: OnKeyEnter }"></select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Observación</div>
                        <input id="Observacion" type="text" class="form-control formulario" data-bind="value: Observacion, event : { focus : OnFocus, keydown : OnKeyEnter }">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <!-- ko with : Filtro -->
            <fieldset>
              <legend>Buscar Documentos Pendientes</legend>
              <div class="col-md-12">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario-venta">Cliente</div>
                    <input id="RazonSocialCliente" type="text" class="form-control formulario" data-bind="value: $parent.RazonSocialCliente(), event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, blur: $parent.ValidarCliente }" data-validation="autocompletado_cliente" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente" data-validation-text-found="">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" class="form-control formulario" placeholder="BUSCAR POR DOCUMENTO" data-bind="value: TextoFiltro">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario-venta">Fecha I.</div>
                    <input type="text" class="form-control formulario fecha" data-bind="value: FechaInicio">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario-venta">Fecha F.</div>
                    <input type="text" class="form-control formulario fecha" data-bind="value: FechaFin">
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <button id="BtnBuscarCobranza" type="button" class="btn btn-primary btn-control" data-bind="event: { click: $parent.OnClickBtnBucarCobranzaPorCliente }"> Buscar </button>
                </div>
              </div>
            </fieldset>
            <!-- /ko -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <fieldset>
              <legend>Cobranza</legend>
              <table class="table-bordered datalist__table table ">
                <thead>
                  <tr>
                    <th class="text-center" colspan="4">Documento Original</th>
                    <th class="text-center" colspan="3">Cobranza</th>
                  </tr>
                  <tr>
                    <th class="text-center">Documento</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-right">Monto Original</th>
                    <th class="text-right">Saldo</th>
                    <th class="text-right">Soles</th>
                    <th class="text-right">Dólares</th>
                    <th class="text-right">Nuevo Saldo</th>
                    <th width="41"></th>
                  </tr>
                </thead>
                <tbody>
                  <!-- ko foreach : DetallesCobranzaCliente -->
                  <tr>
                    <td class="text-left" data-bind="text: SerieDocumento() + '-' + NumeroDocumento()"></td>
                    <td class="text-center" data-bind="text: FechaEmision"></td>
                    <td class="text-right" data-bind="text: SimboloMoneda() + ' ' + MontoOriginal(), numbertrim: MontoOriginal"></td>
                    <td class="text-right" data-bind="text: SimboloMoneda() + ' ' + SaldoPendiente(), numbertrim: SaldoPendiente"></td>
                    <td>
                      <div class="form-group">
                        <input type="text" name="MontoSoles" class="form-control formulario text-right" data-bind="
                        value: MontoSoles,
                        numbertrim: MontoSoles,
                        enable: $parent.IdMoneda() == ID_MONEDA_SOLES,
                        event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, change: (data, event) => { return OnChangeMontoCobranza(data, event, $parent.DetallesCobranzaCliente)}, focusout : ValidarMontoSoles }" data-validation="number_desc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <input type="text" name="MontoDolares" class="form-control formulario text-right" data-bind="
                        value: MontoDolares,
                        numbertrim: MontoDolares,
                        enable: $parent.IdMoneda() == ID_MONEDA_DOLARES,
                        event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, change: (data, event) => { return OnChangeMontoCobranza(data, event, $parent.DetallesCobranzaCliente)}, focusout : ValidarMontoDolares }" data-validation="number_desc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                      </div>
                    </td>
                    <td class="text-right" data-bind="text: NuevoSaldo(),"></td>
                    <td>
                      <button type="button" class="btn btn-danger btn-control" data-bind="event: { click: $parent.OnClickBtnRemoverDetalle }">X</button>
                    </td>
                  </tr>
                  <!-- /ko -->
                </tbody>
              </table>
            </fieldset>
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
          <div class="col-md-4">
          </div>
          <div class="col-md-4 form-group text-center">
            <button id="BtnGrabar" type="button" class="btn btn-success focus-control" data-bind="event: { click: OnClickBtnGrabar }">Grabar</button> &nbsp;
            <button id="BtnLimpiar" type="button" class="btn btn-default focus-control" data-bind="event: { click: OnClickBtnLimpiar }, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
            <button id="BtnCerrar" type="button" class="btn btn-default focus-control btn-close" data-bind="event: { click: OnClickBtnCerrar }, visible : opcionProceso() == 2">Cerrar</button>
          </div>
          <div class="col-md-4">
          </div>
        </div>
      </div>
    </div>
  </div>
</form>