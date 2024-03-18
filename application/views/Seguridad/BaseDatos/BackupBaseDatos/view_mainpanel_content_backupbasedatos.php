<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
	<div class="main__cont">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Backup Base de Datos</h3>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<fieldset>
								<div class="col-md-12 text-center">
									<p>
										<b> Presione el Boton "Generar" para generar el backup de la base de datos: </b> <br>
										<button type="button" class="btn btn-primary"
											data-bind="event: {click : OnClickBtnGenerarBackup}">Generar</button>
									</p>
								</div>
								<div class="col-md-6 text-center">
									<p>
										<b> Si desea descargar el backup presione aquí: </b> <br>
										<button type="button" class="btn btn-primary"
											data-bind="event: {click : OnClickBtnDescargarBackup}">Descargar</button>
									</p>
								</div>
								<div class="col-md-6 text-center">
									<p>
										<b> Si desea enviar por correo el backup presione aquí: </b> <br>
										<button type="button" class="btn btn-primary"
											data-bind="event: {click : OnClickBtnEnviarCorreo}">Enviar Correo</button>
									</p>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /ko -->
