<br>
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <h6>Buscar por Documento</h6>
          <input id="input-text-filtro" class="form-control formulario" type="text" placeholder="Nº Documento" data-bind="value: NumeroDocumento">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <h6>Buscar por RUC/Cliente</h6>
          <input id="input-text-filtro2" class="form-control formulario" type="text" placeholder="RUC / Cliente" data-bind="value: RazonSocial">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <h6>Buscar por Fecha</h6>
          <div class="input-group">
            <div class="input-group-addon formulario">Fecha de Emisión</i></div>
            <input id="fecha-emision2" class="form-control formulario" data-bind="value: FechaEmision" data-inputmask-clearmaskonlostfocus="false">
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <h6>&nbsp;</h6>
          <button id="btnBuscarConsulta" class="btn btn-primary btn-control" data-bind="event: {click: BuscarFactura, change: BusquedaTexto}" >Buscar</button>
        </div>
      </div>
    </div>
    <br>
  </div>
