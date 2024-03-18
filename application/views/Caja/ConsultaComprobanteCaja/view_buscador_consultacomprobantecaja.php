<!-- ko with : Filtros -->
<form action="" class="datalist-filter">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <input id="TextoFiltro" class="form-control formulario" type="text" placeholder="Buscar por Nro Documento, RUC/DNI o Cliente" data-bind="value : TextoFiltro, event : { keyup : $root.Consultar}">
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon ">F. Inicio</div>
            <input id="FechaInicio" class="form-control formulario" data-bind="value: FechaInicio, event : { keyup : $root.Consultar}" />
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon ">F. Fin</div>
            <input id="FechaFin" class="form-control formulario" data-bind="value: FechaFin, event : { keyup : $root.Consultar}" />
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Caja</div>
            <select id="ComboCaja" class="form-control formulario" data-bind="
                value : IdCaja,
                options : Cajas,
                optionsValue : 'IdCaja',
                optionsText : item => { return item.NombreCaja() + ' - ' + item.NombreMoneda() },
                optionsCaption: 'Todos',
                event : { keyup : $root.Consultar}">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Tipo Operación</div>
            <select id="combo-tipoventa" class="form-control  formulario" data-bind="
                value : IdTipoOperacionCaja,
                options : TiposOperacionCaja,
                optionsValue : 'IdTipoOperacionCaja',
                optionsText : 'NombreConceptoCaja',
                optionsCaption: 'Todos',
                event : { keyup : $root.Consultar}">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Monedas</div>
            <select id="combo-tipoventa" class="form-control  formulario" data-bind="
                value : IdMoneda,
                options : Monedas,
                optionsValue : 'IdMoneda',
                optionsText : 'NombreMoneda',
                optionsCaption: 'Todos',
                event : { keyup : $root.Consultar}">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Tipos Documento</div>
            <select id="combo-tipoventa" class="form-control  formulario" data-bind="
                value : IdTipoDocumento,
                options : TiposDocumentoCaja,
                optionsValue : 'IdTipoDocumento',
                optionsText : 'NombreTipoDocumento',
                optionsCaption: 'Todos',
                event : { keyup : $root.Consultar}">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Usuarios</div>
            <select id="combo-usuario" class="form-control  formulario" data-bind="
                value : IdUsuario,
                options : Usuarios,
                optionsValue : 'IdUsuario',
                optionsText : 'NombreUsuario',
                optionsCaption: 'Todos',
                event : { keyup : $root.Consultar}">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <button id="btnBuscar" class="btn btn-primary btn-control" type="button" data-bind="event : {click : $root.ConsultarDesdeButton}">Buscar</button>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
<br>
<br>