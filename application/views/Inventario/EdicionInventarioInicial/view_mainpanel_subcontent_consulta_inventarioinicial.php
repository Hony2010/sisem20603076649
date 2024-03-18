<!-- <div class="scrollable scrollbar-macosx"> -->
<?php echo $view_subcontent_buscador_inventarioinicial; ?>
<fieldset>
  <table id="TablaConsultaInventariosInicial" class="datalist__table table display table-border" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="products__id">Código</th>
        <th class="products__title">Descripción Producto</th>
        <th class="products__title">Fecha Inventario</th>

        <!-- ko if: $root.IndicadorAlmacenZofraVista() == 1 && $root.data.ParametroDocumentoSalidaZofra() == 1-->
        <th class="products__title">Documento Zofra</th>
        <!-- /ko -->
        <!-- ko if: $root.IndicadorAlmacenZofraVista() == 0 && $root.data.ParametroDua() == 1-->
        <th class="products__title">Numero Dua</th>
        <!-- /ko -->
        <th class="products__title">Unidad</th>
        <th class="products__title">Cantidad</th>
        <th class="products__title">Costo Unitario</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : InventariosInicial -->
      <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdInventarioInicial }">

        <td align="left" data-bind="text : CodigoMercaderia">
          <input type="hidden" data-bind="value : IdProducto">
        </td>

        <td data-bind="text : NombreProducto"></td>
        <td data-bind="text : FechaInicial"></td>
        <!-- ko if: $root.IndicadorAlmacenZofraVista() == 1 && $root.data.ParametroDocumentoSalidaZofra() == 1 -->
        <td data-bind="text : NumeroDocumentoSalidaZofra"></td>
        <!-- /ko -->
        <!-- ko if: $root.IndicadorAlmacenZofraVista() == 0 && $root.data.ParametroDua() == 1 -->
        <td data-bind="text : NumeroDua"></td>
        <!-- /ko -->
        <td data-bind="text : NombreUnidadMedida"></td>
        <td data-bind="text : CantidadInicial"></td>
        <td data-bind="text : ValorUnitario"></td>

        <td align="center">
          <button data-bind="click : $root.Editar , attr : { id : IdInventarioInicial() + '_btnEditar' } " class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar InventarioInicial">
            <span class="glyphicon glyphicon-pencil"></span>
          </button>
          <button data-bind="click : $root.PreEliminar" class="btn btn-sm btn-danger btn-operaciones" data-toogle="tooltip" title="Eliminar InventarioInicial">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
          <button data-bind="event: {click : $root.PreImpresionCodigoBarras}"
            class="btn btn-sm btn-print btn-operaciones" data-toogle="tooltip" title="Imprimir Codigo Barras">
              <span class="glyphicon glyphicon-print"></span>
            </button>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</fieldset>
<?php echo $view_subcontent_paginacion_inventarioinicial; ?>
<!-- </div> -->