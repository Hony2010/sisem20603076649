<script id="CabeceraImportacionMercaderia" type="text/html">
  <th class="col-md-1 products__id codigo-producto"><center>Código</center></th>
  <th class="col-md-3 products__title">Nombre</th>
  <th class="col-md-1 products__title">Unidad Medida</th>
  <th class="col-md-1 products__title">Afecto Igv</th>
  <th class="col-md-2 products__title">P. Unit.</th>
  <th class="col-md-2 products__title">Id SubFamilia</th>
  <th class="col-md-2 products__title">Id Modelo</th>
  <th class="col-md-2 products__title">Codigo Propio</th>
</script>

<script id="DetallesImportacionMercaderia" type="text/html">
  <tr name="Fila" class="clickable-row text-uppercase" style="min-height: 80px;">
    <td class="col-md-1">
      <span data-bind="text: CodigoMercaderia"></span>
    </td>
    <td class="col-md-3">
      <span data-bind="text: NombreProducto"></span>
    </td>
    <td class="col-md-1">
      <span data-bind="text: IdUnidadMedida"></span>
    </td>
    <td class="col-md-1">
      <span data-bind="text: IdTipoAfectacionIGV"></span>
    </td>
    <td class="col-md-2">
      <span data-bind="text: PrecioUnitario"></span>
    </td>
    <td class="col-md-2">
      <span data-bind="text: IdSubFamiliaProducto"></span>
    </td>
    <td class="col-md-2">
      <span data-bind="text: IdModelo"></span>
    </td>
    <td class="col-md-2">
      <span data-bind="text: IndicadorCodigoPropio"></span>
    </td>
  </tr>
</script>
