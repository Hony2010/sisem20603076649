<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title" style="margin-top: 6px;">
			Cobranza Rapida de Clientes
		</h3>
	</div>
	<div class="panel-body">
		<div class="">
			<div class="tab-pane active" id="brand" role="tabpanel">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="FechaInicio">&nbsp;</label>
								<div>
									<div class="radio radio-inline">
										<input id="IdRolVendedor" type="radio" name="IdRol" value="1"
											data-bind="checked: Filtros.IdRol, event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, change: $parent.OnChangeRol }">
										<label for="IdRolVendedor">Vendedor</label>
									</div>
									<div class="radio radio-inline">
										<input id="IdRolCliente" type="radio" name="IdRol" value="2"
											data-bind="checked: Filtros.IdRol, event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, change: $parent.OnChangeRol }">
										<label for="IdRolCliente">Cliente</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4" data-bind="visible: Filtros.IdRol() == '1'">
							<div class="form-group">
								<label for="RazonSocialVendedor">Vendedor</label>
								<input id="RazonSocialVendedor" type="text" class="form-control formulario"
									data-bind="value: $parent.RazonSocialVendedor(), event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, blur: $parent.ValidarVendedor }"
									data-validation="autocompletado_vendedor"
									data-validation-error-msg="No se han encontrado resultados para tu búsqueda"
									data-validation-text-found="">
							</div>
						</div>
						<div class="col-md-4" data-bind="visible: Filtros.IdRol() == '2'">
							<div class="form-group">
								<label for="RazonSocialCliente">Cliente</label>
								<input id="RazonSocialCliente" type="text" class="form-control formulario"
									data-bind="value: $parent.RazonSocialVendedor(), event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, blur: $parent.ValidarVendedor }"
									data-validation="autocompletado_vendedor"
									data-validation-error-msg="No se han encontrado resultados para tu búsqueda"
									data-validation-text-found="">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="FechaInicio">Fecha Inicio:</label>
								<input id="FechaInicio" type="text" class="form-control formulario fecha"
									data-bind="value: Filtros.FechaInicio, event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter }">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="FechaFin">Fecha Fin:</label>
								<input id="FechaFin" type="text" class="form-control formulario fecha"
									data-bind="value: Filtros.FechaFin, event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter }">
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label for="FechaInicio">&nbsp;</label>
								<button type="button" class="btn btn-primary btn-control"
									data-bind="event: { click: (data, event) => $parent.OnClickBtnCargarCobranzas(data, event, Filtros)}">Cargar</button>
							</div>
						</div>
					</div>
					<div class="row">
						<form class="col-md-12" action="">
							<fieldset>
								<table class="table grid-detail-body">
									<thead>
										<tr>
											<th>Nuevo Documento</th>
											<th>Hora</th>
											<th>Cliente</th>
											<th>Total</th>
											<th>Cobrado</th>
											<th>Deuda</th>
											<th>Monto a Cobrar</th>
											<th>Cobrador</th>
											<th>Fecha Cobro</th>
											<th>N° Recibo Ref.</th>
											<th></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<!-- ko foreach : CobranzasCliente -->
										<tr data-bind="event: { click: OnClickNuevaCobranza }">
											<td>
												<div class="form-group">
													<input type="text" class="form-control formulario" data-bind="
														value: DocumentoReferencia,
														attr: { id : 'inputDocumentoReferencia_' + IdComprobanteCaja(), 'data-validation-text-found': DocumentoReferencia } ,
														event: { focus: OnFocus, keydown: $root.OnKeyEnter, blur: ValidarDocumentoReferencia }"
														data-validation="autocompletado_documento"
														data-validation-error-msg="">
												</div>
											</td>
											<td>
												<span data-bind="text: HoraEmision"></span>
											</td>
											<td>
												<span data-bind="text: RazonSocial"></span>
											</td>
											<td>
												<span data-bind="text: MontoOriginal"></span>
											</td>
											<td>
												<span data-bind="text: MontoCobrado"></span>
											</td>
											<td>
												<span data-bind="text: SaldoPendiente"></span>
											</td>
											<td>
												<div class="form-group">
													<input class="form-control formulario text-right" type="text"
														data-bind="
														value: MontoACobrar,
														numbertrim: MontoACobrar,
														event: { change: OnChangeInputMontoACobrar, focus: OnFocus, keydown: $root.OnKeyEnter }">
												</div>
											</td>
											<td>
												<div class="form-group">
													<select class="form-control formulario" data-bind="
														value : UsuarioCobrador,
														options : Cobradores,
														optionsValue : 'AliasUsuarioVenta' ,
														optionsText : 'AliasUsuarioVenta' ,
														event : { focus : OnFocus, keydown : $root.OnKeyEnter }">
													</select>
												</div>
											</td>
											<td width="90">
												<div class="form-group">
													<input id="FechaCobro" type="text"
														class="form-control formulario fecha"
														data-bind="value: FechaComprobante, event: { focus: OnFocus, keydown: $root.OnKeyEnter }">
												</div>
											</td>
											<td>
												<div class="form-group">
													<input class="form-control formulario" type="text" data-bind="
													value: NumeroRecibo,
													event: { focus: OnFocus, keydown: $root.OnKeyEnter }">
												</div>
											</td>
											<td width="40">
												<button type="button" class="btn btn-info btn-consulta" data-bind="
													visible: !UltimoItem(),
													event: { click: $root.OnClickBtnVerComprobante }">
													<span class="fa fa-fw fa-eye"></span>
												</button>
											</td>
											<td width="40">
												<button type="button"
													class="btn btn-danger btn-consulta glyphicon glyphicon-minus no-tab"
													data-bind="
													visible: !UltimoItem(),
													event: { click: $root.OnClickBtnRemoveCobranza }"></button>
											</td>
										</tr>
										<!-- /ko -->
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5" class="text-right">
												<h5>Total A Cobrar</h5>
											</td>
											<td style="vertical-align: middle;">
												<input type="text" class="form-control formulario text-right" readonly
													data-bind="value: $root.TotalCobranzaRapida">
											</td>
											<td colspan="4"></td>
										</tr>
									</tfoot>
								</table>
							</fieldset>
						</form>
					</div>
					<br>
					<div class="row">
						<div class="form-group text-center">
							<button type="button" class="btn btn-success" data-bind=" 
								disable: $root.OnDisableBtnGrabar,
								event: {click: $root.OnClickBtnGuardarCobranza } ">Guardar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
