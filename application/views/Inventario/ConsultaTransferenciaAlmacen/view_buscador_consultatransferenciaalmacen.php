<!-- ko with : Filtros -->
<form action="" class="datalist-filter">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <input id="TextoFiltro" class="form-control formulario" type="text" placeholder="Buscar por Nro Documento, RUC/DNI o Cliente" data-bind="value : TextoFiltro, event : { keyup : $root.Consultar}">
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon ">F. Inicio</div>
            <input id="FechaInicio" class="form-control formulario fecha" data-bind="value: FechaInicio, event : { keyup : $root.Consultar}" />
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon ">F. Fin</div>
            <input id="FechaFin" class="form-control formulario fecha" data-bind="value: FechaFin, event : { keyup : $root.Consultar}" />
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