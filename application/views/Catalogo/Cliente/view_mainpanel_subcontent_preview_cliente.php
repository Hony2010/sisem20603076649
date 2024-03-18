<!-- ko with : Cliente  -->
<div class="products-preview datalist-preview">
  <div class="products-preview__cont">
    <div class="products-preview__name text-uppercase" title="Razon Social" data-bind="text: RazonSocial">Nombre</div>
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
      <div class="products-preview__note "> <span><b> </b> </span><b><span class="products-preview__salary" data-bind="text: NombreAbreviado() +': '"></span></b><span class="products-preview__date" data-bind="text: NumeroDocumentoIdentidad()"></span></div>
      <div class="products-preview__note "> <span><b> </b> </span><b><span class="products-preview__salary"> Cel - Telf: </span></b><span class="products-preview__date" data-bind="text: Celular()+ ' - ' +TelefonoFijo()"></span></div>
    </div>
    <div class="products-preview__note"><span><b>Dirección: </b></br></p><span data-bind="text:Direccion" ></div>
  </div>
</div>
<!-- /ko -->
