<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalCliente"
	data-bind="bootstrapmodal : Cliente.showCliente, show : Cliente.showCliente, onhiden :  function(){Cliente.Hide(window)}, backdrop: 'static'">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bind="click : Cliente.OnClickBtnCerrar" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="panel-title"><span data-bind="text : Cliente.MostrarTitulo"></span></h4>
			</div>
			<div class="modal-body">
				<?php echo $view_form_cliente; ?>
			</div>
		</div>
	</div>
</div>

<!-- ko with : Cliente  -->
<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalDireccionesCliente" data-bind="
bootstrapmodal: showDirecciones,
show: showDirecciones,
onhiden :  function() { return HideDirecciones(window)}, backdrop: 'static'">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
				<h4 class="panel-title">Direcciones Cliente</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<table class="table" id="tablaDireccionesCliente">
								<thead>
									<tr>
										<th>DIRECCIÓN</th>
										<th width="50">
											<button class="btn btn-success btn-control" title="Nueva Dirección"
												data-bind="event: { click: OnClickBtnNuevaDireccionCliente }">
												<i class="fas fa-plus"></i>
											</button>
										</th>
									</tr>
								</thead>
								<tbody>
									<!-- ko foreach : DireccionesCliente -->
									<tr>
										<td>
											<div class="form-group">
												<input type="text" class="form-control formulario"
													data-bind="value: Direccion, event : {change : function(data,event) { return $parent.OnChangedDireccionCliente(data,event); } } ">
											</div>
										</td>
										<td>
											<button class="btn btn-danger btn-control" title="Eliminar Dirección"
												data-bind="event: { click: $parent.OnClickBtnRemoverDireccionCliente }">
												<i class="fas fa-trash-alt"></i>
											</button>
										</td>
									</tr>
									<!-- /ko -->
								</tbody>
							</table>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- vehiculo -->
<?php echo $view_mainpanel_modal_vehiculocliente;?>

<!-- /ko -->
