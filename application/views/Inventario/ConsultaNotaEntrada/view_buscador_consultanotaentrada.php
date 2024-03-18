<!-- ko with : Filtros -->
<form action="" class="datalist-filter">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-md-8">
        <input id="input-text-filtro" class="form-control formulario" type="text" placeholder="Buscar por Nro Documento"
        data-bind="value : textofiltro, event : { keyup : $root.Consultar}">
      </div>
      <div class="col-md-2">
        <div class="input-group">
          <div class="input-group-addon ">F. Inicio</div>
          <input id="FechaInicio" name="FechaInicio"
           class="form-control formulario" data-inputmask-clearmaskonlostfocus="false"
           data-bind="value: FechaInicio, event : { keyup : $root.Consultar}"/>
        </div>
      </div>
      <div class="col-md-2">
        <div class="input-group">
          <div class="input-group-addon ">F. Fin</div>
          <input id="FechaFin" name="FechaFin"
           class="form-control formulario" data-inputmask-clearmaskonlostfocus="false"
           data-bind="value: FechaFin, event : { keyup : $root.Consultar}"/>
        </div>
      </div>
    </div>

  </div>

  <div class="collapse" id="datalist-filter__detail">
  </div>
</form>
<!-- /ko -->
<br>
<br>
