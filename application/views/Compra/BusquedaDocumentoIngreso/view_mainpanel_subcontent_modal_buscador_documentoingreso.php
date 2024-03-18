<br>
<!-- ko with : FiltrosIngreso -->
<fieldset>
  <form class="" action="" method="post">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <input id="input-text-filtro-comprobante" class="form-control formulario" type="text" placeholder="Buscar por Nro Documento" data-bind="value : textofiltro">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">Del</div>
              <input id="fecha-inicio" class="form-control formulario" data-bind="value: FechaInicio, event:{blur:ValidarFechaInicio}" data-inputmask-clearmaskonlostfocus="false"
              data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de inicio en invalida" data-validation-has-keyup-event="true"/>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">Al</div>
              <input id="fecha-fin" class="form-control formulario" data-bind="value: FechaFin, event:{blur:ValidarFechaFin}" data-inputmask-clearmaskonlostfocus="false"
              data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de fin en invalida" data-validation-has-keyup-event="true"/>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <button id="btn_buscardocumentocompra" class="btn btn-primary btn-control" data-bind="event:{click: BuscarDocumento}" style="width: 100%;">Buscar</button>
        </div>
      </div>
    </div>
  </form>
</fieldset>
<!-- /ko -->
<br>
