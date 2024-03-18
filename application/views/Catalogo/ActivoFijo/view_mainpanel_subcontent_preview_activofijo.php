<!-- ko with : $root.data.ActivoFijo  -->
<div class="products-preview datalist-preview">

  <div class="products-preview__cont">

    <div class="products-preview__name text-uppercase" title="Nombre Producto" data-bind="text: NombreProducto">Nombre</div>
    <div class="products-preview__data" width="100%">
      <div class="products-preview__photo" style="width:100%; height:100%; max-width: 100%; padding-right:0">
      </div>
    </div>

    <div class="products-preview__props text-uppercase "> <!-- Vista Previa de Familia -->
      <div class="products-preview__prop " title="Codigo"><i class="fa fa-money"></i><span class="products-preview__salary" data-bind="text: CodigoActivoFijo">Codigo</span></div>
      <div class="products-preview__prop " title="Precio Unitario"><i class="fa fa-calendar"></i><span class="products-preview__date" data-bind="text: NumeroSerie">Precio Unitario</span></div>
      <!--<div class="products-preview__prop"><i class="fa fa-heartbeat"></i><span class="products-preview__status" data-bind="text: PrecioUnitario">Stock Actual</span></div>-->
    </div>
    <div class="products-preview__note text-uppercase " title="Beneficios" data-bind="text: Ano">Beneficios</div>

  </div>
</div>
<!-- /ko -->
