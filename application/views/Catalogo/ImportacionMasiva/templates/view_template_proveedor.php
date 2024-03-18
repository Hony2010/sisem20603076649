<script id="CabeceraImportacionProveedor" type="text/html">
  <th class="col-md-3 products__id codigo-producto"><center>Razon Social</center></th>
  <th class="col-md-1 products__title">Numero Documento</th>
  <th class="col-md-1 products__title">Id TipoDocumento</th>
  <th class="col-md-1 products__title">Id TipoPersona</th>
  <th class="col-md-2 products__title">Direccion</th>
  <th class="col-md-2 products__title">Email</th>
  <th class="col-md-2 products__title">Celular</th>
</script>


<script id="DetallesImportacionProveedor" class="text-uppercase" type="text/html">
  <tr name="Fila" class="clickable-row" style="min-height: 80px;">
    <td class="col-md-3">
      <span data-bind="text: RazonSocial"></span>
    </td>
    <td class="col-md-1">
      <span data-bind="text: NumeroDocumentoIdentidad"></span>
    </td>
    <td class="col-md-1">
      <span data-bind="text: IdTipoDocumentoIdentidad"></span>
    </td>
    <td class="col-md-1">
      <span data-bind="text: IdTipoPersona"></span>
    </td>
    <td class="col-md-2">
      <span data-bind="text: Direccion"></span>
    </td>
    <td class="col-md-2">
      <span data-bind="text: Email"></span>
    </td>
    <td class="col-md-2">
      <span data-bind="text: Celular"></span>
    </td>
  </tr>
</script>
