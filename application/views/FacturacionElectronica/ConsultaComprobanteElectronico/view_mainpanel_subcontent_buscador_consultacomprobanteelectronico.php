<div class="datalist-filter">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-1">
        <h6>Buscar Doc.</h6>
        <input id="input-text-filtro" class="form-control formulario" type="text" placeholder="Nº Doc." data-bind="value: NumeroDocumento">
      </div>
      <div class="col-md-2">
        <h6>Buscar RUC/DNI</h6>
        <input id="input-text-filtro2" class="form-control formulario" type="text" placeholder="RUC/DNI" data-bind="value: RazonSocial">
      </div>
      <div class="col-md-2">
        <h6>Buscar por Fecha</h6>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Del</div>
            <input id="fecha-inicio" class="form-control formulario" data-bind="value: FechaInicio"
            data-inputmask-clearmaskonlostfocus="false">
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <h6 class="espacio_ocultar">&nbsp;</h6>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Al</div>
            <input id="fecha-fin" class="form-control formulario" data-bind="value: FechaFin"
            data-inputmask-clearmaskonlostfocus="false">
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <h6>Tipo Doc.</h6>
        <select id="combo-tipodocumento" class="form-control formulario" data-bind="
        value : IdTipoDocumento,
        options : $parent.TiposDocumento,
        optionsValue : 'IdTipoDocumento',
        optionsText : 'NombreAbreviado',
        optionsCaption : 'TODOS' "><!-- event : { change : OnChangeTipoDocumento } -->
        </select>
      </div>
      <div class="col-md-2">
        <h6>Estado del CPE</h6>
        <select id="select-estado" class="form-control formulario" data-bind="value: EstadoCPE">
          <option value="'%'">Todos</option>
          <option value="'G','P','D','Q','Z','L','Y'">Pendientes de Envío</option>
          <option value="'C','K','O'">Enviados y Aceptados</option>
          <option value="'R'">Facturas y Notas Rechazadas</option>
          <option value="'H'">Bajas y Notas Rechazadas</option>
          <option value="'U'">Resumenes Diarios Rechazados</option>
          <option value="'N'">Anulados y No Enviados</option>
        </select>
      </div>
      <div class="col-md-1">
        <h6 class="espacio_ocultar">&nbsp;</h6>
        <button id="BuscadorEnvio" class="btn btn-primary btn-control" data-bind="event: {click: BuscarFactura, change: BusquedaTexto}">Buscar</button>
      </div>
    </div>
  </div>
</div>
<br>
<br>
