<!-- ko with : NotaCredito  -->
<form id="formNotaCredito" name="formNotaCredito" role="form" autocomplete="off">
	<div class="row">
		<div class="container-fluid">
			<div class="col-md-12">
				<div class="row">
					<input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento"
						data-bind="value: IdTipoDocumento">
					<input id="IdTipoVenta" class="form-control" type="hidden" placeholder="Documento"
						data-bind="value: IdTipoVenta">
					<input id="IdComprobanteVenta" class="form-control" type="hidden" placeholder="IdComprobanteVenta">
					<input id="IdCliente" class="form-control" type="hidden" placeholder="RUC/DNI:" data-bind="value: IdCliente">
					<input id="IdPersona" class="form-control" type="hidden" placeholder="Persona:" data-bind="value: IdPersona">
					<div class="col-md-12">
						<fieldset>
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
                          event : { change : OnChangeSerieDocumento, keydown : OnKeyEnter}" data-validation="required"
													data-validation-error-msg="No tiene serie asignada">
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Número</div>
												<input id="NumeroDocumento" class="form-control formulario no-tab" type="text" tabindex="-1"
													data-bind="value: NumeroDocumento , attr : { readonly : CheckNumeroDocumento }, event : { focusout : ValidarNumeroDocumento, keydown : OnKeyEnter}"
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
														data-bind="event: { change : OnChangeCheckNumeroDocumento, keydown : OnKeyEnter}" />
													<label for="CheckNumeroDocumento"> ¿Editar Numero?</label>
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
												<input id="FechaEmision" name="FechaEmision" class="form-control formulario"
													data-inputmask-clearmaskonlostfocus="false"
													data-bind="value: FechaEmision, event: {focusout : ValidarFechaEmision, keydown : OnKeyEnter, change: OnChangeFechaEmision}"
													data-validation="date" data-validation-format="dd/mm/yyyy"
													data-validation-error-msg="La fecha de emision en invalida" />
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario formulario-venta">Motivo</div>
												<select name="combo-motivo" id="combo-motivo" class="form-control formulario" data-bind="
                        value : IdMotivoNotaCredito,
                        options : MotivosNotaCredito,
                        optionsValue : 'IdMotivoNotaCredito' ,
                        optionsText : 'NombreMotivoNotaCredito',
                        event:{change: CambiarMotivoNotaCredito, keydown : OnKeyEnter}">
												</select>
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
									<div class="col-md-3" data-bind="visible : IdFormaPago() == ID_FORMA_PAGO_CREDITO">
<!-- 										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">F. Vencimiento</div>
												<input id="FechaVencimiento" name="FechaVencimiento"
													class="form-control formulario" style="height:27px !important;"
													data-inputmask-clearmaskonlostfocus="false"
													data-bind="value: FechaVencimiento, event : {focus : OnFocus , focusout : ValidarFechaVencimiento , keydown : OnKeyEnter}"
													data-validation="fecha_vencimiento"
													data-validation-format="dd/mm/yyyy"
													data-validation-error-msg="La fecha de vencimiento es incorrecta. Solo es obligatoria cuando es forma de pago credito" /> -->
												<div class="input-group">
													<button type="button" class="btn btn-primary no-tab" style="width: auto !important;padding-left : 10px !important;padding-right:10px;"
														id="BtnCuotasPagoClienteComprobanteVenta"
														data-bind="click : function(data,event) {  return OnClickBtnAbrirPlanCuotasPago(data,event); }"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Agregar Cuotas de Pago</button>
												</div>														
<!-- 											</div>
										</div> -->
									</div>									
								</div>
<!-- 								<div class="col-md-12">
									<strong class="alert-info">*La forma de Pago: CREDITO solo debe ser usado por el motivo: Ajustes - montos y/o fechas de pago, en los demás motivos dejarlo en CONTADO.</strong>
								</div> -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Observación</div>
												<!-- <textarea id="Observacion" class="form-control formulario" name="Observacion" rows="2" cols="20"></textarea> -->
												<input id="Observacion" class="form-control formulario" type="text"
													data-bind="value: Observacion, event:{keydown : OnKeyEnter}" value="Jorge">
											</div>
										</div>
									</div>
								</div>
								<div class="row panel-pendientenota">
									<div class="col-md-12">
										<div class="form-group">
											<div class="input-group">
												<div class="checkbox">
													<input id="CheckPendiente" class="form-control formulario" type="checkbox"
														name="CheckPendiente"
														data-bind="checked: CheckPendiente, event:{keydown : OnKeyEnter, change: OnChangeCheckPendiente}">
													<label for="CheckPendiente">Pendiente Nota Entrada</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7">
										<fieldset>
											<legend>Datos Cliente</legend>
											<div class="form-group">
												<div class="input-group">
													<div class="input-group-addon formulario">RUC/DNI - Cliente</div>
													<input id="Cliente" name="Cliente" class="form-control formulario" type="text"
														data-bind="value : RUCDNICliente(),event : { focus : OnFocus }"
														data-validation="autocompletado_cliente"
														data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente"
														data-validation-text-found="">
												</div>
											</div>
											<div class="form-group">
												<div class="form-group">
													<div class="input-group">
													<div class="input-group-addon formulario">Direccion:</div>
														<!--ko if: DireccionParametroCliente() == 1  -->														
														<select id="combo-direcciones" class="form-control formulario" data-bind="
														value : Direccion,
														options : DireccionesCliente,
														optionsValue : 'Direccion' ,
														optionsText : 'Direccion' ,
														event : { focus : OnFocus, keydown : OnKeyEnter, change : OnChangeDireccion }">
														</select>
														<!-- /ko -->
            											<!-- ko if: DireccionParametroCliente() == 0 -->
														<input tabindex="-1" class="form-control formulario no-tab" id="Direccion" data-bind="value : Direccion" type="text"> 
														<!-- /ko -->
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<div class="col-md-5 panel-almacen">
										<fieldset>
											<legend>Movim. de Almacén</legend>
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario">Almacén</div>
																<select id="combo-sede" class="form-control formulario" data-bind="
                                value : IdAsignacionSede,
                                options : Sedes,
                                optionsValue : 'IdAsignacionSede' ,
                                optionsText : 'NombreSede',
                                event: {keydown : OnKeyEnter, change: OnChangeAlmacen}" data-validation="required"
																	data-validation-error-msg="No tiene almacen asignado">
																</select>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario">F. Ingreso</div>
																<input disabled tabindex="-1" id="FechaIngreso" name="FechaIngreso"
																	class="form-control formulario no-tab" data-inputmask-clearmaskonlostfocus="false"
																	data-bind="value: FechaIngreso, event:{keydown : OnKeyEnter}"
																	data-validation="fecha_vencimiento" data-validation-format="dd/mm/yyyy"
																	data-validation-error-msg="" />
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
											<legend>Documento Referencia</legend>
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon">Tipo Documento</div>
																<select tabindex="-1" id="combo-tipodocumento" class="form-control formulario no-tab"
																	data-bind="
                              options : $parent.TiposDocumento,
                              optionsValue : 'id' ,
                              optionsText : 'value',
                              event:{keydown : OnKeyEnter}">
																</select>
															</div>
														</div>
														<button id="btn_buscardocumentoreferencia" class="btn btn-primary btn-control" type="button"
															name="button" data-bind="click: AbrirConsultaComprobanteVenta">Buscar</button>
													</div>
													<div class="col-md-9">
														<table class="datalist__table table display no-footer" width="100%" data-products="brand">
															<thead>
																<tr>
																	<th class="products__title">Serie/Número</th>
																	<th class="products__title">Fecha</th>
																	<th class="products__title">Forma Pago</th>
																	<th class="products__title">Gravada</th>
																	<th class="products__title">No Gravada</th>
																	<th class="products__title">Des. Global</th>
																	<th class="products__title">IGV</th>
																	<th class="products__title">Total</th>
																	<th class="products__title">Saldo</th>
																	<th class="products__title">&nbsp;</th>
																</tr>
															</thead>
															<tbody>
																<!-- ko foreach : MiniComprobantesVentaNC -->
																<tr class="clickable-row" data-bind="" style="text-transform: UpperCase;">
																	<td data-bind="text: Documento">F001-1286</td>
																	<td data-bind="text: FechaEmision">10/05/2018</td>
																	<td data-bind="text: NombreFormaPago">CONTADO</td>
																	<td data-bind="text: ValorVentaGravado">100.00</td>
																	<td data-bind="text: ValorVentaNoGravado">00.00</td>
																	<td data-bind="text: DescuentoGlobal">00.00</td>
																	<td data-bind="text: IGV">18.00</td>
																	<td data-bind="text: Total">118.00</td>
																	<td data-bind="text: SaldoNotaCredito">100.00</td>
																	<td class="col-sm-auto">
																		<div class="input-group ajuste-opcion-plusminus">
																			<button type="button" class="btn btn-danger no-tab"
																				data-bind="event:{click: EliminarComprobanteVenta}">X</button>
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
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<br>

				<div class="row vista_detallesComprobanteVenta">
					<div class="col-md-12">
						<fieldset>
							<table id="tablaDetalleComprobanteVenta" class="datalist__table table display no-footer" width="100%"
								data-products="brand">
								<thead>
									<tr>
										<th class="col-sm-1 products__id op-codigo">
											<center>Código</center>
										</th>
										<th class="col-sm-6 products__title">Descripción</th>
										<th class="col-sm-1 products__title op-unidad">
											<center>Unidad</center>
										</th>
										<th class="col-sm-1 products__title">
											<center>Cantidad</center>
										</th>
										<th class="col-sm-1 products__title">
											<center>P. U.</center>
										</th>
										<th class="NotaCredito_Todos DetalleNotaCredito_Descuento col-sm-1 products__title">
											<center>Desc.</center>
										</th>
										<th class="NotaCredito_Todos DetalleNotaCredito_DescuentoUnitario col-sm-1 products__title">
											<center>Desc. Unitario</center>
										</th>
										<th class="col-sm-1 products__title">
											<center>Importe</center>
										</th>
									</tr>
								</thead>
								<tbody>
									<!-- ko foreach : DetallesNotaCredito -->
									<tr class="clickable-row" style="min-height: 80px;"
										data-bind="attr : { id: IdDetalleComprobanteVenta }, click : $parent.Seleccionar , event : {focusout : $parent.Refrescar}">
										<td class="col-sm-1 op-codigo">
											<input
												class="NotaCredito_Todos DetalleNotaCredito_Codigo form-control input-detallecomprobanteventa formulario"
												data-bind="value: CodigoMercaderia, valueUpdate : 'keyup',
              attr : { id : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'},
              event : {keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); } , focusout : ValidarCodigoMercaderia}"
												type="text" data-validation="validacion_producto"
												data-validation-error-msg="No existe Codigo Producto" data-validation-found="false">
										</td>
										<td class="col-sm-6">
											<input
												class="NotaCredito_Todos DetalleNotaCredito_Descripcion form-control input-detallecomprobanteventa formulario"
												data-bind="value: NombreProducto,
            attr : { id : IdDetalleComprobanteVenta() + '_input_NombreProducto',
            'data-validation-reference' : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia' },
            event: { focus : $parent.Seleccionar , keydown : $parent.OnKeyEnter }" type="text">
										</td>
										<td class="col-sm-1  text-center op-unidad">
											<span class="input-detallecomprobanteventa" data-bind="text : AbreviaturaUnidadMedida , visible : true ,
            attr : { id : IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida'}"></span>
										</td>
										<td class="col-sm-1">
											<input name="Cantidad"
												class="NotaCredito_Todos DetalleNotaCredito_Cantidad form-control input-detallecomprobanteventa formulario numeric text-mumeric"
												data-bind="value : Cantidad ,
            visible : true , attr : { id : IdDetalleComprobanteVenta() + '_input_Cantidad' },
            event: {focus: CopiaDetalle, blur: CalcularSubTotalItem , keydown : $parent.OnKeyEnter} ,
            numbertrim: Cantidad " type="text" data-validation="number_calc"
												data-validation-allowing="float,positive,range[0.001;9999999]"
												data-validation-decimal-separator="." data-validation-error-msg="Debe ser de 0 a más">
										</td>
										<td class="col-sm-1">
											<input name="PrecioUnitario"
												class="NotaCredito_Todos DetalleNotaCredito_PrecioUnitario form-control input-detallecomprobanteventa formulario numeric text-mumeric"
												data-bind="value : PrecioUnitario ,
            visible : true , attr : { id : IdDetalleComprobanteVenta() + '_input_PrecioUnitario' },
            event: {focus : $parent.Seleccionar, blur: CalcularSubTotalItem, keydown : $parent.OnKeyEnter} "
												type="text" data-validation="number_calc"
												data-validation-allowing="float,positive,range[0.001;9999999]"
												data-validation-decimal-separator="." data-validation-error-msg="Debe ser positivo">
										</td>
										<td class="NotaCredito_Todos DetalleNotaCredito_Descuento col-sm-1">
											<input name="DescuentoItem"
												class="NotaCredito_Todos DetalleNotaCredito_Descuento form-control input-detallecomprobanteventa formulario numeric text-mumeric"
												data-bind="value : DescuentoUnitario ,
            visible : true , attr : { id : IdDetalleComprobanteVenta() + '_input_DescuentoItem' },
            event: {focus : $parent.Seleccionar, blur: CalcularSubTotalItem, keydown : $parent.OnKeyEnter}" type="text"
												data-validation="number_calc" data-validation-allowing="float,positive,range[0;9999999]"
												data-validation-decimal-separator="." data-validation-error-msg="Debe ser de 0 a más">
										</td>
										<td class="NotaCredito_Todos DetalleNotaCredito_DescuentoUnitario col-sm-1">
											<input
												class="NotaCredito_Todos DetalleNotaCredito_DescuentoUnitario form-control input-detallecomprobanteventa formulario numeric text-mumeric"
												data-bind="value : DescuentoUnitario,
            visible : true , attr : { id : IdDetalleComprobanteVenta() + '_span_DescuentoUnitario'},
            event: {blur: CalcularImporteByDescuentoUnitario, focusout : ValidarDescuentoUnitario, focus: CopiaDetalle, keydown : $parent.OnKeyEnter},
            numbertrim: DescuentoUnitario" type="text" data-validation="number_calc"
												data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="."
												data-validation-error-msg="De 0 a más">
										</td>
										<td class="col-sm-1">
											<input
												class="NotaCredito_Todos DetalleNotaCredito_Importe form-control input-detallecomprobanteventa formulario numeric text-mumeric"
												data-bind="value : SubTotal,
            visible : true , attr : { id : IdDetalleComprobanteVenta() + '_span_SubTotal'},
            event: {blur: CalcularDescuentoUnitarioByImporte, focusout : ValidarImporte, focus: CopiaDetalle, keydown : $parent.OnKeyEnter},
            numbertrim: SubTotal" type="text" data-validation="number_calc"
												data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="."
												data-validation-error-msg="De 0 a más">
										</td>
										<td class="col-sm-auto">
											<div class="input-group ajuste-opcion-plusminus">
												<button type="button"
													class="NotaCredito_Todos DetalleNotaCredito_AgregarEliminar btn btn-default focus-control glyphicon glyphicon-minus no-tab"
													data-bind="click : $parent.QuitarDetalleComprobanteVenta, attr : { id : IdDetalleComprobanteVenta() + '_a_opcion'}"></button>
											</div>
										</td>
									</tr>
									<!-- /ko -->
								</tbody>
							</table>
						</fieldset>
					</div>
				</div>

				<div class="row vista_concepto" style="display:none;">
					<div class="col-md-8">

					</div>
					<div class="col-md-4">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon ancho">Saldo Comprobantes</div>
								<input id="TotalFacturas" tabindex="-1" readonly
									class="NotaCredito_Todos NotaCredito_TotalFacturas form-control formulario numeric text-mumeric no-tab"
									type="text" placeholder="TotalFacturas" data-bind="value: TotalSaldo">
							</div>
						</div>
					</div>
				</div>
				<div class="row vista_concepto" style="display:none;">
					<div class="col-md-12">
						<table id="tablaConceptoComprobanteVenta" class="datalist__table table display no-footer" width="100%"
							data-products="brand">
							<thead>
								<tr>
									<th class="NotaCredito_Todos NotaCredito_Concepto col-sm-8 products__title">Concepto</th>
									<th class="NotaCredito_Todos NotaCredito_Porcentaje col-sm-2 products__title">
										<center>Porcentaje</center>
									</th>
									<th class="NotaCredito_Todos NotaCredito_Importe col-sm-2 products__title">
										<center>Importe</center>
									</th>
								</tr>
							</thead>
							<tbody>

								<tr class="clickable-row" style="min-height: 80px;" data-bind="">
									<td class="NotaCredito_Todos NotaCredito_Concepto col-sm-8">
										<select id="combo-conceptonotacredito"
											class="NotaCredito_Todos NotaCredito_Concepto form-control formulario" data-bind="
            value : Concepto,
            options : ConceptosNotaCredito,
            optionsValue : 'IdConceptoNotaCreditoDebito' ,
            optionsText : 'NombreConceptoNotaCreditoDebito',
            event:{keydown : OnKeyEnter}">
										</select>
									</td>
									<td class="NotaCredito_Todos NotaCredito_Porcentaje col-sm-2">
										<input id="input_porcentaje" name="Porcentaje"
											class="NotaCredito_Todos NotaCredito_Porcentaje form-control formulario numeric text-mumeric"
											data-bind="value : Porcentaje ,
          visible : true,
          event:{blur: CalcularTotalByPorcentaje, focus: OnFocus, keydown : OnKeyEnter, focusout: ValidarImporte},
          numbertrim: Porcentaje " type="text" data-validation="number_calc"
											data-validation-allowing="float,positive,range[1;9999999]" data-validation-decimal-separator="."
											data-validation-error-msg="Debe ser positivo, mayor a 0 y menor igual a 100">
									</td>
									<td class="NotaCredito_Todos NotaCredito_Importe col-sm-2">
										<input id="input_importe" name="Importe"
											class="NotaCredito_Todos NotaCredito_Importe form-control formulario numeric text-mumeric"
											data-bind="value : Importe ,
          visible : true,
          event:{blur: CalcularPorcentaje, focus: OnFocus, keydown : OnKeyEnter, focusout: ValidarImporte},
          numbertrim: Importe" type="text" data-validation="number_calc"
											data-validation-allowing="float,positive,range[1;9999999]" data-validation-decimal-separator="."
											data-validation-error-msg="Debe ser positivo, mayor a 0">
									</td>
								</tr>

							</tbody>
						</table>
					</div>
				</div>

				<div class="row vista_detallesComprobanteVenta">
					<div class="col-md-12 denominacion-moneda-nacional">
						<span data-bind="html : DenominacionTotal()" id="nletras"></span>
					</div>
				</div>

				<br>
				<div id="footer_notacredito" class="row">
<!-- 					<div class="col-md-3">
						<div class="mostrarnota" style="display: none;">
							<h4>Nota:</h4>
							<span data-bind="text: NotaUsuario">No se pueden hacer modificaciones de la vida</span><BR>
							<strong class="alert-info">*LA OPERACION GRABADA DEBE TENER MONTOS 00.00.</strong>
						</div>
					</div> -->

					<div class="col-md-3" data-bind="visible: IdMotivoNotaCredito() == 3">
						<div>
							<h4>Nota:</h4>
							<strong class="alert-info">*LA OPERACION GRABADA DEBE TENER MONTOS 00.00.</strong>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<div class="addon-top">Op. Gravada</div>
							<input id="ValorVentaGravado"
								class="NotaCredito_Todos NotaCredito_Gravada form-control formulario numeric text-mumeric input-totales"
								type="text" placeholder="Op. Grav."
								data-bind="value: ValorVentaGravado, event: {focus : OnFocus , focusout: CalcularTotalesByFooter, keydown : OnKeyEnter},numbertrim: ValorVentaGravado">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<div class="addon-top">Op. Exonerado</div>
							<input id="ValorVentaNoGravado"
								class="NotaCredito_Todos NotaCredito_NoGravada form-control formulario numeric text-mumeric input-totales"
								type="text" placeholder="Op. No Grav."
								data-bind="value: ValorVentaNoGravado, event: {focus : OnFocus , focusout: CalcularTotalesByFooter, keydown : OnKeyEnter},numbertrim: ValorVentaNoGravado">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<div class="addon-top">Op. Inafecto</div>
							<input id="ValorVentaInafecto"
								class="NotaCredito_Todos NotaCredito_Inafecto form-control formulario numeric text-mumeric no-tab input-totales"
								type="text" placeholder="Op. Ina."
								data-bind="value: ValorVentaInafecto, event: {focus : OnFocus , focusout: CalcularTotalesByFooter, keydown : OnKeyEnter},numbertrim: ValorVentaInafecto">
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<div class="addon-top">IGV</div>
							<input id="IGV"
								class="NotaCredito_Todos NotaCredito_IGV form-control formulario numeric text-mumeric input-totales"
								type="text" placeholder="IGV"
								data-bind="value: CalculoTotalIGV(), keydown : OnKeyEnter, numbertrim: IGV">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<div class="addon-top">Total</div>
							<input id="Total"
								class="NotaCredito_Todos NotaCredito_Total form-control formulario numeric text-mumeric input-totales"
								type="text" placeholder="Total" data-bind="value : CalculoTotalVenta(), keydown : OnKeyEnter">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br />

	<div class="row">
		<center>
			<button type="button" id="btn_Grabar" class="btn btn-success focus-control focus-tab"
				data-bind="click : Guardar">Grabar</button> &nbsp;
			<button type="button" id="btn_Limpiar" class="btn btn-default focus-control"
				data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
			<button type="button" id="BtnDeshacer" class="btn btn-default"
				data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
			<button type="button" id="btn_Cerrar" class="btn btn-default"
				data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
		</center>
	</div>
</form>
<!-- /ko -->
