<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="BuscadorMercaderiaListaSimple"
	data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="panel-title">BÚSQUEDA DE INVENTARIO DE MERCADERÍAS ( <b><span id="spanNombreSede"></span></b>
					) </h4>
			</div>
			<div class="modal-body">
				<!-- ko with : BusquedaAvanzadaProducto -->
				<div class="container-fluid" style="padding:0px; min-height: 400px;">
					<div class="row">
						<br>
						<div class="col-md-12">
							<form action="" method="post">
								<div class="col-md-12">
									<div class="col-md-5">
										<div class="form-group">
											<label for="">Filtro de producto</label>
											<input id="TextoFiltro" type="text" class="form-control formulario"
												placeholder="Buscar Mercaderia" data-bind="
													value: textofiltro,
													event: {focus: OnFocus, keydown : OnKeyDownBuscarProductos }">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="">Familia</label>
											<select class="form-control formulario" data-bind="
													value: IdFamiliaProducto,
													options: Familias,
													optionsValue: 'IdFamiliaProducto',
													optionsText: 'NombreFamiliaProducto',
													optionsCaption: 'Todos',
													event: { focus: OnFocus, keydown: OnKeyEnter }">
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="">Marca</label>
											<select class="form-control formulario" data-bind="
													value: IdMarca,
													options: Marcas,
													optionsValue: 'IdMarca',
													optionsText: 'NombreMarca',
													optionsCaption: 'Todos',
													event: { focus: OnFocus, keydown: OnKeyEnter }">
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="">&nbsp;</label>
											<div class="checkbox">
												<input type="checkbox" class="form-control formulario"
													data-bind="checked: CheckConSinStock, event: {focus : OnFocus , keydown : OnKeyEnter}" />
												<label for="CheckNumeroDocumento">Con stock</label>
											</div>
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label for="">&nbsp;</label>
											<button type="button" class="btn btn-primary btn-control"
												data-bind=" event: { click: OnClickBuscarProductos }">Buscar</button>
										</div>
									</div>
								</div>
								<fieldset>
									<div style="overflow-x: scroll;">
										<table class="table table-hover" id="TablaInventarioMercaderias">
											<thead>
												<tr>
													<th class="col-md-auto text-center">CODIGO</th>
													<th class="col-md-auto text-center">PRODUCTO</th>
													<th class="col-md-auto text-center">FAMILIA</th>
													<th class="col-md-1 text-center">MARCA</th>
													<th class="col-md-1 text-center">STOCK</th>
													<th class="col-md-1 text-center">CANTIDAD</th>
													<th class="col-md-1 text-center">P. UNITARIO</th>
													<!-- <th class="col-md-1 text-center">IMPORTE</th> -->
													<th></th>
												</tr>
											</thead>
											<tbody>
												<!-- ko foreach : Mercaderias -->
												<tr data-bind="attr : { id: IdProducto()+'_Producto' }">
													<td class="col-md-auto col-md-auto-height text-center">
														<span data-bind="text: CodigoMercaderia"></span>
													</td>
													<td class="col-md-auto col-md-auto-height">
														&nbsp;<span data-bind="text: NombreProducto"></span>
													</td>
													<td class="col-md-auto col-md-auto-height text-center">
														<span data-bind="text: NombreFamiliaProducto"></span>
													</td>
													<td class="col-md-1 col-md-auto-height text-center">
														<span data-bind="text: NombreMarca"></span>
													</td>
													<td class="col-md-1 col-md-auto-height text-center">
														<span data-bind="text: StockProducto"></span>
													</td>
													<td class="col-md-1">
														<input type="text"
															class="form-control formulario text-right"
															data-bind="value: Cantidad, numbertrim: Cantidad, event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeCantidad}">
													</td>
													<td class="col-md-1">
														<input type="text"
															class="form-control formulario text-right"
															data-bind="value: PrecioUnitario, numbertrim: PrecioUnitario, event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangePrecioUnitario}">
													</td>
													<!-- <td class="col-md-1">
														<input type="text"
															class="form-control formulario text-right" readonly
															data-bind="value: SubTotal, numbertrim: SubTotal, event: { focus: OnFocus, keydown: OnKeyEnter}">
													</td> -->
													<td class="text-center">
														<button type="button"
															class="btn btn-btn-control btn-success"
															data-bind="event: {click: (data,event) => OnClickBotonAñadir(data,event,$parent.PostOnClickBotonAñadir) }">Añadir</button>
													</td>
												</tr>
												<!-- /ko -->
											</tbody>
										</table>
									</div>
								</fieldset>
								<br>
							</form>
						</div>
					</div>

					<table>
						<tr>
							<td>
								<div id="PaginadorJSONParaListaSimple">
								</div>
							</td>
							<td style="padding-bottom:5px;">
								<h5>Se encontraron <span data-bind="html : totalfilas"></span> registros</h5>
							<td>
						</tr>
					</table>
				</div>
				<!-- /ko -->
			</div>
		</div>
	</div>
</div>
