<br>
  <div class="col-md-12">
    <div class="row">
      <!--      <div class="col-md-2">
        <h5 class="espacio_ocultar">&nbsp;</h5>&nbsp;
        <h5>Buscar Documento</h5>
        <input id="input-text-filtro" class="form-control" type="text" placeholder="Encuentre documento" data-bind="value: NumeroDocumento">
      </div>-->
      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Fecha de Inicio</div>
            <input id="fecha-inicio" class="form-control formulario" data-bind="value: FechaInicio" data-inputmask-clearmaskonlostfocus="false">
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Fecha de Fin</div>
            <input id="fecha-fin" class="form-control formulario" data-bind="value: FechaFin" data-inputmask-clearmaskonlostfocus="false">
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Estados</div>
            <select id="ComboEstados" class="form-control formulario" data-bind="
            value: CodigoEstado,
            options: EstadosResumen,
            optionsValue: 'CodigoEstado',
            optionsText: 'NombreEstado',
            optionsCaption: 'Todos'"> </select>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <button id="btnBuscarGeneracion" class="btn btn-primary btn-control" data-bind="event: {click: BuscarFactura, change: BusquedaTexto}">Buscar</button>
        </div>
      </div>
      <div class="col-md-7">
        &nbsp;
      </div>
    </div>
  </div>
