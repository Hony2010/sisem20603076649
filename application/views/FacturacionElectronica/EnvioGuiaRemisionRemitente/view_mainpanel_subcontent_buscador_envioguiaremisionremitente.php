<!--<form class="datalist-filter">-->
<div class="col-sm-12">
  <div class="row">
    <div class="col-md-2">
      <div class="form-group">
        <h6>Buscar por Documento</h6>
        <input id="input-text-filtro" class="form-control formulario" type="text" placeholder="Nº Documento" data-bind="value: NumeroDocumento">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <h6>Buscar por RUC/Tranportista</h6>
        <input id="input-text-filtro2" class="form-control formulario" type="text" placeholder="RUC / Tranportista" data-bind="value: RazonSocial">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <h6>Buscar por Fecha</h6>
        <div class="input-group">
          <div class="input-group-addon">Del</div>
          <input id="fecha-inicio" class="form-control formulario" data-bind="value: FechaInicio" data-inputmask-clearmaskonlostfocus="false">
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <h6>&nbsp;</h6>
        <div class="input-group">
          <div class="input-group-addon">Al</div>
          <input id="fecha-fin" class="form-control formulario" data-bind="value: FechaFin" data-inputmask-clearmaskonlostfocus="false">
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <h6>&nbsp;</h6>
        <button id="BuscadorEnvio" class="btn btn-primary btn-control" data-bind="event: {click: BuscarGuiaRemision, change: BusquedaTexto}">Buscar</button>
      </div>
    </div>
  </div>
</div>
<!--</form>-->