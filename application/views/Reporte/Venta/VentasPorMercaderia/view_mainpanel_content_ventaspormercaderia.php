<!-- ko with : vmgReporteVentasPorMercaderia.dataReporteVentasPorMercaderia -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Reporte de Ventas por Producto</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form class="form products-new" enctype="multipart/form-data" id="form_Mercaderia" name="form" action=""
								method="post">
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
																			<input id="FechaInicio_Mercaderia" name="FechaInicio_Mercaderia"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida" type="date"
																				data-bind="value:FechaInicio_Mercaderia, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon">Al</div>
																			<input id="FechaFinal_Mercaderia" name="FechaFinal_Mercaderia"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaFinal_Mercaderia, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</fieldset>
													<fieldset>
														<legend>Producto</legend>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="input-group">
																			<div class="input-group-addon addonrd">
																				<input class="radiobtn" type="radio" name="IdProducto_Mercaderia" value="0"
																					data-bind="checked:IdProducto_Mercaderia, event:{change:GrupoCliente_Mercaderia}">
																			</div>
																			<div class="form-group radiotxt">Todos</div>
																		</label>
																	</div>
																</div>
																<div class="col-md-8">
																	<div class="form-group">
																		<label class="input-group">
																			<div class="input-group-addon addonrd">
																				<input class="radiobtn" type="radio" name="IdProducto_Mercaderia" value="1"
																					data-bind="checked:IdProducto_Mercaderia, event:{change:GrupoCliente_Mercaderia}">
																			</div>
																			<div id="DivBuscar_Mercaderia" class="form-group radiotxt">Buscar</div>
																			<input id="TextoBuscarOculto_Mercaderia" type="hidden"
																				name="TextoMercaderia_Mercaderia" data-bind="value: TextoMercaderia_Mercaderia">
																			<input id="TextoBuscar_Mercaderia" class="form-control formulario " name=""
																				type="hidden" placeholder="escribir para buscar....">
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
																<button id="btnexcel_Mercaderia" type="button" name="excel" class="btn btn-default"
																	data-bind="event:{click:DescargarReporte_Mercaderia}"> E<b><u>x</u></b>cel </button>
																&nbsp;
																<button id="btnpdf_Mercaderia" type="button" name="pdf" class="btn btn-default"
																	data-bind="event:{click:DescargarReporte_Mercaderia}"> PDF </button> &nbsp;
																<button id="btnpantalla_Mercaderia" type="button" class="btn btn-default"
																	data-bind="event:{click:Pantalla_Mercaderia}"> Pantalla </button> &nbsp;
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
