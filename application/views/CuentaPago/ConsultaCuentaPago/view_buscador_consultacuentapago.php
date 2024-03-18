<!-- ko with : Filtros -->
<form action="" class="datalist-filter">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-md-4">
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
      <div class="col-md-4">
        <div class="form-group">
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
        </div>
      </div>
      <div class="col-md-4">
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
      <div class="col-md-4">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Medio Pago</div>
            <select id="combo-mediospago" class="form-control  formulario" data-bind="
                value : IdMedioPago,
                options : MediosPago,
                optionsValue : 'IdMedioPago',
                optionsText : 'NombreAbreviado',
                optionsCaption: 'Todos',
                event : { keyup : $root.Consultar}">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Caja</div>
            <select id="combo-caja" class="form-control  formulario" data-bind="
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
    </div>
  </div>
</form>
<!-- /ko -->
<br>
<br>