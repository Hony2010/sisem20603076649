<fieldset>
  <!-- ko with : BusquedaAvanzadaProducto -->
  <div class="col-md-3">
    <div class="filter-products-vertical form-group">
      <div class="btn-group-vertical" style="width:100%">
        <!-- ko foreach : $parent.FamiliasProducto -->
        <button type="button" class="btn btn-selection btn-familias" data-bind="text: NombreFamiliaProducto, attr : { 'data-familia' : IdFamiliaProducto()}, event:{click: function(data, event){return $parent.BuscarSubFamilias($parent, event, $root);} }"></button>
        <!-- /ko -->
      </div>
    </div>
  </div>
  <div class="col-md-9">
    <div class="search-products form-group">
      <div class="input-group">
        <input type="text" class="form-control formulario" placeholder="BUSCAR POR NOMBRE DEL PRODUCTO" data-bind="value: textofiltro">
        <div class="input-group-btn">
          <button type="button" class="btn btn-default btn-buscar" data-bind="event:{click: function(data, event){return Buscar(data, event, $root);} }"><i class="glyphicon glyphicon-search"></i></button>
        </div>
      </div>
    </div>
    <div class="filter-products form-group">
      <!-- ko foreach : $parent.SubFamiliasProductoFiltrado -->
      <div class="btn-group">
        <button type="button" class="btn btn-selection btn-subfamilias" data-bind="text: NombreSubFamiliaProducto, attr : { 'data-subfamilia' : IdSubFamiliaProducto()}, event: { click: function(data, event){return $parent.BuscarMercaderiaPorSubFamilia($parent, event, $root);} }"></button>
      </div>
      <!-- /ko -->
    </div>
    <div class="list-products">
      <table class="table display table-border table-hover">
        <tbody>
          <!-- ko foreach : $parent.Mercaderias -->
          <tr class="tr-list-products">
            <td width="100px">
              <img width="80px" height="50px" data-bind="attr:{src: Foto},  event: { click: OnClickImgPreviewProduct }">
            </td>
            <td data-bind="event: {click: function (data, event) { return OnClickAgregarMercaderia(data, event, $root.data) }}">
              <span data-toggle="tooltip" data-placement="bottom" data-bind="text: NombreProducto, attr:{title: NombreProducto}"></span>
            </td>
            <td width="100px" data-bind="event: {click: function (data, event) { return OnClickAgregarMercaderia(data, event, $root.data) }}">
              <span data-toggle="tooltip" data-placement="bottom" data-bind="text: PrecioUnitario"></span>
            </td>
          </tr>
          <!-- /ko -->
          <div class=" text-center" data-bind="visible: $parent.Mercaderias().length == 0">
            <h1>Sin resultados...</h1>
          </div>
        </tbody>
      </table>
    </div>
  </div>
  <!-- /ko -->
</fieldset>

<style media="screen">
  .products-photo,
  .products-description,
  .products-price {
    height: 100px;
  }

  .products-photo {
    width: 100px;
  }
  .products-description {
    width: 100%;
  }
  .products-price {
    width: 100px;
  }
  .products-data {
    display: flex;
  }
  .modal-xs {
      width: 500px !important;
  }
</style>
