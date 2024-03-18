<!-- ko with : Filtros -->
  <div class="">
    <div class="form-group datalist-filter__search">
        <input id="input-text-filtro-mercaderia" class="form-control formulario" 
        type="text" placeholder="Buscar Mercadería por Descripción o Código" 
        data-bind="value : textofiltro, event : { keyup : $root.Consultar}">
    </div>
  </div>
<!-- /ko -->