<!-- ko with : $root.data.Servicio  -->
<div class="products-preview datalist-preview">
  <div class="products-preview__cont">
    <div class="products-preview__name text-uppercase" title="Nombre Producto" data-bind="text: NombreProducto">Nombre</div>
    <div class="products-preview__data" width="100%">
      <div class="products-preview__photo" style="width:100%; height:100%; max-width: 100%; padding-right:0">
        <div>
          <a href="#" id="busqueda-google">
            <img src="" width="100%" height="100%" id="img_FileFotoPreview">
          </a>
        </div>
      </div>
    </div>
    <div class="products-preview__props"> <!-- Vista Previa de Familia -->
      <div class="products-preview__note "> <span><b> </b> </span><b><span class="products-preview__salary"> Código: </span></b><span class="products-preview__date" data-bind="text: CodigoServicio"></span></div>
      <div class="products-preview__note "> <span><b> </b> </span><b><span class="products-preview__salary"> Precio: </span></b><span class="products-preview__date" data-bind="text: PrecioUnitario"></span></div>
    </div>
  </div>
</div>
<!-- /ko -->
