<!-- ko with : Filtros -->
<form action="" class="datalist-filter">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<input id="input-text-filtro" class="form-control formulario" type="text"
						placeholder="Buscar por Nro Documento, RUC/DNI o Cliente"
						data-bind="value : textofiltro, event : { keyup : $root.Consultar}">
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon ">F. Inicio</div>
						<input id="FechaInicio" name="FechaInicio" class="form-control formulario"
							data-inputmask-clearmaskonlostfocus="false"
							data-bind="value: FechaInicio, event : { keyup : $root.Consultar}" />
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon ">F. Fin</div>
						<input id="FechaFin" name="FechaFin" class="form-control formulario"
							data-inputmask-clearmaskonlostfocus="false"
							data-bind="value: FechaFin, event : { keyup : $root.Consultar}" />
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">Tipo de Venta</div>
						<select id="combo-tipoventa" class="form-control  formulario" data-bind="
                    value : IdTipoVenta,
                    options : TiposVenta,
                    optionsValue : 'IdTipoVenta',
                    optionsText : 'NombreTipoVenta',
                    optionsCaption: 'Todos',
                    event : { keyup : $root.Consultar}">
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">Tipo de Doc.</div>
						<select id="combo-tipodocumento" class="form-control  formulario" data-bind="
                    value : IdTipoDocumento,
                    options : TiposDocumentoVenta,
                    optionsValue : 'IdTipoDocumento',
                    optionsText : 'NombreTipoDocumento',
                    optionsCaption: 'Todos',
                    event : { keyup : $root.Consultar}">
						</select>
					</div>
				</div>
			</div>
			<!-- ko if: $root.data.ComprobanteVenta.ParametroSauna() == 1 -->
			<div class="col-md-3">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">Genero</div>
						<select id="combo-genero" class="form-control formulario" data-bind="value : IdGenero">
							<option value="%">Todos</option>
							<option value="1">Hombres</option>
							<option value="2">Mujeres</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<input class="form-control formulario" type="text" placeholder="Ingresar Nro de casillero"
						data-bind="value : NombreCasillero, event : { keyup : $root.Consultar}">
				</div>
			</div>
			<!-- /ko -->	
		</div>
	</div>
</form>
<!-- /ko -->
<br>
<br>
