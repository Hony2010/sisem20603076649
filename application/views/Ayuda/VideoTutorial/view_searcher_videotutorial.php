<!-- ko with : Filtro -->
<form action="" autocomplete="off">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
				<input class="form-control formulario" type="text" placeholder="Ingrese su descripción aquí"
					data-bind="value: TextoFiltro, event: { keyup : $root.OnKeyEnter}">
			</div>
			<div class="col-md-3">
				<div class="input-group">
					<div class="input-group-addon">Modulos</div>
					<select id="combo-tipoventa" class="form-control  formulario" data-bind="
            value : IdMolulosSistema,
            options : MolulosSistema,
            optionsValue : 'MolulosSistema',
            optionsText : 'NombreMolulosSistema',
            event: { keyup : $root.OnKeyEnter}">
					</select>
				</div>
      </div>
      <div class="col-md-1"></div>
      <div class="col-md-2">
        <button type="button" class="btn btn-primary btn-control" data-bind="event: { click: OnClickBtnConsultar}">Consultar</button>
      </div>
		</div>
	</div>
</form>
<!-- /ko -->
<br>
<br>
