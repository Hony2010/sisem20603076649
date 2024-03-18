<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalEnvioComprobanteElectronico"
	data-backdrop="static">
	<div class="modal-dialog" role="document" style="max-width: 500px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="panel-title">Compartir el comprobante y xml por</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<br>
						<div class="col-md-12">
							<div class="col-md-6">
								<div class="form-group text-center">
									<div class="radio">
										<input type="radio" name="TipoEnvioComprobante" id="CorreoElectronico" value="1"
											data-bind="checked: TipoEnvioComprobante, event: {change: OnChangeTipoEnvioComprobante}">
										<label for="CorreoElectronico">Correo Electronico</label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group text-center">
									<div class="radio">
										<input type="radio" name="TipoEnvioComprobante" id="WhatsApp" value="2"
											data-bind="checked: TipoEnvioComprobante, event: {change: OnChangeTipoEnvioComprobante}">
										<label for="WhatsApp">WhatsApp</label>
									</div>
								</div>
							</div>
							<br>
							<form autocomplete="off">
								<div class="col-md-12">
									<div class="form-group">
										<input type="text" class="formulario form-control"
											style="text-transform: none; font-size: 13px;"
											data-bind="value: DirecionEnvioComprobante, attr:{ placeholder: TipoEnvioComprobante() == 1 ? 'Ingrese Correo Electrónico' : 'Ingrese Numero de Celular' }">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group text-right">
										<button class="btn btn-success"
											data-bind="click: EnviarComprobantesSegunTipo">Enviar</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
