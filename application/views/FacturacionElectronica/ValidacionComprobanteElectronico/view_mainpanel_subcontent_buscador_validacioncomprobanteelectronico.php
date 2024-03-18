<div class="col-sm-12">
  <div class="row">
    <div class="col-md-2 col-xs-6">
      <div class="form-group">
        <label class="label-form" for="FechaInicio">F. INICIO:</label>
        <input id="FechaInicio" class="form-control formulario fecha" data-bind="value: FechaInicio">
      </div>
    </div>
    <div class="col-md-2 col-xs-6">
      <div class="form-group">
        <label class="label-form" for="FechaFinal">F. FINAL:</label>
        <input id="FechaFinal" class="form-control formulario fecha" data-bind="value: FechaFin">
      </div>
    </div>
    <div class="col-md-2 col-xs-6">
      <div class="form-group">
        <label class="label-form" for="TipoDocumento">TIPO DOCUMENTO:</label>
        <select id="TipoDocumento" class="form-control  formulario" data-bind="
                value : IdTipoDocumento,
                options : TiposDocumentoVenta,
                optionsValue : 'IdTipoDocumento',
                optionsText : 'NombreTipoDocumento',
                optionsCaption: 'Todos',
                event : { keyup : $root.Consultar}">
        </select>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="label-form" for="TextoFiltro">SERIE, NUMERO DOCUMENTO, DNI/RUC O RAZÓN SOCIAL:</label>
        <input id="TextoFiltro" class="form-control formulario" data-bind="value: TextoFiltro" placeholder="Buscar por SERIE, NUMERO DOCUMENTO, DNI/RUC O RAZÓN SOCIAL">
      </div>
    </div>
    <div class="col-md-2 col-xs-12">
      <div class="form-group">
        <label class="label-form" for="Buscador">&nbsp;</label>
        <button id="Buscador" class="btn btn-primary btn-control" data-bind="event: {click: $root.ConsultarComprobantes}">Buscar</button>
      </div>
    </div>
  </div>
</div>
<br>
<br>
