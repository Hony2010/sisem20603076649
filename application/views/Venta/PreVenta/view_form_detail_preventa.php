<div class="detail-products">
  <fieldset  class="margin-bottom">
    <div class="head-details col-md-12">
      <table class="table table-border detalle-punto-venta">
        <tr>
          <th>PRODUCTO</th>
          <th width="60" class="">CANT.</th>
          <!-- ko if:(IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.PRECUENTA) -->
          <th width="30" class="">SLD.</th>
          <!-- /ko -->
          <th width="60" class="">DESC. %</th>
          <th width="60" class="">DESC.</th>
          <th width="60" class="">IMPORT.</th>
          <!-- ko if:(IdTipoDocumento() == "") -->
          <th width="41">&nbsp;</th>
          <!-- /ko -->
        </tr>
      </table>
    </div>
    <div class="body-details col-md-12" style="max-height: 160px; min-height: 160px">
      <table class="table table-border detalle-punto-venta">
        <tbody>
          <!-- ko foreach : DetallesComprobanteVenta -->
          <tr data-bind="attr : { id: IdDetalleComprobanteVenta()+'_tr_detalle', 'data-IdDetalleComprobanteVenta': IdDetalleComprobanteVenta }, event : { click: Seleccionar }">
            <td>
              <span data-bind="text: NombreProducto, attr : { id : IdDetalleComprobanteVenta() + '_input_NombreProducto'"></span><br>
              <span class=" text-uppercase" data-bind="text: 'Precio: ' + PrecioUnitario()" ></span>
            </td>
            <td width="60">
              <input type="text" name="Cantidad" class="form-control formulario text-right" data-bind="
              value: Cantidad,
              attr: { id : IdDetalleComprobanteVenta() + '_input_Cantidad', 'data-cantidad-decimal' : DecimalCantidad() },
              numberdecimal: Cantidad,
              disable: $parent.IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.COMANDA,
              event: { focusout: (data, event) => { return OnFocusOutCantidad( data, event, $parent)}, focus: (data, event) => { return OnFocusCantidad( data, event, $parent)} } ">
            </td>
            <!-- ko if:($parent.IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.PRECUENTA) -->
            <td width="30" data-bind="text: SaldoPendientePreVenta"> </td>
            <!-- /ko -->
            <td width="60">
              <input type="text" name="PorcentajeDescuento" class="form-control formulario text-right PorcentajeDescuento" data-bind="
              value: PorcentajeDescuento,
              attr: { id : IdDetalleComprobanteVenta() + '_input_DescuentoUnitario', 'data-cantidad-decimal' : DecimalCantidad() },
              numberdecimal: PorcentajeDescuento,
              disable: false,
              event: { change: (data, event) => { return OnchangePorcentajeDescuento( data, event, $parent)}, focus: (data, event) => { return OnFocusCantidad( data, event, $parent)} } ">
            </td>
            <td width="60">
              <input type="text" name="DescuentoUnitario" class="form-control formulario text-right" data-bind="
              value: DescuentoUnitario,
              attr: { id : IdDetalleComprobanteVenta() + '_input_DescuentoUnitario', 'data-cantidad-decimal' : DecimalDescuentoUnitario() },
              numberdecimal: DescuentoUnitario,
              disable: true,
              event: { focusout: (data, event) => { return OnFocusOutCantidad( data, event, $parent)} } ">
            </td>
            <td width="60">
              <input type="text" disabled class="form-control formulario text-right" data-bind="value: SubTotal, attr : { id : IdDetalleComprobanteVenta() + '_span_SubTotal'}, numbertrim: SubTotal">
            </td>
            <!-- ko if:($parent.IdTipoDocumento() == "") -->
            <td width="41">
              <button type="button" class="btn btn-danger btn-consulta" data-bind="click : $parent.OnQuitarFila" >
                <span class="fa fa-fw fa-times"></span>
              </button>
            </td>
            <!-- /ko -->
          </tr>
          <!-- /ko -->
        </tbody>
      </table>
    </div>
    <div class="foot-details col-md-12">
      <div class="col-md-4 text-left">
        <span data-bind="text: ' ITEMS: ' + DetallesComprobanteVenta().length"></span>
      </div>
      <div class="col-md-4 text-center">
        <span data-bind="text: 'DESC. S/. ' + CalcularTotalDescuento()"></span>
      </div>
      <div class="col-md-4 text-right">
        <span data-bind="text: 'TOTAL S/. ' + Total(), numbertrim: Total"></span>
      </div>
    </div>
    <div id="GrupoClientePreCuenta" class="col-md-12">
      <div class="form-group" data-bind="visible: IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.COMANDA">
        <div class="input-group">
          <div class="input-group-addon formulario-venta">RUC/DNI</div>
          <input id="ClientePreCuenta" name="Cliente" class="form-control formulario" type="text" data-bind="value : RUCDNICliente(),event : { focus : function (data, event) {return OnFocusElementForm(data, event, OnFocus)}, change: ValidarCliente }"
          data-validation="autocompletado_cliente" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente" data-validation-text-found="">
          <div class="input-group-btn">
            <button type="button" class=" btn-buscar btn btn-default no-tab" id="BtnNuevoCliente" data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span class="fa fa-plus-circle"></span></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 form-group text-left" data-bind="visible: IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.COMANDA">
      <label data-bind="event:{ click : LimpiarCliente }" style="vertical-align: sub;" >Limpiar Cliente</label>
    </div>
    <div class="col-md-5 form-group text-left" data-bind="visible: IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.COMANDA">
      <label id="ClienteConTarjeta"></label>
    </div>
    <div class="col-md-4 form-group text-right" data-bind="visible: IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.COMANDA">
      <div class="checkbox">
        <input id="AplicarDescuentoTarjeta" type="checkbox" class="form-control formulario" data-bind="event: { change : OnClickAplicarDescuentoProductoPorTarjeta }" />
        <label for="AplicarDescuentoTarjeta">Aplicar descuento.</label>
      </div>
    </div>
  </fieldset>
</div>
<div class="detail-voucher" style="display: none;">
  <input type="hidden" id="input-teclado-virtual" value="Cantidad"/>
  <fieldset  class="margin-bottom">
    <div class="form-group">
      <b style="text-decoration: underline; font-size:13px;" id="TituloTipoComprobante"></b>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-6 form-group">
          <div class="input-group">
            <div class="input-group-addon formulario-venta">Serie</div>
            <input id="SerieDocumento" type="text" disabled class="form-control formulario" data-bind="value: SerieDocumento"/>
          </div>
        </div>
        <div class="col-md-6 form-group">
          <input id="FechaEmision" type="text" disabled class="form-control formulario" data-bind="value: FechaEmision"/>
        </div>
        <div id="GrupoCliente" class="col-md-12">
          <div class=" form-group">
            <div class="input-group">
              <div class="input-group-addon formulario-venta">RUC/DNI</div>
              <input id="Cliente" name="Cliente" class="form-control formulario" type="text" data-bind="value : RUCDNICliente(),event : { focus : function (data, event) {return OnFocusElementForm(data, event, OnFocus)}, change: ValidarCliente }"
              data-validation="autocompletado_cliente" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente" data-validation-text-found="">
              <div class="input-group-btn">
                <button type="button" class=" btn-buscar btn btn-default no-tab" id="BtnNuevoCliente" data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span class="fa fa-plus-circle"></span></button>
              </div>
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
