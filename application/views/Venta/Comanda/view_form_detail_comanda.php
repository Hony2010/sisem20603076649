<div class="detail-products">
  <fieldset  class="margin-bottom">
    <div class="head-details col-md-12">
      <table class="table table-border detalle-punto-venta">
        <tr>
          <th>PRODUCTO</th>
          <th width="60" class="">CANT.</th>
          <th width="60" class="">IMPORT.</th>
          <th width="51">&nbsp;</th>
        </tr>
      </table>
    </div>
    <div class="body-details col-md-12" style="max-height: 160px; min-height: 160px">
      <table class="table table-border detalle-punto-venta">
        <tbody>
          <!-- ko foreach : DetallesComprobanteVenta -->
          <tr data-bind="attr : { id: IdDetalleComprobanteVenta()+'_tr_detalle', 'data-IdDetalleComprobanteVenta': IdDetalleComprobanteVenta }, event : { click: Seleccionar }, css: IndicadorImpresion() == ESTADO_INDICADOR_IMPRESION.ENVIADO ? 'enviado': 'pendiente' ">
            <td>
              <span data-bind="text: NombreProducto, attr : { id : IdDetalleComprobanteVenta() + '_input_NombreProducto'"></span><br>
              <b style=" color: #20c05c; " class=" text-uppercase" data-bind="text: 'Obs: ' + Observacion(), visible: Observacion() != '' && Observacion() != null" ></b><br data-bind="visible: Observacion() != '' && Observacion() != null">
              <span class=" text-uppercase" data-bind="text: 'Precio: ' + PrecioUnitario()" ></span>
            </td>
            <td width="60">
              <input type="text" name="Cantidad" class="form-control formulario text-right" data-bind="
              value: Cantidad,
              disable: IndicadorImpresion() == ESTADO_INDICADOR_IMPRESION.ENVIADO,
              attr: { id : IdDetalleComprobanteVenta() + '_input_Cantidad', 'data-cantidad-decimal' : DecimalCantidad() },
              numberdecimal: Cantidad,
              event: { focusout: (data, event) => { return OnFocusOutCantidad( data, event, $parent)}, change: ValidarCantidad, focus: (data, event) => { return OnFocusCantidad( data, event, $parent)} } "
              data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="">
            </td>
            <td width="60">
              <input type="text" class="form-control formulario text-right" data-bind="
              value: SubTotal,
              disable: true
              attr : { id : IdDetalleComprobanteVenta() + '_span_SubTotal'}, numbertrim: SubTotal">
            </td>
            <td width="41">
              <button type="button" class="btn btn-danger btn-consulta" data-bind="click : $parent.OnClickBtnEliminarItemComanda" >
                <span class="fa fa-fw fa-times"></span>
              </button>
            </td>
          </tr>
          <!-- /ko -->
        </tbody>
      </table>
    </div>
    <div class="foot-details col-md-12">
      <div class="col-md-6 text-left">
        <span data-bind="text: ' ITEMS: ' + DetallesComprobanteVenta().length"></span>
      </div>
      <div class="col-md-6 text-right">
        <span data-bind="text: 'TOTAL S/. ' + Total(), numbertrim : Total"></span>
      </div>
    </div>
    <!-- <div class="form-group">
      <textarea class="form-control formulario" rows="2" placeholder="Observaciones" data-bind="value: Observacion" style="min-height: 40px; max-width: 100%;"></textarea>
    </div> -->
  </fieldset>
</div>
<div class="detail-voucher" style="display: none;">
  <input type="hidden" id="input-teclado-virtual" value="Cantidad"/>
  <fieldset  class="margin-bottom">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-12 form-group">
          <div class="btn-group btn-group-justified">
            <div class="btn-group">
              <button type="button" class="btn btn-selection btn-tipodocumento" data-bind="attr: { 'data-tipodocumento': IdTipoDocumentoOrdenPedido }, event: {click: OnClickBtnTipoDocumentoVenta}">PEDIDO</button>
            </div>
            <div class="btn-group">
              <button type="button" class="btn btn-selection btn-tipodocumento" data-bind="attr: { 'data-tipodocumento': IdTipoDocumentoBoleta }, event: {click: OnClickBtnTipoDocumentoVenta}">BOLETA</button>
            </div>
            <div class="btn-group">
              <button type="button" class="btn btn-selection btn-tipodocumento" data-bind="attr: { 'data-tipodocumento': IdTipoDocumentoFactura }, event: {click: OnClickBtnTipoDocumentoVenta}">FACTURA</button>
            </div>
          </div>
        </div>
        <div class="col-md-8 form-group">
          <div class="input-group">
            <div class="input-group-addon formulario-venta">Serie</div>
            <select disabled id="ComboSerieDocumento" class="form-control formulario" data-bind="
              value : IdCorrelativoDocumento,
              options : SeriesDocumento,
              optionsValue : 'IdCorrelativoDocumento' ,
              optionsText : 'SerieDocumento'">
            </select>
          </div>
        </div>
        <div class="col-md-4 form-group">
          <input id="FechaEmision" type="text" disabled class="form-control formulario" data-bind="value: FechaEmision"/>
        </div>
        <div class="col-md-12 form-group">
          <div class="input-group">
            <div class="input-group-addon formulario-venta">RUC/DNI</div>
            <input id="Cliente" name="Cliente" class="form-control formulario" type="text" data-bind="value : RUCDNICliente(),event : { focus : function (data, event) {return OnFocusElementForm(data, event, OnFocus)}, change: ValidarCliente }"
            data-validation="autocompletado_cliente" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente" data-validation-text-found="">
            <div class="input-group-btn">
              <button type="button" class=" btn-buscar btn btn-default no-tab" id="BtnNuevoCliente" data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span class="fa fa-plus-circle"></span></button>
            </div>
          </div>
        </div>
        <div class="col-md-6 form-group text-left">
          <label data-bind="event:{ click : LimpiarCliente }" >Limpiar Cliente</label>
        </div>
        <div class="col-md-6 form-group text-right">
          <div class="checkbox">
            <input name="CheckCliente" id="CheckCliente" type="checkbox" class="form-control formulario"
            data-bind="event: { change : OnChangeCheckCliente , focus : OnFocus , keydown : OnKeyEnter}" />
            <label for="CheckCliente">Editar Cliente</label>
          </div>
        </div>
        <div class="col-md-12 form-group">
          <div class="input-group">
            <div class="input-group-addon formulario-venta">Forma pago</div>
            <select id="ComboFormaPago" class="form-control formulario" data-bind="
              value : IdFormaPago,
              options : FormasPago,
              optionsValue : 'IdFormaPago' ,
              optionsText : 'NombreFormaPago' ,
              event : { change : OnChangeFormaPago , focus : OnFocus , keydown : OnKeyEnter }">
            </select>
          </div>
        </div>
        <div class="col-md-6 form-group">
          <div class="input-group">
            <div class="input-group-addon formulario-venta">Recibido</div>
            <input id="MontoRecibido" type="text" class="form-control formulario text-right" data-cantidad-decimal="2" data-bind="value: MontoRecibido, event: { change: OnChangeMontoRecibido, focus: OnFocusElementForm}, numberdecimal: MontoRecibido"/>
          </div>
        </div>
        <div class="col-md-6 form-group">
          <div class="input-group">
            <div class="input-group-addon formulario-venta">Vuelto</div>
            <input id="VueltoRecibido" type="text" readonly class="form-control formulario text-right" data-bind="value: VueltoRecibido, numbertrim: VueltoRecibido"
            data-validation="number_desc" data-validation-allowing="float,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="El vuelto no debe ser menor a 0"/>
          </div>
        </div>
      </div>
    </div>
  </fieldset>
</div>
