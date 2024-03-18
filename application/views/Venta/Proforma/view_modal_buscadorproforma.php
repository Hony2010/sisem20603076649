<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalBuscadorProforma"
	data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">Buscador de proformas</h5>
			</div>
			<div class="modal-body">
				<!-- ko with: BusquedaProformaVenta -->
				<br>
				<div class="row">
					<div class="col-md-12">
						<fieldset>
							<form action="">
								<!-- ko with: FiltrosBusquedaVentas -->
								<div class="col-md-3">
									<div class="form-group">
										<label for="">Cliente</label>
										<input id="ClienteProforma" type="text" class="form-control formulario"
											data-bind="
											value: ClienteProforma(), event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, change: $parent.ValidarClienteProforma }"
											data-validation="autocompletado_cliente" data-validation-error-msg="" data-validation-text-found="">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="">Fecha Incial</label>
										<input type="text" class="form-control formulario fecha"
											data-bind="value: FechaInicio, event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter}">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="">Fecha Final</label>
										<input type="text" class="form-control formulario fecha"
											data-bind="value: FechaFin, event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter}">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="">Vendedor</label>
										<select class="form-control formulario" data-bind="
										value: IdUsuarioVendedor,
										options: Vendedores,
										optionsValue : 'IdUsuario',
										optionsText : 'AliasUsuarioVenta',
										optionsCaption : 'Todos',
										event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter}"></select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="">Estado</label>
										<select class="form-control formulario" data-bind="value: EstadoComprobante">
											<option value="P">Pendiente</option>
											<option value="P">Utilizado</option>
										</select>
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for="">&nbsp;</label>
										<button type="button" class="btn btn-control btn-primary"
											data-bind="event: { click: $parent.OnClickBtnBuscarProformas }">Buscar</button>
									</div>
								</div>
								<!-- /ko -->
							</form>
						</fieldset>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<fieldset>
							<form action="">
								<div class="col-md-12">
									<table class="table">
										<thead>
											<tr>
												<th>Documento</th>
												<th>RUC/DNI</th>
												<th>Cliente</th>
												<th>Fecha Emisión</th>
												<th>Total</th>
												<th>Forma Pago</th>
												<th>Vendedor</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<!-- ko foreach: ComprobantesVentaProforma -->
											<tr>
												<td>
													<span data-bind="text: Documento"></span>
												</td>
												<td>
													<span data-bind="text: NumeroDocumentoIdentidad"></span>
												</td>
												<td>
													<span data-bind="text: RazonSocial"></span>
												</td>
												<td>
													<span data-bind="text: FechaEmision"></span>
												</td>
												<td>
													<span data-bind="text: Total"></span>
												</td>
												<td>
													<span data-bind="text: NombreFormaPago"></span>
												</td>
												<td>
													<span data-bind="text: AliasUsuarioVenta"></span>
												</td>
												<td>
													<div class="checked">
														<input id="CheckProformaSeleccionado" type="checkbox"
															data-bind="checked: CheckProformaSeleccionado">
														<label for="CheckProformaSeleccionado"></label>
													</div>
												</td>
											</tr>
											<!-- /ko -->
										</tbody>
									</table>
								</div>
							</form>
						</fieldset>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12 text-center">
						<button class="btn btn-success" data-bind="event: {click: OnClickAgregarProformas}">Agregar
							Comprobantes</button>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<table>
							<tr>
								<td>
									<div id="PaginadorBusquedaProformas">
									</div>
								</td>
								<td style="padding-bottom:5px;">
									<h5>Se encontraron <span
											data-bind="text: ComprobantesVentaProforma().length"></span> registros</h5>
								<td>
							</tr>
						</table>
					</div>
				</div>
				<!-- /ko -->
			</div>
		</div>
	</div>
</div>
