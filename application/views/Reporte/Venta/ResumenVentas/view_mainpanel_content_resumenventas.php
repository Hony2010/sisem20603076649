<!-- ko with : vmgReporteResumenVentas.dataReporteResumenVentas -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Reporte de Resumen Ventas</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form class="form products-new" enctype="multipart/form-data" id="form_ResumenVentas" name="form"
								action="" method="post">
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
																			<input id="FechaInicio_ResumenVentas" name="FechaInicio_ResumenVentas"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida" type="date"
																				data-bind="value:FechaInicio_ResumenVentas, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon">Al</div>
																			<input id="FechaFinal_ResumenVentas" name="FechaFinal_ResumenVentas"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaFinal_ResumenVentas, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</fieldset>
													<br>
													<fieldset>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon formulario">Usuario</div>
																			<select id="Vendedor_ResumenVentas" name="Vendedor_ResumenVentas"
																				class="form-control formulario" data-bind="
                                            options :Vendedor,
                                            optionsValue :'AliasUsuarioVenta',
                                            optionsText : (item) => { return item.AliasUsuarioVenta() + ' - ' + item.RazonSocial(); },
                                            optionsCaption : 'Todos' ">
																			</select>
																		</div>
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
															<select id="combo-almacen" class="form-control formulario" data-bind="
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
																<button id="btnexcel_ResumenVentas" type="button" name="excel" class="btn btn-default"
																	data-bind="event:{click:DescargarReporte_ResumenVentas}"> E<b><u>x</u></b>cel
																</button> &nbsp;
																<button id="btnpdf_ResumenVentas" type="button" name="pdf" class="btn btn-default"
																	data-bind="event:{click:DescargarReporte_ResumenVentas}"> PDF </button> &nbsp;
																<button id="btnpantalla_ResumenVentas" type="button" class="btn btn-default"
																	data-bind="event:{click:Pantalla_ResumenVentas}"> Pantalla </button> &nbsp;
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
