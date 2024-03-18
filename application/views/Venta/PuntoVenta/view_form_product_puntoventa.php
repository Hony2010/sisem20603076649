<fieldset>
  <!-- ko with : BusquedaAvanzadaProducto -->
  <div class="search-products form-group">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-3">
          <div class="form-group">
            <input id="CodigoBarras" type="text" class="form-control formulario" placeholder="CODIGO DE BARRAS" data-bind="
            event: { input: (data, event) => BuscarMercaderiaPorCodigoDeBarras(data, event, $root) }">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control formulario" placeholder="BUSCAR POR NOMBRE DEL PRODUCTO" data-bind="value: textofiltro">
              <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-buscar" data-bind="event: { click: function(data, event){return Buscar(data, event, $root);} }"><i class="glyphicon glyphicon-search"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <select id="combo-almacen" class="form-control formulario" data-bind="
              options : $root.data.PuntoVenta.Sedes,
              optionsValue : 'IdAsignacionSede' ,
              optionsText : 'NombreSede',
              event: { change: $root.data.PuntoVenta.OnChangeComboAlmacen}">
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="filter-products form-group">
    <div class="btn-group btn-group-justified">
      <!-- ko foreach : $parent.FamiliasProducto -->
      <div class="btn-group">
        <button type="button" class="btn btn-selection btn-familias" data-bind="text: NombreFamiliaProducto, attr : { 'data-familia' : IdFamiliaProducto(), title: NombreFamiliaProducto }, event:{click: function(data, event){return $parent.Buscar($parent, event, $root);} }"></button>
      </div>
      <!-- /ko -->
    </div>
  </div>
  <!-- /ko -->
  <div class="list-products form-group">
    <div class="row">
      <div class="col-md-12">
        <!-- ko foreach : Mercaderias -->
        <div class="col-md-2 col-xs-4">
          <div class="products-preview__data" data-bind="event: {click: OnClickImagenMercaderia}">
            <div class="products-preview__photo" style="padding-right:0; height: 85px;">
              <label class="label_badge_bottom_right" data-bind="text: 'Precio: ' + PrecioUnitario()"></label>
              <div style="background-image:none">
                <img style="width: 100%;height: 100%;" src="" alt="" data-bind="attr:{src: Foto}">
              </div>
            </div>
          </div>
          <div class="products-preview__info text-center">
            <span data-toggle="tooltip" data-placement="bottom" title="Jack Daniel's - Tenesse Whiskey, Botella 700 ml" data-bind="text: NombreProducto, attr:{title: NombreProducto}"></span>
          </div>
        </div>
        <!-- /ko -->
      </div>
    </div>
  </div>
</fieldset>
