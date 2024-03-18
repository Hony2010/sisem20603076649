<br>
<!-- ko with : Filtros -->
<fieldset>
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-6">
        <div class="form-group">
          <input id="input-text-filtro" class="form-control formulario" type="text" placeholder="Buscar por numero de documento" data-bind="value : textofiltro">
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Del</div>
            <input id="fecha-inicio" class="form-control formulario" data-bind="value: FechaInicio, event:{blur:ValidarFechaInicio}" data-inputmask-clearmaskonlostfocus="false"
            data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de inicio en invalida" data-validation-has-keyup-event="true"/>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">Al</div>
            <input id="fecha-fin" class="form-control formulario" data-bind="value: FechaFin, event:{blur:ValidarFechaFin}" data-inputmask-clearmaskonlostfocus="false"
            data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de fin en invalida" data-validation-has-keyup-event="true"/>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <button id="BuscadorEnvio" class="btn btn-primary btn-control" data-bind="event:{click: ConsultarPorCliente}" style="width: 100%;">Buscar</button>
      </div>
    </div>
  </div>
</fieldset>
<!-- /ko -->
<br>
