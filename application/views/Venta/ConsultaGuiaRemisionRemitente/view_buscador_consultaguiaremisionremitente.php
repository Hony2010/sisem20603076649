<!-- ko with : Filtros -->
<form action="" class="" autocomplete="off">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="">&nbsp;</label>
          <input id="TextoFiltro" class="form-control formulario" type="text" placeholder="BUSCAR DOCUMENTO" data-bind="
          value: TextoFiltro,
          event: {}">
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="FechaInicio">Fecha Inicio</label>
          <input id="FechaInicio" class="form-control formulario fecha" data-bind="
          value: FechaInicio, 
          event : {}" />
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="FechaFin">Fecha Fin</label>
          <input id="FechaFin" class="form-control formulario fecha" data-bind="
          value: FechaFin, 
          event: {}" />
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="Destinatario">Destinatario</label>
          <input id="Destinatario" class="form-control formulario" data-bind="
          value: Destinatario, 
          event: {}" />
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="Transportista">Transportista</label>
          <input id="Transportista" class="form-control formulario" data-bind="
          value: Transportista, 
          event: {}" />
        </div>
      </div>
      <div class="col-md-1">
        <div class="form-group">
          <label for="">&nbsp;</label>
          <button type="button" class="btn btn-primary btn-control" data-bind="event: {click: $root.Consultar}">Buscar</button>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
<br>
<br>