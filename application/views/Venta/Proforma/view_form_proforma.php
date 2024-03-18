<!-- ko with : Proforma -->
<form id="formProforma" name="formProforma" role="form" autocomplete="off">
	<div class="datalist__result">
		<!-- <input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoDocumento">
    <input id="IdTipoVenta" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoVenta">
    <input id="IdComprobanteVenta" class="form-control" type="hidden" placeholder="IdComprobanteVenta">
    <input id="IdCliente" class="form-control" type="hidden" placeholder="RUC/DNI:" data-bind="value: IdCliente"> -->

		<div class="tab-pane active" id="brand" role="tabpanel">
			<div class="scrollable scrollbar-macosx">
				<div class="container-fluid">
					<div class="row">
						<fieldset id="fieldsetForm">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Serie</div>
												<select name="combo-seriedocumento" id="combo-seriedocumento" class="form-control formulario"
													data-bind="
                          value : IdCorrelativoDocumento,
                          options : SeriesDocumento,
                          optionsValue : 'IdCorrelativoDocumento' ,
                          optionsText : 'SerieDocumento',
                          event : { focus : OnFocus , change : OnChangeSerieDocumento , keydown : OnKeyEnter}"
													data-validation="required" data-validation-error-msg="No tiene serie asignada">
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Número</div>
												<input id="NumeroDocumento" class="form-control formulario no-tab" type="text" tabindex="-1"
													data-bind="value: NumeroDocumento ,
                        attr : { readonly : CheckNumeroDocumento }, event : {  focus : OnFocus , focusout : ValidarNumeroDocumento , keydown : OnKeyEnter }"
													data-validation="number" data-validation-allowing="range[1;99999999]"
													data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos"
													data-validation-depends-on="CheckNumeroDocumento" data-validation-optional="true">
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="input-group">
												<div class="checkbox">
													<input name="CheckNumeroDocumento" id="CheckNumeroDocumento" type="checkbox"
														class="form-control formulario"
														data-bind="event: { change : OnChangeCheckNumeroDocumento , focus : OnFocus , keydown : OnKeyEnter}" />
													<label for="CheckNumeroDocumento">Editar Número</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-1">
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">F. Emisión</div>
												<input id="FechaEmision" name="FechaEmision" class="form-control formulario" data-bind="
                          value: FechaEmision, 
                          event: {focus : OnFocus , focusout : ValidarFechaEmision, keydown : OnKeyEnter, change: OnChangeFechaEmision}"
													data-inputmask-clearmaskonlostfocus="false" data-validation="date"
													data-validation-format="dd/mm/yyyy"
													data-validation-error-msg="La fecha de emision en invalida" />
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-5">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">RUC/DNI - Cliente</div>
												<input id="Cliente" name="Cliente" class="form-control formulario" type="text"
													data-bind="value : RUCDNICliente(),event : { focus : OnFocus }"
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
									<div class="col-md-1">
										<div class="form-group">
											<div class="input-group">
												<div class="checkbox">
													<input name="CheckCliente" id="CheckCliente" type="checkbox" class="form-control formulario"
														data-bind="event: { change : OnChangeCheckCliente , focus : OnFocus , keydown : OnKeyEnter}" />
													<label for="CheckCliente">Editar</label>
												</div>
											</div>
										</div>
									</div>
									<!-- ko if: ParametroTransporteMercancia() == 1 -->
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
									<!-- /ko -->
									<!-- ko if:(MostrarCampos.Observacion() == "1" ) -->
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Otros Datos</div>
												<input id="OrdenCompra" class="form-control formulario" type="text"
													data-bind="value: Observacion, event : { focus : OnFocus ,keydown : OnKeyEnter, change: OnChangeOrigenDestino }">
											</div>
										</div>
									</div>
									<!-- /ko -->
								</div>
							</div>
						</fieldset>
					</div>
					<br>
					<div class="row">
						<!-- ko if:(ParametroRubroRepuesto() == 0) -->
						<div class="btn-group">
							<button type="button" class="btn btn-primary no-tab" href="#"
								data-bind="click : function(data,event) {  return OnClickBtnBuscadorMercaderiaListaSimple(data,event,$parent);}">
								<span class="glyphicon glyphicon-search"></span> Buscar Mercadería con Lista
							</button>
						</div>
						<!-- /ko -->
						<div><br></div>
						<fieldset>
							<div class="col-md-12">
								<div class="row detalle-comprobante">
									<div class="col-md-12">
										<table id="DetalleBoletaViaje" class="datalist__table table display grid-detail-body table-border"
											width="100%" id="tablaDetalleComprobanteVenta">
											<thead>
												<tr>
													<th class="">#</th>
													<th class="col-sm-2 text-center products__id">Código</th>
													<th class="col-sm-4 products__title">Descripción</th>
													<!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS && ParametroMarcaVenta() == 1) -->
													<th class="products__title"> Marca </th>
													<!-- /ko -->
													<!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<th class="text-center products__title">Unid.</th>
													<!-- /ko -->
													<!-- ko if:(ParametroStockProductoVenta() != 0 && TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<th class="col-sm-1 text-center products__title">Stock</th>
													<!-- /ko -->
													<th class="col-sm-1 text-center products__title ">Cantidad</th>
													<!-- ko if: (ParametroCalculoIGVDesdeTotal() == 0) -->
													<th class="col-sm-1 products__title text-center">P. U.</th>
													<!-- /ko -->
													<!-- ko if: (ParametroCalculoIGVDesdeTotal() == 1) -->
													<th class="col-sm-1 products__title text-center">V. U.</th>
													<!-- /ko -->
													<!-- ko if: (ParametroDescuentoUnitario() != 0) -->
													<th class="col-sm-1 text-center products__title">Desc. Unit.</th>
													<!-- /ko -->
													<!-- ko if: (ParametroDescuentoItem() != 0 && TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<th class="text-center products__title">Desc. Item</th>
													<!-- /ko -->
													<th class="col-sm-1 products__title text-center">Importe</th>
													<th class="col-sm-1 text-center products__title"></th>
												</tr>
											</thead>
											<tbody>
												<!-- ko foreach : DetallesComprobanteVenta -->
												<tr name="Fila" class="clickable-row" style="min-height: 80px;"
													data-bind="attr : { id: IdDetalleComprobanteVenta()+'_tr_detalle' }, click :  function(data,event) { return OnClickFila(data,event,$parent.OnRefrescar); } ">
													<td data-bind="text: NumeroItem" style="vertical-align: middle;"></td>
													<td class="col-sm-2">
														<div class="form-group">
															<input class="form-control formulario"
																data-bind="
                              value: CodigoMercaderia, valueUpdate : 'keyup',
                              attr : { id : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'},
                              event : {input:function(data,event) { return OnChangeText(data, event, $parent); } , focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); }  }"
																type="text" data-validation="validacion_producto"
																data-validation-error-msg="Cod. Inválido" data-validation-found="false"
																data-validation-text-found="">
														</div>
													</td>
													<td class="col-sm-4">
														<div class="form-group">
															<!-- ko if: $parent.ParametroRubroRepuesto() == 0 || $parent.TipoVenta() != TIPO_VENTA.MERCADERIAS-->
															<input class="form-control formulario"
																data-bind="
																	value: NombreProducto,
																	disable:  $parent.ParametroTransporte() == 1 && $parent.TipoVenta() == TIPO_VENTA.SERVICIOS && BLOQUEO_DETALLE_TRANSPORTE == 1,                              
																	attr : { id : IdDetalleComprobanteVenta() + '_input_NombreProducto', 'data-validation-reference' : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'  },
																	event: { 
																		focus : (data,event) => OnFocus(data,event,$parent.OnRefrescar),
																		change: OnChangeNombreProducto
																	}" type="text"
																data-validation="autocompletado_producto"
																data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
															<!-- /ko -->
															<!-- ko if: $parent.ParametroRubroRepuesto() == 1 && $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS -->
															<input class="form-control formulario"
																data-bind="
																		value: NombreLargoProducto,
																		attr : { id : IdDetalleComprobanteVenta() + '_input_NombreProducto', 'data-validation-reference' : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'  },
																		event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }  }" type="text"
																data-validation="autocompletado_producto"
																data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
															<!-- /ko -->
														</div>
													</td>
													<!-- ko if:($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS && $parent.ParametroMarcaVenta() == 1) -->
													<td class="text-center">
														<span class="" data-bind="
                            text : NombreMarca, 
                            attr : { id : IdDetalleComprobanteVenta() + '_span_Marca'} "></span>
													</td>
													<!-- /ko -->
													<!-- ko if:($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<td class="text-center">
														<span class="" data-bind="
                            text : AbreviaturaUnidadMedida, 
                            attr : { id : IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida'}"></span>
													</td>
													<!-- /ko -->
													<!-- ko if:($parent.ParametroStockProductoVenta() != 0 && $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<td class="text-center">
														<span class=""
															data-bind="
                            text : StockProducto, 
                            attr : { id : IdDetalleComprobanteVenta() + '_span_StockProducto'}, css: ColorText , numbertrim :StockProducto "></span>
													</td>
													<!-- /ko -->
													<td class="col-sm-1">
														<div class="form-group">
															<!-- disable: $parent.ParametroCalcularCantidad() == 1, -->
															<input name="Cantidad" class="form-control formulario numeric text-mumeric"
																data-bind="
                              value : Cantidad,                               
                              attr: { id : IdDetalleComprobanteVenta() + '_input_Cantidad', 'data-cantidad-decimal' : DecimalCantidad() },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout : ValidarCantidad, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);}, change: (data, event) => OnChangeCantidad(data, event, $parent) }, 
                              numberdecimal: Cantidad, 
                              ko_autocomplete_cantidad: { source: DataPrecios(), raleo: DataRaleo(), precio: PrecioUnitario}" type="text" data-validation="number_calc"
																data-validation-allowing="float,positive,range[0.001;9999999]"
																data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 0) -->
													<td class="col-sm-1">
														<!-- , disable: $parent.ParametroCalcularCantidad() == 1 -->
														<div class="form-group">
															<input name="PrecioUnitario" list="items"
																class="form-control  formulario numeric text-mumeric inputs"
																data-bind="
																	value: PrecioUnitario,
																	disable: $parent.IndicadorEditarCampoPrecioUnitarioVenta() == 0,
																	attr : { id : IdDetalleComprobanteVenta() + '_input_PrecioUnitario', 'data-cantidad-decimal' : DecimalPrecioUnitario()},
																	event: { 
																		focus: (data,event) => OnFocusPrecioUnitario(data,event,$parent.OnRefrescar), 
																		keydown : (data,event) => OnKeyEnter(data,event,$parent.OnKeyEnter), 
																		focusout : ValidarPrecioUnitario, 
																		change: (data, event) => OnChangePrecioUnitario(data, event, $parent), 
																	}, 
																	numberdecimal : PrecioUnitario, ko_autocomplete: { source: DataPrecios(), raleo: DataRaleo(), cantidad: Cantidad}"
																type="text" data-validation="number_calc"
																data-validation-allowing="float,positive,range[0.001;9999999]"
																data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
														</div>
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 1) -->
													<td class="col-sm-1">
														<div class="form-group">
															<input name="ValorUnitario" list="items"
																class="form-control  formulario numeric text-mumeric inputs"
																data-bind="
																	value : ValorUnitario, 
																	disable: $parent.ParametroCalcularCantidad() == 1 && $parent.IndicadorEditarCampoPrecioUnitarioVenta() == 0,
																	attr : { id : IdDetalleComprobanteVenta() + '_input_ValorUnitario', 'data-cantidad-decimal' : DecimalPrecioUnitario()},
																	event: { focus: function(data,event) { return OnFocusValorUnitario(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarPrecioUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : ValorUnitario"
																type="text" data-validation="number_calc"
																data-validation-allowing="float,positive,range[0.001;9999999]"
																data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
														</div>
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 0 && $parent.ParametroDescuentoUnitario() != 0) -->
													<td class="col-sm-1">
														<div class="form-group">
															<input name="DescuentoUnitario"
																class="form-control formulario numeric text-mumeric inputs" data-cantidad-decimal="2"
																data-bind="
																	value : DescuentoUnitario ,
																	attr : { id : IdDetalleComprobanteVenta() + '_input_DescuentoUnitario', 'data-cantidad-decimal' : DecimalDescuentoUnitario() },
																	event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarDescuentoUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : DescuentoUnitario"
																type="text" data-validation="number_desc"
																data-validation-allowing="float,positive,range[0;9999999]"
																data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 1 && $parent.ParametroDescuentoUnitario() != 0) -->
													<td class="col-sm-1">
														<div class="form-group">
															<input name="DescuentoValorUnitario"
																class="form-control formulario numeric text-mumeric inputs" data-cantidad-decimal="2"
																data-bind="
																value : DescuentoValorUnitario ,
																attr : { id : IdDetalleComprobanteVenta() + '_input_DescuentoValorUnitario', 'data-cantidad-decimal' : DecimalDescuentoValorUnitario() },
																event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarDescuentoValorUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : DescuentoValorUnitario"
																type="text" data-validation="number_desc"
																data-validation-allowing="float,positive,range[0;9999999]"
																data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- /ko -->
													<!-- ko if:($parent.ParametroDescuentoItem() != 0 && $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<td class="text-center">
														<span class=""
															data-bind="text : CalcularDescuentoItem(), attr : { id : IdDetalleComprobanteVenta() + '_span_DescuentoItem'}"></span>
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 0) -->
													<td class="col-sm-1">
														<div class="form-group">
															<input class="form-control  formulario text-right"
																data-bind="
																	value : SubTotal,
																	disable: OnDisableSubTotal,
																	attr : { id : IdDetalleComprobanteVenta() + '_span_SubTotal'},
																	event : { 
																		focus: (data,event) => OnFocusSubtotal(data,event,$parent.OnRefrescar), 
																		keydown : (data,event) => OnKeyEnter(data,event,$parent.OnKeyEnter), 
																		focusout: ValidarSubTotal, 
																		change: (data,event) => OnChangeSubTotal(data,event,$parent) 
																	}, 
																	numbertrim:SubTotal"
																type="text" data-validation="number_calc"
																data-validation-allowing="float,positive,range[0.001;9999999]"
																data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 1) -->
													<td class="col-sm-1">
														<div class="form-group">
															<input class="form-control  formulario text-right"
																data-bind="
																	value : ValorVentaItem,
																	attr : { id : IdDetalleComprobanteVenta() + '_span_ValorVentaItem'},
																	event : { 
																		focus: function(data,event) { return OnFocusValorVentaItem(data,event,$parent.OnRefrescar); }, 
																		keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, 
																		focusout: ValidarValorVentaItem, 
																		change: function (data,event){return CalculoValorUnitario(data,event,$parent.OnRefrescar);} 
																	}, 
																	numbertrim: ValorVentaItem"
																type="text" data-validation="number_calc"
																data-validation-allowing="float,positive,range[0.001;9999999]"
																data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- /ko -->
													<td class="col-sm-auto">
														<div class="form-group ajuste-opcion-plusminus">
															<button type="button"
																class="btn btn-default focus-control glyphicon glyphicon-minus no-tab" data-bind="click : function(data,event) {  return OnClickBtnOpcion(data,event,$parent.OnQuitarFila);  },
                              event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } ,keydown : function(data,event) { return OnKeyEnterOpcion(data,event,$parent.OnKeyEnter); }  },
                              attr : { id : IdDetalleComprobanteVenta() + '_a_opcion'}"></button>
														</div>
													</td>
												</tr>
												<!-- /ko -->
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="row">
						<div class="col-md-7 denominacion-moneda-nacional">
							<span data-bind="html : DenominacionTotal()" id="nletras"></span>
						</div>
						<div class="col-md-2 text-right">
							<!-- ko if: (TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
							<span data-bind="html : 'CANT. TOTAL : '+TotalCantidades()"></span>
							<!-- /ko -->
						</div>
						<div class="col-md-2 text-right">
							<!-- ko if: (ParametroDescuentoUnitario() != 0 && TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
							<span data-bind="html : 'DESC. TOTAL : '+CalcularTotalDescuento()"></span>
							<!-- /ko -->
						</div>
						<div class="col-md-1 text-right">
							<span data-bind="html : 'ITEMS : '+TotalItems()"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Total</div>
								<input id="Total" readonly tabindex="-1"
									class="form-control formulario numeric text-mumeric input-totales no-tab" type="text"
									placeholder="Total" data-bind="value : CalculoTotalVenta()">
							</div>
						</div>
						<!-- ko if: (ParametroTransporteMercancia() == 1) -->
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Saldo a Cuenta</div>
								<input id="MontoACuenta" tabindex="-1"
									class="form-control formulario numeric text-mumeric input-totales" type="text"
									placeholder="Saldo A Cuenta" data-bind="
                value: MontoACuenta,
                numbertrim: MontoACuenta">
							</div>
						</div>
						<!-- /ko -->
					</div>
					<div class="row">
						<div class="col-md-2">
							<br>
							<strong class="alert-info">* Grabar = ALT + G</strong>
						</div>
						<div class="col-md-8">
							<center>
								<br>
								<button type="button" id="btn_Grabar" class="btn btn-success focus-control"
									data-bind="click : GuardarProforma">Grabar</button> &nbsp;
								<button type="button" id="btn_Limpiar" class="btn btn-default focus-control"
									data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
								<button type="button" id="BtnDeshacer" class="btn btn-default"
									data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
								<button type="button" id="btn_Cerrar" class="btn btn-default"
									data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
								<p>
							</center>
						</div>
						<div class="col-md-2">
							&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- /ko -->
