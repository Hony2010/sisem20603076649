<!-- ko with : vmgReporteRemuneracionEmpleadosMetaMensual.dataReporteRemuneracionEmpleadosMetaMensual -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Remuneracion de Empleados por Meta Mensual</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form id="FormReporteRemuneracionEmpleadosMetaMensual">
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
																			<div class="input-group-addon">Mes</div>
																			<select id="Mes" class="form-control formulario">
																				<option value="01">Enero</option>
																				<option value="02">Febrero</option>
																				<option value="03">Marzo</option>
																				<option value="04">Abril</option>
																				<option value="05">Mayo</option>
																				<option value="06">Junio</option>
																				<option value="07">Julio</option>
																				<option value="08">Agosto</option>
																				<option value="09">Septiembre</option>
																				<option value="10">Octubre</option>
																				<option value="11">Noviembre</option>
																				<option value="12">Diciembre</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon">Año</div>
																			<input id="Anio" class="form-control formulario año-reporte" type="text"
																				data-validation="date" data-validation-format="yyyy"
																				data-validation-error-msg="La año es invalida"
																				data-bind="value: Anio, event: { focusout : ValidarFecha }">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</fieldset>
													<br>
													<!-- <fieldset>
                            <legend>Vendedores</legend>
                            <div class="col-md-12">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="multiselect-native-select formulario">
                                    <button type="button" class="multiselect dropdown-toggle btn btn-default btn-control" data-toggle="dropdown">
                                      <span class="multiselect-selected-text">VENDEDORES </span>
                                      <span class="badge" data-bind="text: NumeroVendedoresSeleccionados"></span>
                                      <b style="float: right;margin: 5px;" class="caret"></b>
                                    </button>
                                    <ul class="multiselect-container dropdown-menu" style="width: 100%;">
                                      <li>
                                        <div class="checkbox">
                                          <input id="SelectorVendedoresMetaMesual" type="checkbox" data-bind="event: { change: SeleccionarTodosVendedores }" />
                                          <label for="SelectorVendedoresMetaMesual" class="checkbox"> Seleccionar Todos</label>
                                        </div>
                                      </li> -->
													<!-- ko foreach: Vendedores -->
													<!-- <li>
                                        <div class="checkbox">
                                          <input type="checkbox" data-bind="attr : { id: IdUsuario() +'_VendedorMetaMesual' }, event: {change: $parent.SeleccionarVendedor}" />
                                          <label class="checkbox" data-bind="text: AliasUsuarioVenta() + ' - ' + RazonSocial(), attr:{ for : IdUsuario() +'_VendedorMetaMesual'}"></label>
                                        </div>
                                      </li> -->
													<!-- /ko -->
													<!-- </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </fieldset> -->
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
														<div class="row text-center">
															<hr>
															<button id="btnexcel" type="button" name="excel" class="btn btn-default"
																data-bind="event: { click: DescargarReporteExcel }"> E<b><u>x</u></b>cel </button>
															&nbsp;
															<button id="btnpdf" type="button" name="pdf" class="btn btn-default"
																data-bind="event: { click: DescargarReportePdf }"> PDF </button> &nbsp;
															<button id="btnpantalla" type="button" class="btn btn-default"
																data-bind="event: { click: MostrarReportePantalla }"> Pantalla </button> &nbsp;
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
