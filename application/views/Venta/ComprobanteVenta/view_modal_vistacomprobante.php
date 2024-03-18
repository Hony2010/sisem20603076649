<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalVistaComprobanteVenta"
	data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="panel-title">Vista de Comprobante Venta</h3>
			</div>
			<div class="modal-body">
				<!-- ko with : ComprovanteVenta -->
				<form id="formComprobanteVenta" name="formComprobanteVenta" role="form" autocomplete="off">
					<div class="datalist__result">
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
																<div class="input-group-addon formulario-venta">Serie
																</div>
																<input readonly type="text"
																	class="form-control formulario"
																	data-bind="value: SerieDocumento">
															</div>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario-venta">Número
																</div>
																<input readonly class="form-control formulario no-tab"
																	type="text" data-bind="value: NumeroDocumento">
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
														</div>
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario-venta">F.
																	Emisión</div>
																<input readonly class="form-control formulario"
																	data-bind="value: FechaEmision" />
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario-venta">RUC -
																	Cliente</div>
																<input readonly type="text"
																	class="form-control formulario" type="text"
																	data-bind="value : RazonSocial">
															</div>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario-venta">Forma
																	Pago</div>
																<input readonly type="text"
																	class="form-control formulario" type="text"
																	data-bind="value : NombreFormaPago">
															</div>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario-venta">F.
																	Vencimiento</div>
																<input readonly class="form-control formulario"
																	data-bind="value: FechaVencimiento" />
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario-venta">
																	Dirección</div>

																<input readonly class="form-control formulario"
																	data-bind="value: Direccion" />
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario-venta">Otros
																	Datos</div>
																<input readonly type="text"
																	class="form-control formulario"
																	data-bind="value: Observacion">
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario-venta">Alamcén</div>
																<input readonly type="text"
																	class="form-control formulario"
																	data-bind="value: NombreSede">
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon formulario-venta">Caja</div>
																<input readonly type="text"
																	class="form-control formulario"
																	data-bind="value: NombreCaja">
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
											<div class="col-md-12">
												<div class="row detalle-comprobante">
													<div class="col-md-12">
														<table
															class="datalist__table table display table-border"
															width="100%" id="tablaDetalleComprobanteVenta">
															<thead>
																<tr>
																	<th class="">#</th>
																	<th class="text-center"> Código </th>
																	<th class=""> Descripción </th>
																	<th class="text-center"> Unid. </th>
																	<th class="text-center"> Cantidad </th>
																	<th class="text-center"> P. U. </th>
																	<th class="text-center"> Importe </th>
																</tr>
															</thead>
															<tbody>
																<!-- ko foreach : DetallesComprobanteVenta -->
																<tr name="Fila" class="clickable-row">
																	<td data-bind="text: NumeroItem"
																		style="vertical-align: middle;">
																	</td>
																	<td class="col-sm-2">
																		<div class="input-group">
																			<input readonly
																				class="form-control formulario"
																				data-bind="value: CodigoMercaderia">
																		</div>
																	</td>
																	<td class="col-sm-4">
																		<div class="input-group">
																			<input readonly
																				class="form-control formulario"
																				data-bind="value: NombreProducto">
																		</div>
																	</td>
																	<td class="text-center">
																		<span class=""
																			data-bind="text: AbreviaturaUnidadMedida"></span>
																	</td>
																	<td class="col-sm-1">
																		<input readonly class="form-control formulario"
																			data-bind="value: Cantidad">
																	</td>
																	<td class="col-sm-1">
																		<input readonly class="form-control formulario"
																			data-bind="value: PrecioUnitario">
																	</td>
																	<td class="col-sm-1">
																		<div class="input-group">
																			<input readonly
																				class="form-control formulario"
																				data-bind="value: SubTotal">
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
										<div class="col-md-2">
											<div class="form-group">
												<div class="addon-top">Op. Gravada</div>
												<input readonly id="ValorVentaGravado"
													class="form-control formulario numeric text-mumeric no-tab input-totales"
													type="text" data-bind="value: ValorVentaGravado">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<div class="addon-top">Op. Exonerado</div>
												<input readonly id="ValorVentaNoGravado"
													class="form-control formulario numeric text-mumeric no-tab input-totales"
													type="text" data-bind="value: ValorVentaNoGravado">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<div class="addon-top">Op. Inafecto</div>
												<input readonly id="ValorVentaInafecto"
													class="form-control formulario numeric text-mumeric no-tab input-totales"
													type="text" data-bind="value: ValorVentaInafecto">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<div class="addon-top">(Descto. Global)</div>
												<input readonly id="DescuentoGlobal"
													class="form-control formulario numeric text-mumeric input-totales"
													type="text" placeholder="Des. Global"
													data-bind="value: DescuentoGlobal">
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<div class="addon-top">ICBPER</div>
												<input readonly id="ICBPER"
													class="form-control formulario numeric text-mumeric no-tab input-totales"
													type="text" data-bind="value: ICBPER">
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<div class="addon-top">IGV</div>
												<input readonly id="IGV"
													class="form-control formulario numeric text-mumeric no-tab input-totales"
													type="text" data-bind="value: IGV">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<div class="addon-top">Total</div>
												<input readonly id="Total"
													class="form-control formulario numeric text-mumeric no-tab input-totales"
													type="text" data-bind="value: Total">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 text-center">
											<br>
											<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
				<!-- /ko -->

			</div>
		</div>
	</div>
</div>
<style>
	.table input{
		border-radius: 4px !important;
	}
</style>