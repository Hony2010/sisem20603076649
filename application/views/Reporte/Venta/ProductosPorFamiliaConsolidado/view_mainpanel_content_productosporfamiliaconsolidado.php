<!-- ko with : vmgReporteProductosPorFamiliaConsolidado -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Reporte de Productos por Familias (Consolidado)</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form class="form products-new" enctype="multipart/form-data"
								id="FormReporteProductosPorFamiliaConsolidado" action="" method="post">
								<div class="container-fluid">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12">
													<fieldset>
														<legend>Rango de fecha</legend>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Del</div>
																			<input
																				class="form-control formulario fecha-reporte"
																				type="text"
																				data-inputmask-clearmaskonlostfocus="false"
																				data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaInicio, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Al</div>
																			<input
																				class="form-control formulario fecha-reporte"
																				type="text"
																				data-inputmask-clearmaskonlostfocus="false"
																				data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaFinal, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!-- ko if: ParametroHoraReporte() == 1 -->
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Hora I.</div>
																			<input
																				class="form-control formulario hora-reporte"
																				type="text"
																				data-bind="value:HoraInicio">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon">Hora F.</div>
																			<input
																				class="form-control formulario hora-reporte"
																				type="text" data-bind="value:HoraFinal">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!-- /ko -->
													</fieldset>
													<br>
													<fieldset>
														<legend>Usuarios</legend>
														<div class="col-md-12">
															<div class="form-group">
																<div class="input-group">
																	<div class="multiselect-native-select formulario">
																		<button type="button"
																			class="multiselect dropdown-toggle btn btn-default btn-control"
																			data-toggle="dropdown">
																			<span
																				class="multiselect-selected-text">USUARIOS
																			</span>
																			<span class="badge"
																				data-bind="text: NumeroUsuariosSeleccionados"></span>
																			<b style="float: right;margin: 5px;"
																				class="caret"></b>
																		</button>
																		<ul class="multiselect-container dropdown-menu"
																			style="width: 100%;">
																			<li>
																				<div class="checkbox">
																					<input id="SelectorUsuarios"
																						type="checkbox"
																						data-bind="event: { change: SeleccionarTodosUsuarios }" />
																					<label for="SelectorUsuarios"
																						class="checkbox"> Seleccionar
																						Todos</label>
																				</div>
																			</li>
																			<!-- ko foreach: Usuarios -->
																			<li>
																				<div class="checkbox">
																					<input type="checkbox"
																						data-bind="attr : { id: IdUsuario() +'_Usuario' }, event: {change: $parent.SeleccionarUsuario}" />
																					<label class="checkbox"
																						data-bind="text: AliasUsuarioVenta() + ' - ' + RazonSocial(), attr:{ for : IdUsuario() +'_Usuario'}"></label>
																				</div>
																			</li>
																			<!-- /ko -->
																		</ul>
																	</div>
																</div>
															</div>
														</div>
													</fieldset>
													<br>
													<fieldset>
														<div class="input-group">
															<div class="input-group-addon formulario-venta">Almacén
															</div>
															<select id="combo-almacen" class="form-control formulario"
																data-bind="
																value: IdAsignacionSede,
																options: Almacenes,
																optionsValue: 'IdAsignacionSede' ,
																optionsText: 'NombreSede',
																optionsCaption: 'Todos'">
															</select>
														</div>
													</fieldset>
													<div class="col-md-12">
														<div class="row">
															<center>
																<hr>
																<button id="btnexcel" type="button"
																	class="btn btn-default"
																	data-bind="event: { click: DescargarReporteExcel }">
																	E<b><u>x</u></b>cel </button>
																&nbsp;
																<button id="btnpdf" type="button"
																	class="btn btn-default"
																	data-bind="event: { click: DescargarReportePdf }">
																	PDF </button>
																&nbsp;
																<button id="btnpantalla" type="button"
																	class="btn btn-default"
																	data-bind="event: { click: MostrarReportePantalla }">
																	Pantalla </button> &nbsp;
															</center>
														</div>
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
		</div>
	</div>
</div>
<!-- /ko -->
