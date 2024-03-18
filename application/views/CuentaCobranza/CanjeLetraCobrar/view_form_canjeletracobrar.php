<form id="formCanjeLetraCobrar" name="formCanjeLetraCobrar" role="form" autocomplete="off">
  <div class="datalist__result">
    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="container-fluid">
        <!-- ko with : Filtro -->
        <div class="row">
          <fieldset>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Cliente</div>
                      <input id="RazonSocialCliente" type="text" class="form-control formulario" data-bind="
                      value: RazonSocialCliente, 
                      event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, blur: $parent.ValidarCliente }" data-validation="autocompletado" data-validation-error-msg="" data-validation-text-found="">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Moneda</div>
                      <select id="ComboMonedas" class="form-control formulario" data-bind="
                      value: IdMoneda,
                      options: $parent.Monedas,
                      optionsValue: 'IdMoneda' ,
                      optionsText: 'NombreMoneda',
                      event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter }"></select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Fecha Inicio</div>
                      <input id="FechaInicio" type="text" class="form-control formulario fecha" data-bind="
                      value: FechaInicio, 
                      event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter }">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Fecha Fin</div>
                      <input id="FechaFin" type="text" class="form-control formulario fecha" data-bind="
                      value: FechaFin, 
                      event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter }">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <button type="button" class="btn btn-primary btn-control" data-bind="event: { click: $parent.ObtenerPendientesCobranzaCliente }"> Buscar </button>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
        <!-- /ko -->
        <br>
        <div class="row">
          <fieldset>
            <table class="table">
              <thead>
                <tr>
                  <th>Documento</th>
                  <th>Fecha</th>
                  <th class="text-right">Importe</th>
                  <th class="text-right">Saldo</th>
                  <th width="40"></th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : $parent.BusquedaPendientesCobranzaCliente -->
                <tr data-bind="css : ComprobanteSeleccionado() ? 'active' : ''">
                  <td>
                    <span data-bind="text: NombreAbreviado() +' '+ SerieDocumento() +'-'+ NumeroDocumento()"></span>
                  </td>
                  <td>
                    <span data-bind="text: FechaEmision"></span>
                  </td>
                  <td class="text-right">
                    <span data-bind="text: MontoOriginal"></span>
                  </td>
                  <td class="text-right">
                    <span data-bind="text: SaldoPendiente"></span>
                  </td>
                  <td>
                    <div class="checkbox">
                      <input type="checkbox" class="form-control formulario" data-bind="
                      checked: ComprobanteSeleccionado,
                      event: { change: $parent.OnChangeCheckedComprobantePendienteCobranza }" />
                      <label></label>
                    </div>
                  </td>
                </tr>
                <!-- /ko -->
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="text-right">
                    <span>TOTAL:</span>
                  </td>
                  <td class="text-right">
                    <span data-bind="
                    text: TotalPendientesCobranzaCliente,
                    numbertrim: TotalPendientesCobranzaCliente"></span>
                  </td>
                  <th></th>
                </tr>
              </tfoot>
            </table>
            <br>
          </fieldset>
        </div>
        <br>
        <div class="row">
          <fieldset>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-2">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Cant. Letras</div>
                      <input id="NumeroLetra" type="text" class="form-control formulario" data-bind="
                      value: NumeroLetra,
                      event: { change: OnChangeCheckedComprobantePendienteCobranza }">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Lugar</div>
                      <input id="LugarGiro" type="text" class="form-control formulario" data-bind="
                      value: LugarGiro">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Aval</div>
                      <input id="NombreAval" type="text" class="form-control formulario" data-bind="
                      value: NombreAval">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Ruc</div>
                      <input id="RUCAval" type="text" class="form-control formulario" data-bind="
                      value: RUCAval">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
        <br>
        <div class="row">
          <fieldset>
            <table class="table">
              <thead>
                <tr>
                  <th class="text-center">Item</th>
                  <th class="text-center">Documento</th>
                  <th>FechaGiro</th>
                  <th>Plazo (Días)</th>
                  <th>Fecha Vencimiento</th>
                  <th class="text-right">Importe</th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : PendientesLetraCobrar -->
                <tr data-bind="">
                  <td class="text-center">
                    <span data-bind="text: Item"></span>
                  </td>
                  <td class="text-center">
                    <span data-bind="text: SerieDocumento() +' '+ NumeroDocumento()"></span>
                  </td>
                  <td>
                    <div class="form-group">
                      <input type="text" class="form-control formulario" data-bind="
                      value: FechaGiro,
                      event: { change: ObtenerFechaVencimiento }">
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <input type="text" class="form-control formulario" data-bind="
                      value: DiasPlazo,
                      event: { change: ObtenerFechaVencimiento }">
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <input type="text" class="form-control formulario fecha" data-bind="
                      value: FechaVencimiento">
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <input type="text" class="form-control formulario text-right" data-bind="
                      value: ImporteLetra,
                      numbertrim: ImporteLetra, 
                      event: { change: $parent.CalcularImporteTotalCanje }">
                    </div>
                  </td>
                </tr>
                <!-- /ko -->
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5" class="text-right">
                    <span>TOTAL:</span>
                  </td>
                  <td class="text-right">
                    <span data-bind="
                    text: ImporteTotalCanje,
                    numbertrim: ImporteTotalCanje"></span>
                  </td>
                  <th></th>
                </tr>
              </tfoot>
            </table>
            <br>
          </fieldset>
        </div>
        <br>
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