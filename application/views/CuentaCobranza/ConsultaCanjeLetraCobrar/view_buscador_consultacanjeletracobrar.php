<!-- ko with : Filtros -->
<form action="" class="datalist-filter">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-md-3">
        <input id="TextoFiltro" class="form-control formulario" type="text" placeholder="Buscar por Nro Documento, RUC/DNI o Cliente"
        data-bind="value : TextoFiltro, event : { keyup : $root.Consultar}">
      </div>
      <div class="col-md-2">
        <div class="input-group">
          <div class="input-group-addon ">F. Inicio</div>
          <input id="FechaInicio" class="form-control formulario" data-bind="value: FechaInicio, event : { keyup : $root.Consultar}"/>
        </div>
      </div>
      <div class="col-md-2">
        <div class="input-group">
          <div class="input-group-addon ">F. Fin</div>
          <input id="FechaFin"class="form-control formulario" data-bind="value: FechaFin, event : { keyup : $root.Consultar}"/>
        </div>
      </div>
      <!-- <div class="col-md-4">
        <div class="input-group">
          <div class="input-group-addon">Tipos Documento</div>
            <select id="ComboCaja" class="form-control formulario" data-bind="
              value : IdTipoDocumento,
              options : TiposDocumentoCaja,
              optionsValue : 'IdTipoDocumento',
              optionsText : 'NombreTipoDocumento',
              optionsCaption: 'Todos',
              event : { keyup : $root.Consultar}">
            </select>
        </div>
      </div> -->
      <!-- <div class="col-md-3">
        <div class="input-group">
          <div class="input-group-addon">Motivo de caja</div>
            <select id="combo-tipoventa" class="form-control  formulario" data-bind="
              value : IdTipoOperacionCaja,
              options : TiposOperacionCaja,
              optionsValue : 'IdTipoOperacionCaja',
              optionsText : 'NombreConceptoCaja',
              optionsCaption: 'Todos',
              event : { keyup : $root.Consultar}">
            </select>
        </div>
      </div> -->
    </div>
  </div>
</form>
<!-- /ko -->
<br>
<br>
