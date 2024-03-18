<!-- ko with : vmgProductosMasVendidos.dataProductosMasVendidos -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Reporte de Productos más Vendidos</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form class="form products-new" enctype="multipart/form-data" id="form_MAS" name="form"
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
																			<input id="FechaInicio_MAS"
																				name="FechaInicio_MAS"
																				class="form-control formulario fecha-reporte"
																				type="text"
																				data-inputmask-clearmaskonlostfocus="false"
																				data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaInicio_MAS, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Al</div>
																			<input id="FechaFinal_MAS"
																				name="FechaFinal_MAS"
																				class="form-control formulario fecha-reporte"
																				type="text"
																				data-inputmask-clearmaskonlostfocus="false"
																				data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaFinal_MAS, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</fieldset>
													<fieldset>
														<legend>Ordenado por</legend>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="input-group">
																			<div class="input-group-addon addonrd">
																				<input class="radiobtn" type="radio"
																					name="OrdenadoPor" value="1"
																					data-bind="checked: OrdenadoPor">
																			</div>
																			<div class="form-group radiotxt">Cantidad
																			</div>
																		</label>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="input-group">
																			<div class="input-group-addon addonrd">
																				<input class="radiobtn" type="radio"
																					name="OrdenadoPor" value="2"
																					data-bind="checked: OrdenadoPor">
																			</div>
																			<div class="form-group radiotxt">Monto</div>
																		</label>
																	</div>
																</div>
															</div>
														</div>
													</fieldset>
													<fieldset>
														<legend>Cantidad de productos a mostrar</legend>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="input-group">
																			<div class="input-group-addon addonrd">
																				<input class="radiobtn" type="radio"
																					name="CantidadFilas_MAS" value="0"
																					data-bind="checked:CantidadFilas_MAS">
																			</div>
																			<div class="form-group radiotxt">Sólo los 10
																				primeros</div>
																		</label>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="input-group">
																			<div class="input-group-addon addonrd">
																				<input class="radiobtn" type="radio"
																					name="CantidadFilas_MAS" value="1"
																					data-bind="checked:CantidadFilas_MAS">
																			</div>
																			<div class="form-group radiotxt">Todos</div>
																		</label>
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
																<!-- <br> -->
																<button id="btnexcel_MAS" name="excel" type="submit"
																	class="btn btn-default"
																	data-bind="event : {click : DescargarReporte_MAS}">
																	E<b><u>x</u></b>cel</button>
																&nbsp;
																<button id="btnpdf_MAS" name="pdf" type="submit"
																	class="btn btn-default"
																	data-bind="event : {click : DescargarReporte_MAS}">
																	PDF </button> &nbsp;
																<button id="btnpantalla_MAS" type="submit"
																	class="btn btn-default"
																	data-bind="event:{click:Pantalla_MAS}"> Pantalla
																</button> &nbsp;
																<!-- <button class="btn btn-default" >Gráfico</button> &nbsp; -->
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
