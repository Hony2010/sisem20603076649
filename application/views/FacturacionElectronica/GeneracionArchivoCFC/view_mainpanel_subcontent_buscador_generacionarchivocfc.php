<div class="col-sm-12">
  <div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">Fecha Emision</div>
          <input id="fecha-inicio" class="form-control formulario" data-bind="value: FechaInicio" data-inputmask-clearmaskonlostfocus="false">
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <button id="BuscadorEnvio" class="btn btn-primary btn-control" data-bind="event: {click: BuscarFactura, change: BusquedaTexto}">Cargar</button>
      </div>
    </div>
    <div class="col-md-1">

    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <div class="input-group">
              <div class="checkbox">
                <input name="CheckMotivo" id="CheckMotivo" type="checkbox" class="form-control formulario" data-bind="checked: $root.CheckMotivo, event: { change : $root.OnChangeCheckMotivo}">
                <label for="CheckNumeroDocumento"> ¿Aplicar a Todos?</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">Motivo Contingencia</div>
              <select disabled id="combo-motivo" class="form-control formulario" data-bind="
              options : $parent.Motivos,
              optionsValue : 'IdMotivoComprobanteFisicoContingencia',
              optionsText : 'NombreMotivoComprobanteFisicoContingencia',
              event: {change: $root.OnChangeMotivo}">
              </select>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
