<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="BuscadorMercaderia" data-backdrop='static'>
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="panel-title">BÚSQUEDA DE MERCADERÍAS</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid" style="padding:0px; min-height: 400px;">
					<br>
					<div class="row">
						<!-- ko with : BusquedaAvanzadaProducto -->
						<form class="" action="" method="post">
							<div class="col-md-10">
								<div class="form-group">
									<input class="form-control formulario" type="text"
										placeholder="Buscar Mercadería por Descripción o Código" data-bind="value: textofiltro">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-control" name="button"
										data-bind="event:{click: function(data, event){return Buscar(data, event, $root);} }"> Buscar
									</button>
								</div>
							</div>
						</form>
						<!-- /ko -->
					</div>
					<br>
					<div class="row">
						<div class="col-md-12">
							<fieldset>
								<div class="row">

									<div class="col-md-12">
										<!-- ko foreach : Mercaderias -->
										<div class="col-md-2 images_products">
											<div class="panel panel-sky" style="margin-bottom: 5px;">
												<div class="panel-heading" style="padding: 3px 8px;height:30px">
													<h3 class="panel-title"
														title="ASPIRADORA 1400WT 30LT ASPIRADORA 1400WT 30LT ASPIRADORA 1400WT 30LT"
														style="font-size:11px;display:block;display:-webkit-box;max-width:100%;margin:0 auto;line-height: 1;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;"
														data-bind="text: NombreProducto, attr:{title: NombreProducto}">ASPIRADORA 1400WT 30LT
														ASPIRADORA
														1400WT 30LT ASPIRADORA 1400WT 30LT</h3>
												</div>
												<div class="panel-body" style="padding: 8px; border-radius: 4px;">
													<div class="col-md-12">
														<div class="row">
															<div class="col-md-12 col-xs-12">
																<div class="products-preview__photo"
																	style="max-width: 100%; padding-right:0; height: 150px;">
																	<label class="label_badge_top_left hide"
																		data-bind="text: 'Cód: ' + CodigoMercaderia()">Cód:
																		CCCC-444454</label>
																	<label class="label_badge_bottom_right"
																		data-bind="text: 'Stock: ' + StockProducto(), css: ColorText">Stock: 1234.32</label>
																	<div>
																		<img style="width: 100%;height: 100%;" src="" alt=""
																			data-bind="attr:{src: Foto}, event: {click: OnClickImagenMercaderia}">
																	</div>
																</div>
															</div>
															<div class="col-md-12 col-xs-12">
																<div class="form-group col-md-6 col-xs-6"
																	style="padding-right: 1px; padding-left: 1px;">
																	<label style="margin: 0px;">Cantidad:</label>
																	<input type="text" class="form-control formulario text-uppercase"
																		data-bind="value: Cantidad, event:{focus: OnFocus, focusout: ValidarCantidad}, numbertrim : Cantidad">
																</div>
																<div class="form-group col-md-6 col-xs-6"
																	style="padding-right: 1px; padding-left: 1px;">
																	<label style="margin: 0px;">Precio:</label>
																	<input type="text" class="form-control formulario text-uppercase input-success"
																		data-bind="value: PrecioUnitario, event:{focus: OnFocus, focusout: ValidarPrecioUnitario}, numbertrim : PrecioUnitario">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- /ko -->
										<div id="resultado_busqueda" class="hide text-center">
											<h1>Sin resultados...</h1>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12">
							<table>
								<tr>
									<td>
										<div id="PaginadorJSON">
										</div>
									</td>
									<td style="padding-bottom:5px;">
										<!-- ko with : BusquedaAvanzadaProducto -->
										<!-- <h5>Se encontraron <span data-bind="html : totalfilas"></span> registros</h5> -->
										<!-- /ko -->
									<td>
								</tr>
							</table>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
