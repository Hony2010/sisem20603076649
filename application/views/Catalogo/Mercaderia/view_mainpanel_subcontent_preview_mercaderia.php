<!-- ko with : $root.data.Mercaderia  -->
<div class="products-preview datalist-preview">
  <div class="products-preview__cont">
    <div class="products-preview__name" title="Nombre Producto" data-bind="text: NombreProducto">Nombre</div>
    <div class="products-preview__data" width="100%">
      <div class="products-preview__photo" style="width:100%; height:100%; max-width: 100%; padding-right:0">
        <div id="preview-images" data-bind="">
          <a href="#" id="busqueda-google">
            <img src="" width="100%" height="100%" id="img_FileFotoPreview">
          </a>
        </div>
      </div>
    </div>
    <div class="form-group">
      <button type="button" class="btn btn-default btn-control" name="button" data-bind="event{ click: BuscarGoogle}">Buscar imagen en Internet</button>
    </div>
    <div class="products-preview__props"> <!-- Vista Previa de Familia -->
      <div class="products-preview__note "> <span><b> </b> </span><b><span class="products-preview__salary">Código: </span></b><span class="products-preview__date" data-bind="text:CodigoMercaderia"></span></div>
      <div class="products-preview__note "> <span><b> </b> </span><b><span class="products-preview__salary">Precio: </span></b><span class="products-preview__date" data-bind="text:PrecioUnitario"></span></div>
      <div class="products-preview__note "> <span><b> </b> </span><b><span class="products-preview__salary">Familia: </span></b><span class="products-preview__date" data-bind="text:NombreFamiliaProducto"></span></div>
      <div class="products-preview__note "> <span><b> </b> </span><b><span class="products-preview__salary">Fabricante: </span></b><span class="products-preview__date" data-bind="text:NombreFabricante"></span></div>
    </div>
  </div>
</div>
<!-- /ko -->
