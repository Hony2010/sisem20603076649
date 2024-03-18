<!-- ko with : Filtros -->
<form action="" class="datalist-filter">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-md-3">
        <input id="input-text-filtro" class="form-control formulario" type="text" placeholder="Buscar por Nro Documento, RUC o Proveedor"
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
      <div class="col-md-2">
        <div class="input-group">
          <div class="input-group-addon">Tipo de Compra</div>
            <select id="combo-tipocompra" class="form-control  formulario" data-bind="
                    value : IdTipoCompra,
                    options : TiposCompra,
                    optionsValue : 'IdTipoCompra',
                    optionsText : 'NombreTipoCompra',
                    optionsCaption: 'Todos',
                    event : { keyup : $root.Consultar}">
            </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="input-group">
          <div class="input-group-addon">Tipo de Doc.</div>
            <select id="combo-tipodocumento" class="form-control  formulario" data-bind="
                    value : IdTipoDocumento,
                    options : TiposDocumentoCompra,
                    optionsValue : 'IdTipoDocumento',
                    optionsText : 'NombreTipoDocumento',
                    optionsCaption: 'Todos',
                    event : { keyup : $root.Consultar}">
            </select>
        </div>
      </div>
    </div>
  </div>

</form>
<!-- /ko -->
<br>
<br>
