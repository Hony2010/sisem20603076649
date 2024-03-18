<!-- ko with : Filtros -->
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario-venta">Almacen</div>
            <select name="combo-almacen" id="combo-almacen" class="form-control formulario" data-bind="
            value : IdAsignacionSede,
            options : $root.data.Sedes,
            optionsValue : 'IdAsignacionSede' ,
            optionsText : 'NombreSede',
            event:{change: $root.OnChangeAlmacen}"
            data-validation="required" data-validation-error-msg="No tiene almacen asignado">
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group datalist-filter__search">
        <input id="input-text-filtro-inventarioinicial" class="form-control formulario" type="text" placeholder="Buscar por Descripción o Código Mercadería" data-bind="value : textofiltro, event : { keyup : $root.Consultar}">
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon formulario-venta">F. Inventario</div>
          <input id="FechaMovimiento" name="FechaMovimiento" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaMovimiento, event:{focusout: $root.ValidarFechaMovimiento}"
          data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de emision en invalida"/>
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <button type="button" id="BtnFechaMovimiento" class="btn btn-success focus-control btn-control" data-bind="event:{click: $root.CambiarFechaInventario}">Cambiar Fecha</button>
    </div>

    </div>
  </div>
<!-- /ko -->
