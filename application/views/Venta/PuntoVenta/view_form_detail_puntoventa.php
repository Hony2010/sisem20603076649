<div class="detail-products" ondragstart="return false">
	<fieldset class="margin-bottom">
		<div class="head-details col-md-12">
			<table class="table table-border detalle-punto-venta">
				<tr>
					<th>PRODUCTO</th>
					<th width="70" class="">CANT.</th>
					<!-- ko if: ParametroStockProductoVenta() == 1 -->
					<th width="50" class="text-right">STOCK</th>
					<!-- /ko -->
					<th width="60" class="">P.U.</th>
					<th width="60" class="">DESC.</th>
					<th width="70" class="">IMPORT.</th>
					<th width="41">&nbsp;</th>
				</tr>
			</table>
		</div>
		<div class="body-details col-md-12" style="max-height: 160px; min-height: 160px">
			<table class="table table-border detalle-punto-venta">
				<thead>
				</thead>
				<tbody>
					<!-- ko foreach : DetallesComprobanteVenta -->
					<tr
						data-bind="attr : { id: IdDetalleComprobanteVenta()+'_tr_detalle', 'data-IdDetalleComprobanteVenta': IdDetalleComprobanteVenta }, event : { click: Seleccionar }">
						<td>
							<span data-bind="
							text: NombreProducto, 
							attr: { id : IdDetalleComprobanteVenta() + '_input_NombreProducto'"></span>
						</td>
						<td width="70">
							<input type="text" name="Cantidad" class="form-control formulario text-right"
								data-bind="
								value: Cantidad,
								attr: { id : IdDetalleComprobanteVenta() + '_input_Cantidad', 'data-cantidad-decimal' : DecimalCantidad() },
								numberdecimal: Cantidad,
								event: { focusout: (data, event) => { return OnFocusOutCantidad( data, event, $parent)}, focus: (data, event) => { return OnFocusCantidad( data, event, $parent)} } ">
						</td>
						<!-- ko if: $parent.ParametroStockProductoVenta() == 1 -->
						<td width="50" class="text-right">
							<span data-bind="
							text: StockProducto, 
							attr: { id : IdDetalleComprobanteVenta() + '_span_StockProducto',
							css: ColorText,
							numbertrim: StockProducto"></span>
						</td>
						<!-- /ko -->
						<td width="60">
							<input type="text" name="PrecioUnitario" class="form-control formulario text-right"
								data-bind="
								value: PrecioUnitario,
								attr: { id : IdDetalleComprobanteVenta() + '_input_PrecioUnitario', 'data-cantidad-decimal' : DecimalPrecioUnitario() },
								numberdecimal: PrecioUnitario,
								event: { focusout: (data, event) => { return OnFocusOutCantidad( data, event, $parent)}, focus: (data, event) => { return OnFocusCantidad( data, event, $parent)} } ">
						</td>
						<td width="60">
							<input type="text" name="DescuentoUnitario" class="form-control formulario text-right"
								data-bind="
								value: DescuentoUnitario,
								attr: { id : IdDetalleComprobanteVenta() + '_input_DescuentoUnitario', 'data-cantidad-decimal' : DecimalDescuentoUnitario() },
								numberdecimal: DescuentoUnitario,
								event: { focusout: (data, event) => { return OnFocusOutCantidad( data, event, $parent)}, focus: (data, event) => { return OnFocusCantidad( data, event, $parent)} } ">
						</td>
						<td width="70">
							<input type="text" disabled class="form-control formulario text-right"
								data-bind="value: SubTotal, attr : { id : IdDetalleComprobanteVenta() + '_span_SubTotal'}, numbertrim: SubTotal">
						</td>
						<td width="41">
							<button type="button" class="btn btn-danger btn-consulta"
								data-bind="click : $parent.OnQuitarFila">
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
	</fieldset>
</div>
<div class="detail-voucher" style="display: none;">
	<input type="hidden" id="input-teclado-virtual" value="MontoRecibido" />
	<fieldset class="margin-bottom">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12 form-group">
					<div class="btn-group btn-group-justified">
						<div class="btn-group">
							<button type="button" class="btn btn-selection btn-tipodocumento"
								data-bind="attr: { 'data-tipodocumento': IdTipoDocumentoOrdenPedido }, event: {click: OnClickBtnTipoDocumentoVenta}">PEDIDO</button>
						</div>
						<div class="btn-group">
							<button type="button" class="btn btn-selection btn-tipodocumento"
								data-bind="attr: { 'data-tipodocumento': IdTipoDocumentoBoleta }, event: {click: OnClickBtnTipoDocumentoVenta}">BOLETA</button>
						</div>
						<div class="btn-group">
							<button type="button" class="btn btn-selection btn-tipodocumento"
								data-bind="attr: { 'data-tipodocumento': IdTipoDocumentoFactura }, event: {click: OnClickBtnTipoDocumentoVenta}">FACTURA</button>
						</div>
					</div>
				</div>
				<div class="col-md-8 form-group">
					<div class="input-group">
						<div class="input-group-addon formulario-venta">Serie</div>
						<select disabled id="ComboSerieDocumento" class="form-control formulario" data-bind="
						value : IdCorrelativoDocumento,
						options : SeriesDocumentoFiltrado,
						optionsValue : 'IdCorrelativoDocumento' ,
						optionsText : 'SerieDocumento'">
						</select>
					</div>
				</div>
				<div class="col-md-4 form-group">
					<input id="FechaEmision" type="text" disabled class="form-control formulario"
						data-bind="value: FechaEmision" />
				</div>
				<div id="GrupoCliente" class="col-md-12">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon formulario-venta">RUC/DNI</div>
							<input id="Cliente" name="Cliente" class="form-control formulario" type="text"
								data-bind="value : RUCDNICliente(),event : { focus : function (data, event) {return OnFocusElementForm(data, event, OnFocus)}, change: ValidarCliente }"
								data-validation="autocompletado_cliente"
								data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente"
								data-validation-text-found="">
							<div class="input-group-btn">
								<button type="button" class=" btn-buscar btn btn-default no-tab" id="BtnNuevoCliente"
									data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span
										class="fa fa-plus-circle"></span></button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon formulario-venta">Dirección</div>
							<select id="combo-direcciones" class="form-control formulario" data-bind="
							value : Direccion,
							options : DireccionesCliente,
							optionsValue : 'Direccion' ,
							optionsText : 'Direccion' ,
							event : { focus : OnFocus, keydown : OnKeyEnter, change : OnChangeDireccion }">
							</select>
							<!-- <input readonly tabindex="-1" class="form-control formulario no-tab" id="Direccion" data-bind="value : Direccion" type="text"> -->
						</div>
					</div>
				</div>
				<div class="col-md-6 form-group text-left">
					<label data-bind="event:{ click : LimpiarCliente }">Limpiar Cliente</label>
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
				<!-- ko if: (ParametroCaja() == 1)-->
				<div class="col-md-12">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon formulario-venta">Caja</div>
							<select id="combo-caja" class="form-control formulario"
								data-bind="event:{ change: OnChangeCajas, focus : OnFocus, keydown : OnKeyEnter}"
								data-validation="required" data-validation-error-msg="No tiene caja asignado">
							</select>
						</div>
					</div>
				</div>
				<!-- /ko -->
				<div class="col-md-6 form-group">
					<div class="input-group">
						<div class="input-group-addon formulario-venta">Recibido</div>
						<input id="MontoRecibido" type="text" class="form-control formulario text-right"
							data-cantidad-decimal="2"
							data-bind="value: MontoRecibido, event: { change: OnChangeMontoRecibido, focus: OnFocusElementForm}, numberdecimal: MontoRecibido" />
					</div>
				</div>
				<div class="col-md-6 form-group">
					<div class="input-group">
						<div class="input-group-addon formulario-venta">Vuelto</div>
						<input id="VueltoRecibido" type="text" readonly class="form-control formulario text-right"
							data-bind="value: VueltoRecibido, numbertrim: VueltoRecibido" data-validation="number_desc"
							data-validation-allowing="float,range[0;9999999]" data-validation-decimal-separator="."
							data-validation-error-msg="El vuelto no debe ser menor a 0" />
					</div>
				</div>
			</div>
		</div>
	</fieldset>
</div>
