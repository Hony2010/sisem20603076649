<fieldset>
  <div class="col-sm-12">
  <div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">Tipo Documento</div>
          <select id="combo-tipodocumento" class="form-control formulario" data-bind="
          value : IdTipoDocumento,
          options : $parent.TiposDocumento,
          optionsValue : 'IdTipoDocumento',
          optionsText : 'NombreTipoDocumento',
          optionsCaption : 'TODOS' "><!-- event : { change : OnChangeTipoDocumento } -->
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">Estado</div>
          <select class="form-control formulario" name="" data-bind="value: IndicadorEstadoPW">
            <option value="%">Todos</option>
            <option value="D">Pendiente</option>
            <option value="V">Enviado</option>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">Del</div>
          <input id="fecha-inicio" class="form-control formulario" data-bind="value: FechaInicio" data-inputmask-clearmaskonlostfocus="false">
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">Al</div>
          <input id="fecha-fin" class="form-control formulario" data-bind="value: FechaFin" data-inputmask-clearmaskonlostfocus="false">
        </div>
      </div>
    </div>
    <div class="col-md-1">
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <button id="BuscadorEnvio" class="btn btn-primary btn-control" data-bind="event: {click: BuscarFactura, change: BusquedaTexto}">Buscar</button>
      </div>
    </div>
  </div>
</div>
</fieldset>
