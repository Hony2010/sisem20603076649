<div class="scrollable scrollbar-macosx">

  <div class="container-fluid">

    <?php echo $view_subcontent_buscador_empresa; ?>

    <table id="DataTables_Table_0" class="datalist__table table display" width="100%" data-products="brand">
      <thead>
        <tr>
          <th class="products__id">Codigo</th>
          <th  class="products__title">Descripcion</th>
          <th class="products__title">Unidad</th>
          <th class="products__title">Marca</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <!-- ko foreach : Empresas -->
        <tr class="clickable-row" data-bind="click : $root.Seleccionar, attr : { id: IdProducto }">

          <td align="left" data-bind="text : CodigoMercaderia">
            <input type="hidden" data-bind="value : IdProducto">
          </td>

          <td data-bind="text : NombreProducto"></td>
          <td data-bind="text : AbreviaturaUnidadMedida"></td>
          <td data-bind="text : NombreMarca"></td>

          <td align="center" >
              <button data-bind="click : $root.Editar , attr : { id : IdProducto() + '_btnEditar' } "
                  class="btn btn-sm btn-warning" data-toogle="tooltip" title="Editar Mercaderia">
                  <span class="fa fa-fw fa-pencil"></span>
              </button>
              <button data-bind="click : $root.PreEliminar"
                class="btn btn-sm btn-danger" data-toogle="tooltip" title="Eliminar Mercaderia">
                <span class="glyphicon glyphicon-trash"></span>
              </button>
          </td>
        </tr>
        <!-- /ko -->
      </tbody>
    </table>
  </div>
</div>
